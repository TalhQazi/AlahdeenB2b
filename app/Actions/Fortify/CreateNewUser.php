<?php

namespace App\Actions\Fortify;

use App\Mail\NewUserWelcome;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\Rule;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    protected $accountTypes;

    public function __construct()
    {
        $this->accountTypes = config('account_types');
    }

    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'account_type' => ['required', 'integer', Rule::in(array_flip(config('account_types'))),],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255', 'unique:users'],
            'phone_full' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => $this->passwordRules(),
            'city_id' => ['required', 'integer'],
            'address' => ['required_if:account_type,3', 'string', 'max:255'],
            'industry' => ['required_if:account_type,2', 'string', 'max:255'],
            'job_freelance' => ['required_if:account_type,3', 'string', 'max:255'],
        ],
        [
            // custom validation messages
            'account_type' => __('Please select appropriate Account Type from the dropdown options.'),
        ],
        [
            // custom attribute values
            'account_type' => 'account type',
            'phone_full' => 'phone number',
        ])->validate();


        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'] ?? null,
                'phone' => $input['phone_full'],
                'password' => Hash::make($input['password']),
                'city_id' => $input['city_id'],
                'address' => $input['address'] ?? null,
                'industry' => $input['industry'] ?? null,
                'job_freelance' => $input['job_freelance'] ?? null,
                'start_as' => $input['start_as'] ?? null,
            ]), function (User $user) use ($input) {
                $this->createTeam($user);
                // assign role to user based on selected account type
                $user->assignRole($this->accountTypes[$input['account_type']]);

                // if ($user->email) {
                //     Mail::send(new NewUserWelcome($user));
                // }
            });
        });
    }

    /**
     * Create a personal team for the user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function createTeam(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0] . "'s Team",
            'personal_team' => true,
        ]));
    }
}

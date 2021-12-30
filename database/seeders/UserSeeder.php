<?php

namespace Database\Seeders;

use App\Models\AdditionalBusinessDetail;
use App\Models\BusinessDetail;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tap(User::create([
            'name' => 'Super Admin', 'email' => 'super.admin@emandii.com', 'phone' => '+923012345678', 'password' => Hash::make('admin123'), 'city_id' => 1
        ]), function (User $user) {

            // assign role to user based on selected account type
            $user->assignRole('super-admin');

        });

        tap(User::create([
            'name' => 'Admin', 'email' => 'admin@emandii.com', 'phone' => '+923012345679', 'password' => Hash::make('admin123'), 'city_id' => 1
        ]), function (User $user) {
            $user->assignRole('admin');
        });

        User::factory()->count(10)->create()->each(function($user) {
            $user->assignRole('corporate');
            $business = $user->business()->save(BusinessDetail::factory()->make());
            $business->additionalDetails()->save(AdditionalBusinessDetail::factory()->make());
        });
    }
}

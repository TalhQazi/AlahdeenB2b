<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\City;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
    /**
    /**
    /**
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randPhoneVerified = [null, now()];
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'city_id' => $this->faker->numberBetween(1, City::count()),
            'email_verified_at' => now(),
            'phone_verified_at' => $randPhoneVerified[array_rand($randPhoneVerified, 1)],
            'password' => Hash::make('12345678'), // password
            'remember_token' => Str::random(10),
        ];
    }
}

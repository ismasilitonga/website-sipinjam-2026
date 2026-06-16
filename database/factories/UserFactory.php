<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
   
    protected static ?string $password;

    /**
     * Definisikan state default untuk model User.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'nim' => fake()->unique()->numerify('33123#####'), 
            'role' => fake()->randomElement(['admin', 'anggota', 'ketua', 'pic', 'pamdal']), 
            'organisasi' => fake()->randomElement(['DPM', 'BEM', 'HMTI', 'HME', 'HMM', 'HMMB', 'IMMPB', 'PD El-Shaddai', 'MENWA', 'MAPALA', 'PEC', 'KUAS', 'BLUG', 'LPM-Paradigma', 'Energi', 'KOP']),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
    
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
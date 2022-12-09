<?php

namespace Database\Factories;

use App\Enum\VerificationTypesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'type' => VerificationTypesEnum::EMAIL
        ];
    }
}

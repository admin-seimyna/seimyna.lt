<?php

namespace Database\Factories\Family;

use App\Enum\GenderEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'gender' => GenderEnum::MALE,
        ];
    }
}

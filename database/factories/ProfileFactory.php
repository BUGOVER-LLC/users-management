<?php

namespace Database\Factories;

use App\Domain\UMAC\Model\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'psn' => $this->faker->word(),
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            'patronymic' => $this->faker->word(),
            'dateBirth' => Carbon::now(),
            'createdAt' => Carbon::now(),
        ];
    }
}

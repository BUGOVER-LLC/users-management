<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\CUM\Model\Citizen;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Model\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CitizenFactory extends Factory
{
    protected $model = Citizen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'citizenId' => $this->faker->randomNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt($this->faker->password()),
            'isActive' => $this->faker->boolean(),
            'lastActivityAt' => Carbon::now(),
            'lastDeactivateAt' => Carbon::now(),
            'updatedAt' => Carbon::now(),
            'createdAt' => Carbon::now(),

            'userId' => User::factory(),
            'profileId' => Profile::factory(),
        ];
    }
}

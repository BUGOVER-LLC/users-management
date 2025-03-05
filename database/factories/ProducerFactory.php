<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Producer\Model\Producer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProducerFactory extends Factory
{
    protected $model = Producer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt($this->faker->password()),
            'createdAt' => Carbon::now(),
        ];
    }
}

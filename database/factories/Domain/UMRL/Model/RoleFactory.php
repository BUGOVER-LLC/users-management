<?php

declare(strict_types=1);

namespace Database\Factories\Domain\UMRL\Model;

use App\Domain\UMRP\Model\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'roleName' => $this->faker->name(),
            'roleDescription' => $this->faker->text(),
            'createdAt' => Carbon::now(),
        ];
    }
}

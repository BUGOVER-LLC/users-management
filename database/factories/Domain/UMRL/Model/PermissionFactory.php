<?php

declare(strict_types=1);

namespace Database\Factories\Domain\UMRL\Model;

use App\Domain\UMRP\Model\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'permissionName' => $this->faker->name(),
            'permissionDescription' => $this->faker->text(),
            'createdAt' => Carbon::now(),
        ];
    }
}

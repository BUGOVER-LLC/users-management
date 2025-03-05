<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Abstract\AbstractSeeder;
use Database\Seeders\concerns\HasCountrySeed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use JsonException;

class DatabaseSeeder extends AbstractSeeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * @throws JsonException
     */
    public function run(): void
    {
//        User::factory()->count(5)->create();
//        Role::factory()->count(10)->create();
//        Permission::factory()->count(50)->create();

//        $this->call(CountriesTableSeed::class);
    }

    /**
     * @throws JsonException
     */
    protected function getJsonData(string $fileName)
    {
        $predefinedCourtTypes = File::get(database_path("seeders/data/$fileName.json"));

        if (!json_validate($predefinedCourtTypes)) {
            throw new JsonException(json_last_error_msg(), json_last_error());
        }

        return json_decode($predefinedCourtTypes, true);
    }
}

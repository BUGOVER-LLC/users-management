<?php

declare(strict_types=1);

namespace Database\Seeders\concerns;

use Illuminate\Support\Collection;
use Infrastructure\Eloquent\Model\Region;

trait HasRegionSeed
{
    /**
     * @throws \JsonException
     */
    protected function createRegions(): Collection
    {
        $predefinedRegions = $this->getJsonData('regions');

        $this->truncate(Region::class);

        $regions = [];

        foreach ($predefinedRegions as $predefinedRegion) {
            $regions[] = Region::create([
                'code' => $predefinedRegion['code'],
                'name' => $predefinedRegion['name'],
            ]);
        }

        return collect($regions);
    }
}

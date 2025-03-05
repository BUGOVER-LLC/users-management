<?php

declare(strict_types=1);

namespace Database\Seeders\concerns;

use Illuminate\Support\Collection;
use Infrastructure\Eloquent\Model\Community;

trait HasCommunitySeed
{
    /**
     * @throws \JsonException
     */
    protected function createCommunities(): Collection
    {
        $predefinedCommunities = $this->getJsonData('communities');

        $this->truncate(Community::class);

        $communities = [];

        foreach ($predefinedCommunities as $predefinedCommunity) {
            $communities[] = Community::create([
                'code' => $predefinedCommunity['code'],
                'name' => $predefinedCommunity['name'],
            ]);
        }

        return collect($communities);
    }
}

<?php

declare(strict_types=1);

namespace App\Core\Abstract;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;
use Symfony\Component\Console\Helper\ProgressBar;

abstract class AbstractSeeder extends Seeder
{
    abstract public function run(): void;

    public function truncate(string $model): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        /**
         * @var ServiceModel $model
         */
        DB::table($model::getTableName())->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);

        $progressBar->start();

        $items = new Collection();

        foreach (range(1, $amount) as $i) {
            $items = $items->merge(
                $createCollectionOfOne()
            );
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->getOutput()->writeln('');

        return $items;
    }
}

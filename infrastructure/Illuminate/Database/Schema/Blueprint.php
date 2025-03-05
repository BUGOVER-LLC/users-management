<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Database\Schema;

use Illuminate\Database\Schema\Blueprint as BaseBlueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;
use Override;

class Blueprint extends BaseBlueprint
{
    #[\Override]
    public function foreignId($column): ForeignIdColumnDefinition|ColumnDefinition
    {
        return $this->addColumnDefinition(new ForeignIdColumnDefinition($this, [
            'type' => 'integer',
            'name' => $column,
            'autoIncrement' => false,
            'unsigned' => true,
        ]));
    }

    #[Override]
    public function timestamps($precision = 0): void
    {
        $this->timestamp(ServiceModel::CREATED_AT, $precision)->useCurrent();

        $this->timestamp(ServiceModel::UPDATED_AT, $precision)->useCurrent();
    }

    #[Override]
    public function softDeletes($column = ServiceModel::DELETED_AT, $precision = 0): ColumnDefinition
    {
        return $this->timestamp($column, $precision)->nullable();
    }

    public function personMorph(null|string $indexName = null): void
    {
        $this->string('personType');

        $this->unsignedBigInteger('personId');

        $this->index(['personType', 'personId'], $indexName);
    }
}

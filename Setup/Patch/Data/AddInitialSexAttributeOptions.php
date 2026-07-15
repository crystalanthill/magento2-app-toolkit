<?php

declare(strict_types=1);


namespace CrystalAnthill\AppToolkit\Setup\Patch\Data;

use CrystalAnthill\AppToolkit\Setup\AddProductAttributeOptions;

class AddInitialSexAttributeOptions //extends AddProductAttributeOptions
{

    protected function getAttributeCode(): string
    {
        return 'sex';
    }

    protected function getOptions(): array
    {
        return [
            ['label' => 'Men\'s', 'sort_order' => 10],
            ['label' => 'Women\'s', 'sort_order' => 20],
            ['label' => 'Kids\'', 'sort_order' => 30],
        ];
    }

    public static function getDependencies(): array
    {
        return [
            CreateSexAttribute::class,
        ];
    }

}
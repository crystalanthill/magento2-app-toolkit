<?php

namespace CrystalAnthill\AppToolkit\Setup\Patch\Data;

use CrystalAnthill\AppToolkit\Setup\AddProductAttributeOptions;

class AddUnisexSexAttributeOptions //extends AddProductAttributeOptions
{
    protected function getAttributeCode(): string
    {
        return 'sex';
    }

    protected function getOptions(): array
    {
        return [
            ['label' => 'Unisex', 'sort_order' => 40],
        ];
    }

    public static function getDependencies(): array
    {
        return [
            AddInitialSexAttributeOptions::class,
        ];
    }
}
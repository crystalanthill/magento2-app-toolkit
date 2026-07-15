<?php

declare(strict_types=1);

namespace CrystalAnthill\AppToolkit\Setup\Patch\Data;

use CrystalAnthill\AppToolkit\Setup\CreateAttributeSet;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreatePretendAttributeSet //extends CreateAttributeSet
{
    public const ATTRIBUTE_SET_NAME = 'Pretend';
    public const ATTRIBUTE_SET_ORDER = 30;

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}

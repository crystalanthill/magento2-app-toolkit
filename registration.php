<?php
/**
 * Copyright © Crystal Anthill (https://www.crystalanthill.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'CrystalAnthill_AppToolkit',
    __DIR__
);
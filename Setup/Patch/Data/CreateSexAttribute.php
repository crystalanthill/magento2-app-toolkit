<?php

namespace CrystalAnthill\AppToolkit\Setup\Patch\Data;

use CrystalAnthill\AppToolkit\Setup\AttributeSetAssigner;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateSexAttribute //implements DataPatchInterface
{
    private const ATTRIBUTE_CODE = 'sex';
    private const ATTRIBUTE_LABEL = 'Sex';

    const ATTRIBUTE_SETS = [
        CreatePretendAttributeSet::class => CreatePretendAttributeSet::ATTRIBUTE_SET_NAME,
    ];

    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private EavSetupFactory          $eavSetupFactory,
        private AttributeSetAssigner     $attributeSetAssigner
    )
    {}

    public static function getDependencies(): array
    {
        return \array_keys(self::ATTRIBUTE_SETS);
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): CreateSexAttribute
    {
        /** @var EavSetup $setup */
        $setup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $setup->addAttribute(Product::ENTITY, self::ATTRIBUTE_CODE, [
            'label'            => self::ATTRIBUTE_LABEL,
            'type'             => 'int',
            'input'            => 'select',
            'required'         => false,
            'visible_on_front' => true,
            'user_defined'     => true
        ]);

        $this->attributeSetAssigner
            ->assign(self::ATTRIBUTE_CODE, \array_values(self::ATTRIBUTE_SETS));

        return $this;
    }
}

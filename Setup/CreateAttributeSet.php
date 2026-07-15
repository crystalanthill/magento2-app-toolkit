<?php

declare(strict_types=1);

namespace CrystalAnthill\AppToolkit\Setup;

use Exception;
use Magento\Catalog\Setup\CategorySetup;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set as AttributeSetResource;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

abstract class CreateAttributeSet implements DataPatchInterface
{
    public const ATTRIBUTE_SET_NAME = null;
    public const ATTRIBUTE_SET_ORDER = 20;

    public function __construct(
        protected ModuleDataSetupInterface $moduleDataSetup,
        protected AttributeSetFactory $attributeSetFactory,
        protected AttributeSetResource $attributeSetResource,
        protected CategorySetupFactory $categorySetupFactory,
    ) {
    }

    public function apply(): self
    {
        if (static::ATTRIBUTE_SET_NAME === null || static::ATTRIBUTE_SET_ORDER === null) {
            throw new Exception('Attribute set name or attribute set order not set.');
        }

        /** @var Set $attributeSet */
        $attributeSet = $this->attributeSetFactory->create();

        /** @var CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create([
            'setup' => $this->moduleDataSetup,
        ]);

        $attributeSetId = $categorySetup->getDefaultAttributeSetId(
            CategorySetup::CATALOG_PRODUCT_ENTITY_TYPE_ID
        );

        $attributeSet->setData([
            'attribute_set_name' => static::ATTRIBUTE_SET_NAME,
            'entity_type_id'     => CategorySetup::CATALOG_PRODUCT_ENTITY_TYPE_ID,
            'sort_order'         => static::ATTRIBUTE_SET_ORDER,
        ]);

        $attributeSet->validate();
        $this->attributeSetResource->save($attributeSet);
        $attributeSet->initFromSkeleton($attributeSetId);
        $this->attributeSetResource->save($attributeSet);

        return $this;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
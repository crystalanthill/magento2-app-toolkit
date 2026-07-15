<?php

declare(strict_types=1);

namespace CrystalAnthill\AppToolkit\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeManagementInterface;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AttributeSetAssigner
{
    private const DEFAULT_GROUP = 'General';
    private const DEFAULT_SORT_ORDER = 999;

    public function __construct(
        private readonly EavSetup $eavSetup,
        private readonly AttributeManagementInterface $attributeManagement,
        private readonly ModuleDataSetupInterface $moduleDataSetup
    ) {
    }

    /**
     * @return array<string, int|string>
     */
    private function getAttributeSets(): array
    {
        $connection = $this->moduleDataSetup->getConnection();

        $select = $connection->select()
            ->from(
                $this->moduleDataSetup->getTable('eav_attribute_set'),
                ['attribute_set_name', 'attribute_set_id']
            )
            ->where('entity_type_id = :entity_type_id');

        $rows = $connection->fetchAll($select, [
            'entity_type_id' => $this->eavSetup->getEntityTypeId(Product::ENTITY),
        ]);

        $attributeSets = [];

        foreach ($rows as $row) {
            $attributeSets[$row['attribute_set_name']] = (int) $row['attribute_set_id'];
        }

        return $attributeSets;
    }

    public function assign(string $code, array $sets, string $group = self::DEFAULT_GROUP): void
    {
        $this->assignToSets($code, $group, $sets);
    }

    public function assignToAll(string $code, string $group = self::DEFAULT_GROUP): void
    {
        $this->assignToSets($code, $group);
    }

    private function assignToSets(string $code, string $group, ?array $allowedSetNames = null): void
    {
        foreach ($this->getAttributeSets() as $attributeSetName => $attributeSetId) {
            if (!$this->shouldAssignToSet($attributeSetName, $allowedSetNames)) {
                continue;
            }


            $groupId = $this->eavSetup->getAttributeGroupId(
                Product::ENTITY,
                $attributeSetId,
                $group
            );

            if (!$groupId) {
                continue;
            }

            $this->attributeManagement->assign(
                Product::ENTITY,
                $attributeSetId,
                $groupId,
                $code,
                self::DEFAULT_SORT_ORDER
            );
        }
    }

    private function shouldAssignToSet(string $attributeSetName, ?array $allowedSetNames): bool
    {
        if ($allowedSetNames === null) {
            return true;
        }

        return in_array($attributeSetName, $allowedSetNames, true);
    }
}
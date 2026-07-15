<?php

declare(strict_types=1);

namespace CrystalAnthill\AppToolkit\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeOptionManagementInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Eav\Api\Data\AttributeOptionInterface;
use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

abstract class AddProductAttributeOptions implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly AttributeRepositoryInterface $attributeRepository,
        private readonly AttributeOptionManagementInterface $optionManagement,
        private readonly AttributeOptionInterfaceFactory $optionFactory
    ) {
    }

    abstract protected function getAttributeCode(): string;

    /**
     * List options do add.
     *
     * Acceptable formats:
     * [
     *     'Men\'s',
     *     'Women\'s',
     *     'Unisex',
     * ]
     *
     * or:
     * [
     *     ['label' => 'Men\'s', 'sort_order' => 10],
     *     ['label' => 'Women\'s', 'sort_order' => 20],
     * ]
     */
    abstract protected function getOptions(): array;

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): self
    {
        $connection = $this->moduleDataSetup->getConnection();
        $connection->startSetup();

        try {
            $attributeCode = $this->getAttributeCode();

            $attribute = $this->attributeRepository->get(Product::ENTITY, $attributeCode);

            $existingLabels = [];
            foreach ((array) $attribute->getOptions() as $existingOption) {
                $label = $this->normalizeLabel((string) $existingOption->getLabel());

                if ($label !== '') {
                    $existingLabels[$label] = true;
                }
            }

            foreach ($this->getOptions() as $index => $optionData) {
                $option = $this->prepareOptionData($optionData, $index);
                $normalizedLabel = $this->normalizeLabel($option['label']);

                if ($normalizedLabel === '') {
                    continue;
                }

                if (isset($existingLabels[$normalizedLabel])) {
                    continue;
                }

                $attributeOption = $this->buildOption(
                    $option['label'],
                    $option['sort_order']
                );

                $this->optionManagement->add(
                    Product::ENTITY,
                    $attributeCode,
                    $attributeOption
                );

                $existingLabels[$normalizedLabel] = true;
            }
        } catch (NoSuchEntityException $e) {
            throw new LocalizedException(
                __('Product attribute "%1" does not exist.', $this->getAttributeCode()),
                $e
            );
        } finally {
            $connection->endSetup();
        }

        return $this;
    }

    protected function normalizeLabel(string $label): string
    {
        return mb_strtolower(trim($label));
    }

    protected function prepareOptionData(string|array $optionData, int $index): array
    {
        if (is_string($optionData)) {
            return [
                'label' => $optionData,
                'sort_order' => ($index + 1) * 10,
            ];
        }

        return [
            'label' => (string) ($optionData['label'] ?? ''),
            'sort_order' => (int) ($optionData['sort_order'] ?? (($index + 1) * 10)),
        ];
    }

    protected function buildOption(string $label, int $sortOrder): AttributeOptionInterface
    {
        $option = $this->optionFactory->create();
        $option->setLabel($label);
        $option->setSortOrder($sortOrder);

        return $option;
    }
}
<?php

namespace DeployEcommerce\BuilderIO\Model\Config\Source;

use DeployEcommerce\BuilderIO\Api\ProductCollectionInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Type implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => ProductCollectionInterface::TYPE_CONDITION, 'label' => __('Condition')],
            ['value' => ProductCollectionInterface::TYPE_SKU, 'label' => __('Sku')],
            ['value' => ProductCollectionInterface::TYPE_CATEGORY, 'label' => __('Category')],
        ];
    }
}

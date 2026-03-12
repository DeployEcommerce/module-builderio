<?php
declare(strict_types=1);

/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIOProductCollections\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CategorySortBy implements OptionSourceInterface
{
    /**
     * Convert to option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'position', 'label' => __('Position')],
            ['value' => 'name', 'label' => __('Product Name')],
            ['value' => 'price', 'label' => __('Price')],
        ];
    }
}

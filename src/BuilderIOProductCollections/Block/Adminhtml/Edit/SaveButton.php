<?php
declare(strict_types=1);

/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIOProductCollections\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}

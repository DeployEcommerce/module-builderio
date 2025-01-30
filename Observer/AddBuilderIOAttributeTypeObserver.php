<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Observer;

use DeployEcommerce\BuilderIO\Model\ContentSectionModel;
use Magento\Framework\Module\Manager;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Observer model
 */
class AddBuilderIOAttributeTypeObserver implements ObserverInterface
{
    /**
     * Execute.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Framework\DataObject $response */
        $response = $observer->getEvent()->getResponse();

        $types = $response->getTypes();

        $types[] = [
            'value' => ContentSectionModel::BUILDERIO_TYPE_ATTRIBUTE_FRONTEND_INPUT,
            'label' => __('BuilderIO (Automatic URL-matched)'),
            'hide_fields' => [
                'is_unique',
                'is_required',
                'frontend_class',
                '_scope',
                '_default_value',
                '_front_fieldset',
            ],
        ];

        $types[] = [
            'value' => ContentSectionModel::BUILDERIO_TYPE_ATTRIBUTE_FRONTEND_SELECT,
            'label' => __('BuilderIO (Manually selected)'),
            'hide_fields' => [
                'is_unique',
                'is_required',
                'frontend_class',
                '_scope',
                '_default_value',
                '_front_fieldset',
            ],
        ];

        $response->setTypes($types);
    }
}

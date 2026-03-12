<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model\ResourceModel\WebhookModel;

use DeployEcommerce\BuilderIO\Model\ResourceModel\WebhookResource;
use DeployEcommerce\BuilderIO\Model\WebhookModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class WebhookCollection
 *
 * This class represents the collection of Webhook entities.
 * It extends the AbstractCollection class to provide collection-specific functionality.
 *
 */
class WebhookCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'webhook_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(WebhookModel::class, WebhookResource::class);
    }
}

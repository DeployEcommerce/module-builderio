<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model\ResourceModel;

use DeployEcommerce\BuilderIO\Api\Data\WebhookInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class WebhookResource
 *
 * This class represents the resource model for the Webhook entity.
 * It extends the AbstractDb class to provide basic CRUD operations.
 *
 */
class WebhookResource extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'webhook_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('builderio_webhook', WebhookInterface::WEBHOOK_ID);
        $this->_useIsObjectNew = true;
    }
}

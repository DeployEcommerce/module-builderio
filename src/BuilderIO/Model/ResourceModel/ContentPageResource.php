<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use DeployEcommerce\BuilderIO\Api\Data\ContentPageInterface;

/**
 * Class ContentPageResource
 *
 * This class is a resource model for the ContentPage model.
 *
 *
 */
class ContentPageResource extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'builderio_content_page_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('builderio_content_page', ContentPageInterface::ID);
        $this->_useIsObjectNew = true;
    }

    /**
     * @inheritDoc
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->isObjectNew()) {
            $object->setCreatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
        }
        $object->setUpdatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
        return parent::_beforeSave($object);
    }
}

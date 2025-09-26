<?php
declare(strict_types=1);
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Model\ResourceModel\ContentStructuredDataModel;

use DeployEcommerce\BuilderIO\Model\ContentStructuredDataModel;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ContentStructuredDataResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class ContentStructuredDataCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'builderio_content_structured_data_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(ContentStructuredDataModel::class, ContentStructuredDataResource::class);
    }
}

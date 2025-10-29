<?php
declare(strict_types=1);
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Model\ResourceModel\ContentSectionModel;

use DeployEcommerce\BuilderIO\Model\ContentSectionModel;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ContentSectionResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class ContentSectionCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'builderio_content_section_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(ContentSectionModel::class, ContentSectionResource::class);
    }
}

<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model\ResourceModel\ContentPageModel;

use DeployEcommerce\BuilderIO\Model\ContentPageModel;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ContentPageResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class ContentPageCollection
 *
 * This class is a collection model for the ContentPage model.
 *
 */
class ContentPageCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'builderio_content_page_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(ContentPageModel::class, ContentPageResource::class);
    }
}

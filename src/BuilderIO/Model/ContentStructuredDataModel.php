<?php
declare(strict_types=1);
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Model;

use DeployEcommerce\BuilderIO\Model\ResourceModel\ContentStructuredDataResource;
use Magento\Framework\Model\AbstractModel;

class ContentStructuredDataModel extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'builderio_content_structured_data_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ContentStructuredDataResource::class);
    }
}

<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 */

namespace DeployEcommerce\BuilderIO\Model;

use DeployEcommerce\BuilderIO\Model\ResourceModel\ContentSectionResource;
use Magento\Framework\Model\AbstractModel;

class ContentSectionModel extends AbstractModel
{
    const BUILDERIO_TYPE_ATTRIBUTE_FRONTEND_INPUT = 'text';

    const BUILDERIO_TYPE_ATTRIBUTE_FRONTEND_SELECT = 'select';

    /**
     * @var string
     */
    protected $_eventPrefix = 'builderio_content_section_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ContentSectionResource::class);
    }
}

<?php
declare(strict_types=1);
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ContentSectionResource extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'builderio_content_section_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('builderio_content_section', 'builderio_section_id');
        $this->_useIsObjectNew = true;
    }
}

<?php
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductCollection extends AbstractDb
{
    private const TABLE_NAME = 'builderio_collections';
    private const PRIMARY_KEY = 'id';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::PRIMARY_KEY);
    }
}

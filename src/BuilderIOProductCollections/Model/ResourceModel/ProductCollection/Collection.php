<?php

declare(strict_types=1);

namespace DeployEcommerce\BuilderIOProductCollections\Model\ResourceModel\ProductCollection;

use DeployEcommerce\BuilderIO\Model\ProductCollection as Model;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection as ResourceModel;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class Collection extends SearchResult
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'builderio_collections',
        $resourceModel = ResourceModel::class
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    protected function _afterLoad()
    {
        foreach ($this->_items as $item) {
            $item->setConfig(json_decode($item->getConfig(), true));
        }
        return parent::_afterLoad();
    }

    public function afterLoad(DataObject $object)
    {
        $object->setConfig(json_decode($object->getConfig()));
        parent::afterLoad($object);
    }
}

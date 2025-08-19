<?php
declare(strict_types=1);

/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Model;

use DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection\CollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var \DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var PoolInterface
     */
    private $pool;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->pool = $pool ?: ObjectManager::getInstance()->get(PoolInterface::class);
    }

    /**
     * Get form data for UI component.
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $this->loadedData = [];
        $id = $this->request->getParam('id');

        if ($id) {
            $model = $this->collection->getItemById($id);
            if ($model) {
                $this->loadedData[$id] = $this->convertValues($model);
            }
        }

        $data = $this->dataPersistor->get('builderio_product_collection');
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $this->convertValues($model);
            $this->dataPersistor->clear('builderio_product_collection');
        }

        return $this->loadedData;
    }

    /**
     * Convert model data to array format for forms.
     *
     * @param \DeployEcommerce\BuilderIO\Model\ProductCollection $model
     * @return array
     */
    private function convertValues($model)
    {
        $data = $model->getData();
        $rule = $model->getData('rule');
        if (isset($rule['conditions_serialized'])) {
            $conditions = json_decode($rule['conditions_serialized'], true);
            $data['conditions'] = $conditions;
        }
        return $data;
    }
}

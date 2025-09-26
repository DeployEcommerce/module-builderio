<?php
declare(strict_types=1);



namespace DeployEcommerce\BuilderIO\Model\Indexer\ProductCollection;

use DeployEcommerce\BuilderIO\Model\ProductCollection;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection as ProductCollectionResource;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection\Collection as ProductCollectionCollection;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection\CollectionFactory as
    ProductCollectionCollectionFactory;
use Magento\Framework\App\ResourceConnection;

class ProductIndexer
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var ProductCollectionCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var ProductCollectionResource
     */

    /**
     * @param ResourceConnection $resourceConnection
     * @param ProductCollectionCollectionFactory $collectionFactory
     * @param ProductCollectionResource $productCollectionResource
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        ProductCollectionCollectionFactory $collectionFactory,
        ProductCollectionResource $productCollectionResource,
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Reindex all collections.
     *
     * @return void
     */
    public function reindexAll(): void
    {
        $collection = $this->collectionFactory->create();
        $this->reindexRows($collection->getAllIds());
    }

    /**
     * Reindex single row.
     *
     * @param int $id
     * @return void
     */
    public function reindexRow(int $id): void
    {
        $this->reindexRows([$id]);
    }

    /**
     * Reindex multiple rows.
     *
     * @param array $ids
     * @return void
     */
    public function reindexRows(array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('builderio_collections_product_index');

        $connection->delete($tableName, ['collection_id IN (?)' => $ids]);

        /** @var ProductCollectionCollection $collection */
        $collection = $this->collectionFactory->create()->addFieldToFilter('id', ['in' => $ids]);
        $products = $collection->getItems();
        /** @var ProductCollection $productCollection */
        foreach ($collection as $productCollection) {
            $products = $productCollection->getProducts();
            $productIds = array_map(function ($product) {
                return $product['entity_id'];
            }, $products);

            if (empty($productIds)) {
                continue;
            }

            $data = [];
            foreach ($productIds as $productId) {
                $data[] = [
                    'collection_id' => $productCollection->getId(),
                    'product_id' => $productId,
                ];
            }

            if (!empty($data)) {
                $connection->insertMultiple($tableName, $data);
            }
        }
    }
}

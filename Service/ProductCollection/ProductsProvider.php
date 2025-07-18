<?php

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Service\ProductCollection;

use DeployEcommerce\BuilderIO\Api\Data\ProductCollectionResultInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\ResourceConnection;

class ProductsProvider implements ProductCollectionResultInterface
{

    static public function getCacheKey(int $collectionId) {
        return self::CACHE_KEY . $collectionId;
    }

    const CACHE_KEY = "product_collection_webapi_";

    private $productCount;


    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param ResourceConnection $resourceConnection
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        ProductRepositoryInterface $productRepository,
        private CollectionFactory $productCollectionFactory,
        private CacheInterface $cache
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->productRepository = $productRepository;
    }

    /**
     * @param int $collectionId
     * @return array
     */
    public function getProductCollection(int $collectionId): array
    {
        if($payload = $this->cache->load(self::getCacheKey($collectionId) . $collectionId)) {
            return json_decode($payload, true);
        }

        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('builderio_product_collection_product_index');

        $select = $connection->select()->from($tableName, 'product_id')->where('collection_id = ?', $collectionId);
        $productIds = $connection->fetchCol($select);

        if (empty($productIds)) {
            return [];
        }

        $productCollection = $this->productCollectionFactory->create();
        $productCollection = $productCollection
            ->addFieldToFilter('entity_id', [ "in" => $productIds])
            ->addTierPriceData()
            ->addPriceData()
            ->addMediaGalleryData()
            ->addCategoryIds()
            ->addFinalPrice();

        $products = $productCollection->getItems();

        $products = array_map(function ($product) {return $product->getData();}, array_values($products));

        //double wrap to preserve keys
        $payload = [[
            'products' => $products,
            'count' => count($productIds)
        ]];
        $this->cache->save(self::getCacheKey($collectionId), json_encode($payload));

        return $payload;
    }
}

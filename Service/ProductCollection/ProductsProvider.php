<?php
/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Service\ProductCollection;

use DeployEcommerce\BuilderIO\Api\Data\ProductCollectionResultInterface;
use Magento\Catalog\Model\Config as CatalogConfig;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;

class ProductsProvider implements ProductCollectionResultInterface
{
    const CACHE_KEY = "product_collection_webapi_";

    /**
     * ProductsProvider constructor.
     *
     * @param ResourceConnection $resourceConnection
     * @param ProductRepositoryInterface $productRepository
     * @param CollectionFactory $productCollectionFactory
     * @param CatalogConfig $catalogConfig
     * @param EavConfig $eavConfig
     * @param CacheInterface $cache
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private ResourceConnection $resourceConnection,
        private ProductRepositoryInterface $productRepository,
        private CollectionFactory $productCollectionFactory,
        private CatalogConfig $catalogConfig,
        private EavConfig $eavConfig,
        private CacheInterface $cache,
        private StoreManagerInterface $storeManager
    ) {
    }

    /**
     * Get product collection by collection ID.
     *
     * @param int $collectionId
     * @return array
     */
    public function getProductCollection(int $collectionId): array
    {
        try {
            $storeId = $this->storeManager->getStore()->getId();
        } catch (\Exception $e) {
            $storeId = $this->storeManager->getDefaultStoreView()->getId();
        }

        $cacheKey = self::getCacheKey($collectionId, (int) $storeId);

        if ($payload = $this->cache->load($cacheKey)) {
            return json_decode($payload, true);
        }

        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('builderio_product_collection_product_index');

        $select = $connection
            ->select()
            ->from($tableName, 'product_id')
            ->where('collection_id = ?', $collectionId);

        $productIds = $connection->fetchCol($select);

        if (empty($productIds)) {
            return [[
                'products' => [],
                'count' => 0
            ]];
        }

        $productCollection = $this->productCollectionFactory->create();

        $productCollection->addFieldToFilter('entity_id', [ "in" => $productIds])
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite()
            ->addAttributeToFilter('visibility', ([
                Visibility::VISIBILITY_IN_CATALOG,
                Visibility::VISIBILITY_BOTH
            ]));

        $loadvalues = $this->getAttributesToLoadValues();

        // Process image URLs for each product
        foreach ($productCollection->getItems() as $product) {
            $this->processProductImageUrls($product);

            // Ensure the product is loaded with all necessary data
            if(!empty($loadvalues)) {
                foreach ($loadvalues as $loadvalue) {
                    $value = $product->getAttributeText($loadvalue);

                    if(is_array($value)) {
                        $product->setData($loadvalue, implode(', ', $value));
                    } else {
                        $product->setData($loadvalue, (string) $value);
                    }
                }
            }
        }

        $productCollection = $productCollection->toArray();

        $payload = [[
            'store_id' => $storeId,
            'products' => $productCollection,
            'count' => count($productCollection)
        ]];

        $this->cache->save($cacheKey, json_encode($payload));

        return $payload;
    }

    /**
     * Get attributes that need to be loaded with values.
     * This method checks the product attributes configured in the catalog
     *
     * @return array
     */
    private function getAttributesToLoadValues(): array
    {
        $loadvalues = [];
        foreach ($this->catalogConfig->getProductAttributes() as $attribute) {
            $attribute = $this->eavConfig->getAttribute('catalog_product', $attribute);
            if ($attribute->getFrontendInput() === 'select' || $attribute->getFrontendInput() === 'multiselect') {
                $loadvalues[] = $attribute->getAttributeCode();
            }
        }
        return $loadvalues;
    }

    /**
     * Process product image URLs to return full URLs.
     *
     * @param DataObject $product
     * @return void
     */
    private function processProductImageUrls(DataObject $product): void
    {
        $imageAttributes = ['image', 'small_image', 'thumbnail'];
        $store = $this->storeManager->getStore();
        $mediaUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        foreach ($imageAttributes as $imageType) {
            $imageFile = $product->getData($imageType);

            if ($imageFile && $imageFile !== 'no_selection') {
                // Generate full image URL using direct media URL approach
                $imageUrl = $mediaUrl . 'catalog/product' . $imageFile;
                $product->setData($imageType . '_url', $imageUrl);

            } else {
                // For products without images, set a proper placeholder URL
                $placeholderUrl = $mediaUrl . 'catalog/product/placeholder/' . $imageType . '.jpg';
                $product->setData($imageType . '_url', $placeholderUrl);
            }
        }
    }

    /**
     * Get the cache key for the product collection.
     *
     * @param int $collectionId
     * @return string
     */
    public static function getCacheKey(int $collectionId, int $storeId): string
    {
        return self::CACHE_KEY . $collectionId . '_' . $storeId;
    }
}

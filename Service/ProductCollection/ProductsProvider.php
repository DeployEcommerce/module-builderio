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
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Tax\Helper\Data as TaxHelper;
use Magento\Framework\App\Area;
use Magento\Store\Model\App\Emulation;

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
     * @param PricingHelper $pricingHelper
     * @param TaxHelper $taxHelper
     */
    public function __construct(
        private ResourceConnection $resourceConnection,
        private ProductRepositoryInterface $productRepository,
        private CollectionFactory $productCollectionFactory,
        private CatalogConfig $catalogConfig,
        private EavConfig $eavConfig,
        private CacheInterface $cache,
        private StoreManagerInterface $storeManager,
        private PricingHelper $pricingHelper,
        private TaxHelper $taxHelper,
        private ImageBuilder $imageBuilder,
        private Emulation $emulation
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
            return [];
        }

        $productCollection = $this->productCollectionFactory->create();

        $productCollection->addFieldToFilter('entity_id', [ "in" => $productIds])
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite()
            ->addPriceData()
            ->addAttributeToFilter('visibility', ([
                Visibility::VISIBILITY_IN_CATALOG,
                Visibility::VISIBILITY_BOTH
            ]));

        $loadvalues = $this->getAttributesToLoadValues();
        $products = [];

        $this->emulation->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND, true);

        // Process image URLs for each product
        foreach ($productCollection->getItems() as $product) {

            $products[$product->getId()] = [
                'name' => $product->getName(),
                'sku' => $product->getSku(),
                'url' => $product->getProductUrl(),
                'image' => $this->imageBuilder->create($product, 'product_page_image_large')->getImageUrl(),
                'small_image' => $this->imageBuilder->create($product, 'product_page_image_small')->getImageUrl(),
                'thumbnail' => $this->imageBuilder->create($product, 'product_thumbnail_image')->getImageUrl(),
                'type_id' => $product->getTypeId(),
                'visibility' => $product->getVisibility(),
                'status' => $product->getStatus(),
                'weight' => $product->getWeight(),
            ];

            $final_price = $product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();
            $regular_price = $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();

            if ($final_price < $regular_price) {
                $products[$product->getId()]['regular_price']['label'] = __('Regular Price')->getText();
                $products[$product->getId()]['regular_price']['value'] = $this->getFormattedPrice($regular_price);

                $products[$product->getId()]['price']['label'] = __('Special Price')->getText();
                $products[$product->getId()]['price']['value'] = $this->getFormattedPrice($final_price);
            } else {
                $products[$product->getId()]['price']['label'] = __('Regular Price')->getText();
                $products[$product->getId()]['price']['value'] = $this->getFormattedPrice($final_price);
            }

            if ($product->getTypeId() == 'configurable') {
                $products[$product->getId()]['price_label'] = __('From')->getText();

                $products[$product->getId()]['configurable_options'] = [];
                foreach ($product->getTypeInstance()->getConfigurableAttributesAsArray($product) as $attribute) {
                    $options = [];
                    foreach ($attribute['values'] as $value) {
                        $options[] = [
                            'value_index' => $value['value_index'],
                            'label' => $value['store_label']
                        ];
                    }
                    $products[$product->getId()]['configurable_options'][] = [
                        'attribute_code' => $attribute['attribute_code'],
                        'label' => $attribute['label'],
                        'values' => $options
                    ];
                }
            }

            // Ensure the product is loaded with all necessary data
            if (!empty($loadvalues)) {
                foreach ($loadvalues as $loadvalue) {
                    $value = $product->getAttributeText($loadvalue);

                    if (is_array($value)) {
                        $product->setData($loadvalue, implode(', ', $value));
                        $products[$product->getId()][$loadvalue] = implode(', ', $value);
                    } else {
                        $product->setData($loadvalue, (string) $value);
                        $products[$product->getId()][$loadvalue] = (string) $value;
                    }
                }
            }
        }

        $this->cache->save($cacheKey, json_encode($products));

        return $products;
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

    private function getFormattedPrice($price): string
    {
        return $this->pricingHelper->currency($price, true, false);
    }

    /**
     * Get the cache key for the product collection.
     *
     * @param int $collectionId
     * @param int $storeId
     * @return string
     */
    public static function getCacheKey(int $collectionId, int $storeId): string
    {
        return self::CACHE_KEY . $collectionId . '_' . $storeId;
    }
}

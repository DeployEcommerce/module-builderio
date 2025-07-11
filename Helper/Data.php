<?php
/**
 * @Author:    Brandon van Rensburg
 * @Copyright: 2025 DeployEcommerce (https://www.techarlie.co.za/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Helper;

use DeployEcommerce\BuilderIO\Api\ProductCollectionInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product\Attribute\Repository as ProductAttributeRepository;
use Magento\Catalog\Model\Product\Url as ProductUrl;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filter\FilterManager;

class Data extends AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Repository
     */
    protected $productAttributeRepository;

    /**
     * @var \Magento\Framework\Filter\FilterManager
     */
    protected $filterManager;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var \Magento\Catalog\Model\Product\Url
     */
    protected $productUrl;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository
     * @param \Magento\Framework\Filter\FilterManager $filterManager
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Catalog\Model\Product\Url $productUrl
     */
    public function __construct(
        Context $context,
        ProductCollectionFactory $productCollectionFactory,
        CategoryFactory $categoryFactory,
        ProductAttributeRepository $productAttributeRepository,
        FilterManager $filterManager,
        ImageHelper $imageHelper,
        ProductUrl $productUrl
    ) {
        parent::__construct($context);
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->filterManager = $filterManager;
        $this->imageHelper = $imageHelper;
        $this->productUrl = $productUrl;
    }

    /**
     * Get product count based on collection type and config.
     *
     * @param \DeployEcommerce\BuilderIO\Api\ProductCollectionInterface $productCollection
     * @return int
     */
    public function getProductCount(ProductCollectionInterface $productCollection): int
    {
        $count = 0;
        $type = $productCollection->getType();
        $config = json_decode($productCollection->getConfig(), true);

        if (!$config) {
            return $count;
        }

        $collection = $this->productCollectionFactory->create()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToFilter('status', ['in' => $this->scopeConfig->getValue('catalog/frontend/flat_catalog_product_status') ? [1] : [\Magento\Catalog\Model\Product\Status::STATUS_ENABLED]])
            ->addStoreFilter($this->scopeConfig->getValue('store', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));

        switch ($type) {
            case 'category':
                if (isset($config['category_id'])) {
                    $category = $this->categoryFactory->create()->load($config['category_id']);
                    if ($category->getId()) {
                        $collection->addCategoryFilter($category);
                    }
                }
                break;
            case 'sku':
                if (isset($config['skus'])) {
                    $skus = explode(',', $config['skus']);
                    $skus = array_map('trim', $skus);
                    $collection->addAttributeToFilter('sku', ['in' => $skus]);
                }
                break;
            case 'condition':
                // This is a simplified example. Magento's condition builder is complex.
                // You would typically use a \Magento\Rule\Model\Condition\CombineFactory
                // and apply the conditions to the product collection.
                // For now, we'll just return 0 for condition type.
                return 0;
        }

        $count = $collection->getSize();

        return $count;
    }

    /**
     * Generate URL key from collection name.
     *
     * @param \DeployEcommerce\BuilderIO\Api\ProductCollectionInterface $productCollection
     * @return string
     */
    public function generateUrlKey(ProductCollectionInterface $productCollection): string
    {
        return $this->filterManager->translitUrl($productCollection->getName());
    }

    /**
     * Get products based on collection type and config.
     *
     * @param \DeployEcommerce\BuilderIO\Api\ProductCollectionInterface $productCollection
     * @return array
     */
    public function getProductsByCollection(ProductCollectionInterface $productCollection): array
    {
        $productsData = [];
        $type = $productCollection->getType();
        $config = json_decode($productCollection->getConfig(), true);

        if (!$config) {
            return $productsData;
        }

        $collection = $this->productCollectionFactory->create()
            ->addAttributeToSelect(['name', 'price', 'small_image', 'url_key', 'sku'])
            ->addAttributeToFilter('status', ['in' => [\Magento\Catalog\Model\Product\Status::STATUS_ENABLED]])
            ->addAttributeToFilter('visibility', ['in' => [\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH, \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG, \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_SEARCH]])
            ->addStoreFilter($this->scopeConfig->getValue('store', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));

        switch ($type) {
            case 'category':
                if (isset($config['category_id'])) {
                    $category = $this->categoryFactory->create()->load($config['category_id']);
                    if ($category->getId()) {
                        $collection->addCategoryFilter($category);
                    }
                }
                if (isset($config['sort_by'])) {
                    $collection->addAttributeToSort($config['sort_by'], 'asc');
                }
                if (isset($config['limit'])) {
                    $collection->setPageSize($config['limit']);
                }
                break;
            case 'sku':
                if (isset($config['skus'])) {
                    $skus = explode(',', $config['skus']);
                    $skus = array_map('trim', $skus);
                    $collection->addAttributeToFilter('sku', ['in' => $skus]);
                }
                break;
            case 'condition':
                // For condition type, you would apply the conditions from $config['conditions']
                // This is a complex part and requires Magento's rule processing.
                // For now, we'll return an empty array.
                return [];
        }

        foreach ($collection as $product) {
            $productsData[] = [
                'id' => $product->getId(),
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'image' => $this->imageHelper->init($product, 'product_small_image')->getUrl(),
                'url' => $this->productUrl->getUrl($product),
            ];
        }

        return $productsData;
    }
}

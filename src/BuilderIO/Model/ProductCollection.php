<?php
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model;

use DeployEcommerce\BuilderIO\Api\ProductCollectionInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogRule\Helper\Data;
use Magento\CatalogRule\Model\Indexer\Rule\RuleProductProcessor;
use Magento\CatalogRule\Model\ResourceModel\Product\ConditionsToCollectionApplier;
use Magento\CatalogRule\Model\ResourceModel\Rule as RuleResourceModel;
use Magento\CatalogRule\Model\Rule;
use Magento\CatalogRule\Model\Rule\Action\CollectionFactory as RuleCollectionFactory;
use Magento\CatalogRule\Model\Rule\Condition\CombineFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Model\ResourceModel\Iterator;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;

class ProductCollection extends Rule implements ProductCollectionInterface
{
    /**
     * Cache tag for product collection
     */
    public const CACHE_TAG = 'buildeio_product_collection';

    /**
     * Event prefix for product collection
     *
     * @var string
     */
    protected $_eventPrefix = 'builderio_product_collection';

    /**
     * Event object identifier
     *
     * @var string
     */
    protected $_eventObject = 'rule';

    /**
     * Number of products in the collection
     *
     * @var int
     */
    protected int $productCount;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param TimezoneInterface $localeDate
     * @param CollectionFactory $productCollectionFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param StoreManagerInterface $storeManager
     * @param CombineFactory $combineFactory
     * @param RuleCollectionFactory $actionCollectionFactory
     * @param ProductFactory $productFactory
     * @param Iterator $resourceIterator
     * @param Session $customerSession
     * @param Data $catalogRuleData
     * @param TypeListInterface $cacheTypesList
     * @param DateTime $dateTime
     * @param RuleProductProcessor $ruleProductProcessor
     * @param ProductRepositoryInterface $productRepository
     * @param ConditionsToCollectionApplier $conditionsToCollectionApplier
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $relatedCacheTypes
     * @param array $data
     * @param ExtensionAttributesFactory|null $extensionFactory
     * @param AttributeValueFactory|null $customAttributeFactory
     * @param Json|null $serializer
     * @param RuleResourceModel|null $ruleResourceModel
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        TimezoneInterface $localeDate,
        private CollectionFactory $productCollectionFactory,
        private CategoryRepositoryInterface $categoryRepository,
        StoreManagerInterface $storeManager,
        CombineFactory $combineFactory,
        RuleCollectionFactory $actionCollectionFactory,
        ProductFactory $productFactory,
        Iterator $resourceIterator,
        Session $customerSession,
        Data $catalogRuleData,
        TypeListInterface $cacheTypesList,
        DateTime $dateTime,
        RuleProductProcessor $ruleProductProcessor,
        private ProductRepositoryInterface $productRepository,
        ConditionsToCollectionApplier $conditionsToCollectionApplier,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $relatedCacheTypes = [],
        array $data = [],
        ExtensionAttributesFactory $extensionFactory = null,
        AttributeValueFactory $customAttributeFactory = null,
        Json $serializer = null,
        RuleResourceModel $ruleResourceModel = null,
    ) {
        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $localeDate,
            $productCollectionFactory,
            $storeManager,
            $combineFactory,
            $actionCollectionFactory,
            $productFactory,
            $resourceIterator,
            $customerSession,
            $catalogRuleData,
            $cacheTypesList,
            $dateTime,
            $ruleProductProcessor,
            $resource,
            $resourceCollection,
            $relatedCacheTypes,
            $data,
            $extensionFactory,
            $customAttributeFactory,
            $serializer,
            $ruleResourceModel,
            $conditionsToCollectionApplier
        );
    }

    /**
     * After load operations
     *
     * @return $this
     */
    public function afterLoad()
    {
        parent::afterLoad();
        $config = $this->getConfig();
        if (is_string($config)) {
            $this->setConfig(json_decode($config, true));
        }
        return $this;
    }

    /**
     * Before save operations
     *
     * @return ProductCollection
     */
    public function beforeSave()
    {
        if (is_array($this->getConfig())) {
            $this->setConfig(json_encode($this->getConfig()));
        }
        return parent::beforeSave();
    }

    /**
     * Get conditions instance
     *
     * @return \Magento\CatalogRule\Model\Rule\Condition\Combine
     */
    public function getConditionsInstance()
    {
        return $this->_combineFactory->create();
    }

    /**
     * Get actions instance
     *
     * @return \Magento\CatalogRule\Model\Rule\Action\Collection
     */
    public function getActionsInstance()
    {
        return $this->_actionCollectionFactory->create();
    }

    /**
     * Get ID
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * Set ID
     *
     * @param mixed $id
     * @return ProductCollection|\Magento\Framework\Model\AbstractModel
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Get type
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Set type
     *
     * @param string $type
     * @return ProductCollectionInterface
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Get config
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->getData(self::CONFIG);
    }

    /**
     * Set config
     *
     * @param string $config
     * @return ProductCollectionInterface
     */
    public function setConfig($config)
    {
        return $this->setData(self::CONFIG, $config);
    }

    /**
     * Get product count
     *
     * @return mixed
     */
    public function getProductCount()
    {
        return $this->getData(self::PRODUCT_COUNT);
    }

    /**
     * Set product count
     *
     * @param int $productCount
     * @return ProductCollectionInterface
     */
    public function setProductCount($productCount)
    {
        return $this->setData(self::PRODUCT_COUNT, $productCount);
    }

    /**
     * Get URL key
     *
     * @return mixed
     */
    public function getUrlKey()
    {
        return $this->getData(self::URL_KEY);
    }

    /**
     * Set URL key
     *
     * @param string $urlKey
     * @return ProductCollectionInterface
     */
    public function setUrlKey($urlKey)
    {
        return $this->setData(self::URL_KEY, $urlKey);
    }

    /**
     * Get products based on collection type
     *
     * @return array
     */
    public function getProducts()
    {
        // This will join all attributes used in the conditions.

        $matchingProducts = match ($this->getType()) {
            ProductCollectionInterface::TYPE_CATEGORY => $this->getCategoryProducts(),
            ProductCollectionInterface::TYPE_CONDITION => $this->getConditionProducts(),
            ProductCollectionInterface::TYPE_SKU => $this->getSkuProducts(),
            default => [],
        };
        $this->setData("product_count", count($matchingProducts ?? []));

        return $matchingProducts;
    }

    /**
     * Get count of products
     *
     * @return int
     */
    public function getCount()
    {
        return $this->productCount;
    }

    /**
     * Get products matching conditions
     *
     * @return array
     */
    private function getConditionProducts(): array
    {

        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect('*');

        $conditions = $this->getConditions();

        $matchingProducts = [];
        foreach ($productCollection as $product) {
            if ($conditions->validate($product)) {
                $matchingProducts[] = $product;
            }
        }
        return $matchingProducts;
    }

    /**
     * Get products by SKU list
     *
     * @return array
     */
    private function getSkuProducts(): array
    {
        $sku_list = explode(",", $this->getData("config/sku_list"));

        $matchingProducts = [];

        foreach ($sku_list as $sku) {
            $matchingProducts[] = $this->productRepository->get($sku);
        }

        return $matchingProducts;
    }

    /**
     * Get products from category
     *
     * @return array
     */
    private function getCategoryProducts(): array
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $category = $this->categoryRepository->get($this->getData("config/category_id"));

        $productCollection = $category->getProductCollection()
            ->addAttributeToSelect('*'); // Select all attributes

        $order = match ($this->getData("config/category_sort_by")) {
            "price" => ["attribute" => "price", "direction" => "DESC"],
            "name" => ["attribute" => "name", "direction" => "ASC"],
            "position" => ["attribute" => "position", "direction" => "ASC"],
            default => ["attribute" => "name", "direction" => "ASC"],
        };

        $productCollection->setOrder($order['attribute'], $order['direction']);

        $products = $productCollection->getItems();

        if ($this->getData("config/category_limit")) {
            $products = array_slice(array_values($products), 0, (int) $this->getData("config/category_limit"));
        }

        return $products;
    }
}

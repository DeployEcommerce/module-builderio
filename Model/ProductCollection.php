<?php

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model;

use DeployEcommerce\BuilderIO\Api\ProductCollectionInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as CatalogProductCollectionFactory;
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
use Magento\Framework\App\ObjectManager;
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
    const CACHE_TAG = 'buildeio_product_collection';

    protected $_eventPrefix = 'builderio_product_collection';

    protected $_eventObject = 'rule';


    protected int $productCount;

    public function __construct(
        Context                                                        $context,
        Registry                                                       $registry,
        FormFactory                                                    $formFactory,
        TimezoneInterface                                              $localeDate,
        private \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        private CategoryRepositoryInterface                                    $categoryRepository,
        StoreManagerInterface                                          $storeManager,
        CombineFactory                                                 $combineFactory,
        RuleCollectionFactory                                          $actionCollectionFactory,
        ProductFactory                                                 $productFactory,
        Iterator                                                       $resourceIterator,
        Session                                                        $customerSession,
        Data                                                           $catalogRuleData,
        TypeListInterface                                              $cacheTypesList,
        DateTime                                                       $dateTime,
        RuleProductProcessor                                           $ruleProductProcessor,
        private ProductRepositoryInterface                             $productRepository,
        private ConditionsToCollectionApplier                          $conditionsToCollectionApplier,
        AbstractResource                                               $resource = null,
        AbstractDb                                                     $resourceCollection = null,
        array                                                          $relatedCacheTypes = [],
        array                                                          $data = [],
        ExtensionAttributesFactory                                     $extensionFactory = null,
        AttributeValueFactory                                          $customAttributeFactory = null,
        Json                                                           $serializer = null,
        RuleResourceModel                                              $ruleResourceModel = null,
    )
    {
        parent::__construct($context, $registry, $formFactory, $localeDate, $productCollectionFactory, $storeManager, $combineFactory, $actionCollectionFactory, $productFactory, $resourceIterator, $customerSession, $catalogRuleData, $cacheTypesList, $dateTime, $ruleProductProcessor, $resource, $resourceCollection, $relatedCacheTypes, $data, $extensionFactory, $customAttributeFactory, $serializer, $ruleResourceModel, $conditionsToCollectionApplier);
    }


    public function afterLoad()
    {
        parent::afterLoad();
        $config = $this->getConfig();
        if(is_string($config)) {
            $this->setConfig(json_decode($config, true));
        }
        return $this;
    }

    public function setData($key, $value = null)
    {
        return parent::setData($key, $value); // TODO: Change the autogenerated stub
    }


    public function beforeSave()
    {
        if(is_array($this->getConfig())) {
            $this->setConfig(json_encode($this->getConfig()));
        }
        return parent::beforeSave();
    }


    public function getConditionsInstance()
    {
        return $this->_combineFactory->create();
    }

    public function getActionsInstance()
    {
        return $this->_actionCollectionFactory->create();
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @param mixed $id
     * @return ProductCollection|\Magento\Framework\Model\AbstractModel
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @param string $type
     * @return ProductCollectionInterface
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->getData(self::CONFIG);
    }

    /**
     * @param string $config
     * @return ProductCollectionInterface
     */
    public function setConfig($config)
    {
        return $this->setData(self::CONFIG, $config);
    }

    /**
     * @return mixed
     */
    public function getProductCount()
    {
        return $this->getData(self::PRODUCT_COUNT);
    }

    /**
     * @param int $productCount
     * @return ProductCollectionInterface
     */
    public function setProductCount($productCount)
    {
        return $this->setData(self::PRODUCT_COUNT, $productCount);
    }

    /**
     * @return mixed
     */
    public function getUrlKey()
    {
        return $this->getData(self::URL_KEY);
    }

    /**
     * @param string $urlKey
     * @return ProductCollectionInterface
     */
    public function setUrlKey($urlKey)
    {
        return $this->setData(self::URL_KEY, $urlKey);
    }

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

    public function getCount()
    {
        return $this->productCount;
    }

    private function getConditionProducts():array {

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

    private function getSkuProducts(): array {
        $sku_list = explode("," ,$this->getData("config/sku_list"));

        $matchingProducts = [];

        foreach ($sku_list as $sku) {
            $matchingProducts[] = $this->productRepository->get($sku);
        }


        return $matchingProducts;
    }

    private function getCategoryProducts(): array {
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

        if($this->getData("config/category_limit")){
            $products = array_slice(array_values($products),0, (int) $this->getData("config/category_limit"));
        }

        return $products;
    }
}

<?php
/**
 * @Copyright: 2025 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Api;

/**
 * Interface ProductCollectionInterface
 *
 * @api
 */
interface ProductCollectionInterface
{

    public const ID = "id";
    public const TYPE = "type";
    public const TYPE_CONDITION = "condition";
    public const TYPE_SKU = "sku";
    public const TYPE_CATEGORY = "category";
    public const PRODUCT_COUNT = "product_count";
    public const URL_KEY = "url_key";
    public const CONFIG = "config";

    /**
     * Get the conditions instance for this product collection.
     *
     * @return mixed
     */
    public function getConditionsInstance();

    /**
     * Get the actions instance for this product collection.
     *
     * @return mixed
     */
    public function getActionsInstance();

    /**
     * @return mixed
     */
    public function getId();


    /**
     * @param mixed $id
     * @return ProductCollection|AbstractModel
     */
    public function setId($id);


    /**
     * @return mixed
     */
    public function getType();


    /**
     * @param string $type
     * @return ProductCollectionInterface
     */
    public function setType($type);


    /**
     * @return mixed
     */
    public function getConfig();


    /**
     * @param string $config
     * @return ProductCollectionInterface
     */
    public function setConfig($config);


    /**
     * @return mixed
     */
    public function getProductCount();


    /**
     * @param int $productCount
     * @return ProductCollectionInterface
     */
    public function setProductCount($productCount);


    /**
     * @return mixed
     */
    public function getUrlKey();


    /**
     * @param string $urlKey
     * @return ProductCollectionInterface
     */
    public function setUrlKey($urlKey);


    public function getProducts();


    public function getCount();
}

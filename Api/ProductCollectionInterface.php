<?php
declare(strict_types=1);

/**
 * @Copyright: 2025 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

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
     * Get ID.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Set ID.
     *
     * @param mixed $id
     * @return ProductCollection|AbstractModel
     */
    public function setId($id);

    /**
     * Get type.
     *
     * @return mixed
     */
    public function getType();

    /**
     * Set type.
     *
     * @param string $type
     * @return ProductCollectionInterface
     */
    public function setType($type);

    /**
     * Get config.
     *
     * @return mixed
     */
    public function getConfig();

    /**
     * Set config.
     *
     * @param string $config
     * @return ProductCollectionInterface
     */
    public function setConfig($config);

    /**
     * Get product count.
     *
     * @return mixed
     */
    public function getProductCount();

    /**
     * Set product count.
     *
     * @param int $productCount
     * @return ProductCollectionInterface
     */
    public function setProductCount($productCount);

    /**
     * Get URL key.
     *
     * @return mixed
     */
    public function getUrlKey();

    /**
     * Set URL key.
     *
     * @param string $urlKey
     * @return ProductCollectionInterface
     */
    public function setUrlKey($urlKey);

    /**
     * Get products.
     *
     * @return mixed
     */
    public function getProducts();

    /**
     * Get count.
     *
     * @return mixed
     */
    public function getCount();
}

<?php
/**
 * @Author:    Brandon Bishop
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
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

    const ID = "id";
    const TYPE = "type";

    const TYPE_CONDITION = "condition";
    const TYPE_SKU = "sku";
    const TYPE_CATEGORY = "category";

    const PRODUCT_COUNT = "product_count";
    const URL_KEY = "url_key";
    const CONFIG = "config";

    public function getConditionsInstance();

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

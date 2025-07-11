<?php
/**
 * @Author:    Brandon van Rensburg
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface ProductCollectionSearchResultsInterface
 *
 * @api
 */
interface ProductCollectionSearchResultsInterface
{
    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getProducts();

    /**
     * @return int
     */
    public function getCount();
}

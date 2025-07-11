<?php
/**
 * @Author:    Brandon van Rensburg
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Model;

use Magento\Framework\Api\SearchResults;
use DeployEcommerce\BuilderIO\Api\Data\ProductCollectionSearchResultsInterface;

class ProductCollectionSearchResults extends SearchResults implements ProductCollectionSearchResultsInterface
{
}

<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model;

use DeployEcommerce\BuilderIO\Api\Data\WebhookSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Class WebhookSearchResultsModel
 *
 * This class provides search results for webhook entities.
 *
 */
class WebhookSearchResultsModel extends SearchResults implements WebhookSearchResultsInterface
{
}

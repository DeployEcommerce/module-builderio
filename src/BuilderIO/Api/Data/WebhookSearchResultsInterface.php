<?php
declare(strict_types=1);
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface WebhookSearchResultsInterface
 *
 * This interface defines the methods for the WebhookSearchResultsInterface class.
 * The WebhookSearchResultsInterface class is responsible for returning search results for webhook entities.
 *
 */
interface WebhookSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pages list.
     *
     * @return WebhookInterface[]
     */
    public function getItems();

    /**
     * Set pages list.
     *
     * @param WebhookInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

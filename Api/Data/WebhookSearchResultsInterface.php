<?php
declare(strict_types=1);

/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;
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
     * Get items list.
     *
     * @return ExtensibleDataInterface[]
     */
    public function getItems();

    /**
     * Set items list.
     *
     * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

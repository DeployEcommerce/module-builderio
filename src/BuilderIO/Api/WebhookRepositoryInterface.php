<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Api;

use DeployEcommerce\BuilderIO\Api\Data\WebhookSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use DeployEcommerce\BuilderIO\Api\Data\WebhookInterface;

/**
 * Interface WebhookRepositoryInterface
 *
 * This interface defines the methods for the WebhookRepositoryInterface class.
 * The WebhookRepositoryInterface class is responsible for saving and retrieving webhook data.
 *
 */
interface WebhookRepositoryInterface
{
    /**
     * Save webhook.
     *
     * @param WebhookInterface $webhook
     * @return WebhookInterface
     * @throws LocalizedException
     */
    public function save(WebhookInterface $webhook);

    /**
     * Retrieve webhook.
     *
     * @param int $webhookId
     * @return WebhookInterface
     * @throws LocalizedException
     */
    public function getById(int $webhookId);

    /**
     * Delete webhook.
     *
     * @param WebhookInterface $webhook
     * @return void
     * @throws LocalizedException
     */
    public function delete(WebhookInterface $webhook);

    /**
     * Retrieve webhook matching the specified criteria.
     *
     * @param SearchCriteriaInterface $criteria
     * @return WebhookSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $criteria);
}

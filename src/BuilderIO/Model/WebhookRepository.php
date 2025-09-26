<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model;

use DeployEcommerce\BuilderIO\Api\Data\WebhookInterface;
use DeployEcommerce\BuilderIO\Api\Data\WebhookSearchResultsInterface;
use DeployEcommerce\BuilderIO\Api\Data\WebhookSearchResultsInterfaceFactory;
use DeployEcommerce\BuilderIO\Api\WebhookRepositoryInterface;
use DeployEcommerce\BuilderIO\Model\ResourceModel\WebhookModel\WebhookCollection;
use DeployEcommerce\BuilderIO\Model\ResourceModel\WebhookModel\WebhookCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Throwable;

/**
 * Class WebhookRepository
 *
 * This class provides methods to save and retrieve webhook entities.
 * It implements the WebhookRepositoryInterface.
 *
 */
class WebhookRepository implements WebhookRepositoryInterface
{

    /**
     * WebhookRepository constructor.
     *
     * @param ResourceModel\WebhookResource $resource
     * @param WebhookModelFactory $webhookFactory
     * @param WebhookSearchResultsInterfaceFactory $searchResultsFactory
     * @param WebhookCollectionFactory $webhookCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        private ResourceModel\WebhookResource $resource,
        private WebhookModelFactory $webhookFactory,
        private WebhookSearchResultsInterfaceFactory $searchResultsFactory,
        private WebhookCollectionFactory $webhookCollectionFactory,
        private CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * Save webhook.
     *
     * @param WebhookInterface $webhook
     * @return WebhookInterface
     * @throws CouldNotSaveException
     */
    public function save(WebhookInterface $webhook): WebhookInterface
    {
        try {
            $this->resource->save($webhook);
        } catch (LocalizedException $exception) {
            throw new CouldNotSaveException(
                __('Could not save the webhook: %1', $exception->getMessage()),
                $exception
            );
        } catch (Throwable $exception) {
            throw new CouldNotSaveException(
                __('Could not save the webhook: %1', __('Something went wrong while saving the webhook.')),
                $exception
            );
        }
        return $webhook;
    }

    /**
     * Retrieve webhook.
     *
     * @param null|int|string $webhookId
     * @return WebhookInterface
     * @throws NoSuchEntityException
     */
    public function getById($webhookId): WebhookInterface
    {
        $webhook = $this->webhookFactory->create();
        $webhook->load($webhookId);
        if (!$webhook->getId()) {
            throw new NoSuchEntityException(__('The Webhook with the "%1" ID doesn\'t exist.', $webhookId));
        }

        return $webhook;
    }

    /**
     * Delete webhook.
     *
     * @param WebhookInterface $webhook
     * @return void
     * @throws CouldNotSaveException
     */
    public function delete(WebhookInterface $webhook): void
    {
        try {
            $this->resource->delete($webhook);
        } catch (LocalizedException $exception) {
            throw new CouldNotSaveException(
                __('Could not delete the webhook: %1', $exception->getMessage()),
                $exception
            );
        } catch (Throwable $exception) {
            throw new CouldNotSaveException(
                __('Could not delete the webhook: %1', __('Something went wrong while deleting the webhook.')),
                $exception
            );
        }
    }

    /**
     * Retrieve webhook matching the specified criteria.
     *
     * @param SearchCriteriaInterface $criteria
     * @return WebhookSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var WebhookCollection $collection */
        $collection = $this->webhookCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}

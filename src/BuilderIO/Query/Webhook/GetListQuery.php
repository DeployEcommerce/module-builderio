<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Query\Webhook;

use DeployEcommerce\BuilderIO\Mapper\WebhookDataMapper;
use DeployEcommerce\BuilderIO\Model\ResourceModel\WebhookModel\WebhookCollection;
use DeployEcommerce\BuilderIO\Model\ResourceModel\WebhookModel\WebhookCollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;

/**
 * Class GetListQuery
 *
 * This class provides a method to get a list of Webhook entities based on search criteria.
 * It uses the WebhookCollectionFactory to create a collection of Webhook entities and the
 * WebhookDataMapper to map the collection to data transfer objects (DTOs).
 *
 */
class GetListQuery
{
    /**
     * @param CollectionProcessorInterface $collectionProcessor
     * @param WebhookCollectionFactory $entityCollectionFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param WebhookDataMapper $entityDataMapper
     * @param SearchResultsInterfaceFactory $searchResultFactory
     */
    public function __construct(
        private CollectionProcessorInterface  $collectionProcessor,
        private WebhookCollectionFactory      $entityCollectionFactory,
        private SearchCriteriaBuilder         $searchCriteriaBuilder,
        private WebhookDataMapper             $entityDataMapper,
        private SearchResultsInterfaceFactory $searchResultFactory
    ) {
    }

    /**
     * Get Webhook list by search criteria.
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return SearchResultsInterface
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface
    {
        /** @var WebhookCollection $collection */
        $collection = $this->entityCollectionFactory->create();

        if ($searchCriteria === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $entityDataObjects = $this->entityDataMapper->map($collection);

        /** @var SearchResultsInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setItems($entityDataObjects);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}

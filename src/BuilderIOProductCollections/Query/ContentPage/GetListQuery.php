<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Query\ContentPage;

use DeployEcommerce\BuilderIO\Mapper\ContentPageDataMapper;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ContentPageModel\ContentPageCollection;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ContentPageModel\ContentPageCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;

class GetListQuery
{
    /**
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ContentPageCollectionFactory $entityCollectionFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ContentPageDataMapper $entityDataMapper
     * @param SearchResultsInterfaceFactory $searchResultFactory
     */
    public function __construct(
        private CollectionProcessorInterface  $collectionProcessor,
        private ContentPageCollectionFactory  $entityCollectionFactory,
        private SearchCriteriaBuilder         $searchCriteriaBuilder,
        private ContentPageDataMapper         $entityDataMapper,
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
        /** @var ContentPageCollection $collection */
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

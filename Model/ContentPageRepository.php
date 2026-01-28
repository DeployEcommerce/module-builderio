<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model;

use DeployEcommerce\BuilderIO\Api\Data\ContentPageInterface;
use DeployEcommerce\BuilderIO\Api\Data\ContentPageInterfaceFactory;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ContentPageModel\ContentPageCollectionFactory;
use DeployEcommerce\BuilderIO\Api\ContentPageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class ContentPageRepository implements ContentPageRepositoryInterface
{

    /**
     * ContentPageRepository constructor.
     *
     * @param ResourceModel\ContentPageResource $resource
     * @param ContentPageInterfaceFactory $contentPageFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param ContentPageCollectionFactory $contentPageCollectionFactory
     */
    public function __construct(
        private ResourceModel\ContentPageResource $resource,
        private ContentPageInterfaceFactory $contentPageFactory,
        private SearchResultsInterfaceFactory $searchResultsFactory,
        private ContentPageCollectionFactory $contentPageCollectionFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(ContentPageInterface $contentPage): ContentPageInterface
    {
        try {
            $this->resource->save($contentPage);
        } catch (LocalizedException $exception) {
            throw new CouldNotSaveException(
                __('Could not save the webhook: %1', $exception->getMessage()),
                $exception
            );
        } catch (\Throwable $exception) {
            throw new CouldNotSaveException(
                __('Could not save the content page: %1', $exception->getMessage()),
                $exception
            );
        }

        return $contentPage;
    }

    /**
     * @inheritDoc
     */
    public function getById($contentPageId, $field = ContentPageInterface::ID): ContentPageInterface
    {
        $contentPage = $this->contentPageFactory->create();
        $contentPage->load($contentPageId, $field);
        if (!$contentPage->getId()) {
            throw new NoSuchEntityException(__('The ContentPage with the "%1" ID doesn\'t exist.', $contentPageId));
        }

        return $contentPage;
    }

    /**
     * @inheritDoc
     */
    public function findByBuilderioPageId($builderioPageId): ContentPageInterface
    {
        return $this->getById($builderioPageId, ContentPageInterface::BUILDERIO_PAGE_ID);
    }

    /**
     * @inheritDoc
     */
    public function delete(ContentPageInterface $contentPage): void
    {
        try {
            $this->resource->delete($contentPage);
        } catch (LocalizedException $exception) {
            throw new CouldNotSaveException(
                __('Could not delete the webhook: %1', $exception->getMessage()),
                $exception
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var WebhookCollection $collection */
        $collection = $this->contentPageCollectionFactory->create();

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}

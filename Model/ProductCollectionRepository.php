<?php
/**
 * @Author:    Brandon Bishop
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Model;

use DeployEcommerce\BuilderIO\Api\Data\ProductCollectionSearchResultsInterface;
use DeployEcommerce\BuilderIO\Api\Data\ProductCollectionSearchResultsInterfaceFactory;
use DeployEcommerce\BuilderIO\Api\ProductCollectionInterface;
use DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection as ProductCollectionResourceModel;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection\CollectionFactory as ProductCollectionCollectionFactory;
use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class ProductCollectionRepository extends AbstractDb implements ProductCollectionRepositoryInterface
{
    public function __construct(
        private ProductCollectionResourceModel $resource,
        private ProductCollectionFactory $productCollectionFactory,
        private ProductCollectionCollectionFactory $collectionFactory,
        private CollectionProcessorInterface $collectionProcessor,
        Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * @inheritDoc
     */
    public function save($productCollection)
    {
        try {
            $this->resource->save($productCollection);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $productCollection;
    }

    /**
     * @inheritDoc
     */
    public function getById($id): ProductCollectionInterface
    {
        $productCollection = $this->productCollectionFactory->create();
        $this->resource->load($productCollection, $id);
        if (!$productCollection->getId()) {
            throw new NoSuchEntityException(__('Product Collection with id ' . $id . ' does not exist.'));
        }
        return $productCollection;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function delete($productCollection)
    {
        try {
            $this->resource->delete($productCollection);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    protected function _construct()
    {
    }
}

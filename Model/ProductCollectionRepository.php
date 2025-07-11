<?php
/**
 * @Author:    Brandon van Rensburg
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
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductCollectionRepository extends AbstractDb implements ProductCollectionRepositoryInterface
{
    /**
     * @var \DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection
     */
    private $resource;

    /**
     * @var \DeployEcommerce\BuilderIO\Model\ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var \DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \DeployEcommerce\BuilderIO\Api\Data\ProductCollectionSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param \DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection $resource
     * @param \DeployEcommerce\BuilderIO\Model\ProductCollectionFactory $productCollectionFactory
     * @param \DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ProductCollectionResourceModel $resource,
        ProductCollectionFactory $productCollectionFactory,
        ProductCollectionCollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save($productCollection)
    {
        try {
            $this->resource->save($productCollection);
        } catch (\Exception $exception) {
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

    /**
     * @inheritDoc
     */
    public function delete($productCollection)
    {
        try {
            $this->resource->delete($productCollection);
        } catch (\Exception $exception) {
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

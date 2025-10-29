<?php
declare(strict_types=1);

/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIOProductCollections\Controller\Adminhtml\Products;

use DeployEcommerce\BuilderIOProductCollections\Api\ProductCollectionInterface;
use DeployEcommerce\BuilderIOProductCollections\Api\ProductCollectionInterfaceFactory;
use DeployEcommerce\BuilderIOProductCollections\Api\ProductCollectionRepositoryInterface;
use DeployEcommerce\BuilderIOProductCollections\Model\Indexer\ProductCollection\ProductIndexer;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Indexer\IndexerRegistry;
use Throwable;

class Save extends Action
{
    /**
     * Constructor
     *
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ProductCollectionRepositoryInterface $productCollectionRepository
     * @param ProductRepositoryInterface $productRepository
     * @param ProductCollectionInterfaceFactory $productCollectionInterfaceFactory
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Context $context,
        private DataPersistorInterface $dataPersistor,
        private ProductCollectionRepositoryInterface $productCollectionRepository,
        private ProductRepositoryInterface $productRepository,
        private ProductCollectionInterfaceFactory $productCollectionInterfaceFactory,
        private UrlInterface $urlBuilder,
        private ProductIndexer $productIndexer,
        private IndexerRegistry $indexerRegistry,
    ) {
        parent::__construct($context);
    }

    /**
     * Execute save action
     *
     * @return Redirect
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = (int) $this->getRequest()->getParam('id');

            try {
                $model = $this->productCollectionRepository->getById($id);
            } catch (LocalizedException $e) {
                $model = $this->productCollectionInterfaceFactory->create();
            }

            if ($model->getType() == ProductCollectionInterface::TYPE_SKU) {
                //validate skus in sku list
                try {
                    foreach (explode(",", $data["config"]["sku_list"]) as $sku) {
                        $this->productRepository->get($sku);
                    }
                } catch (Throwable $e) {
                    $this->messageManager->addError("There was an invalid sku within the sku list");
                    return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                }
            } else {
                $model->setData("config/sku_list", null);
            }

            unset($data['condtions']);

            $model = $model->setData($data);

            $this->productCollectionRepository->save($model);

            //calling get products unset conditions serialized. So we copy the model to preserve the data
            $newModel = $this->productCollectionRepository->getById($model->getId());

            $model->setProductCount(count($newModel->getProducts()));

            try {
                $this->productCollectionRepository->save($model);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__('Error saving product collection: %1', $e->getMessage()));
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            } catch (Throwable $e) {
                $this->messageManager->addErrorMessage(__('An unexpected error occurred while saving the product collection.'));
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }


            $index = $this->indexerRegistry->get("builderio_product_collection");

            if(!$index->isScheduled()){
                $this->productIndexer->reindexRow((int) $model->getId());
            } else {
                $index->invalidate();
            }

            $this->messageManager->addSuccessMessage(__('You saved the product collection.'));
            $this->dataPersistor->clear('builderio_product_collection');

            return $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect->setPath('*/*/');
    }
}

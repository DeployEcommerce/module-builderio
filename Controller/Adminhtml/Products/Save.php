<?php
declare(strict_types=1);

/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Controller\Adminhtml\Products;

use DeployEcommerce\BuilderIO\Api\ProductCollectionInterface;
use DeployEcommerce\BuilderIO\Api\ProductCollectionInterfaceFactory;
use DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
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
        private UrlInterface $urlBuilder
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

            try {
                $this->productCollectionRepository->save($model);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__('Error saving product collection: %1', $e->getMessage()));
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            } catch (Throwable $e) {
                $this->messageManager->addErrorMessage(__('An unexpected error occurred while saving the product collection.'));
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }

            $this->messageManager->addSuccessMessage(__('You saved the product collection.'));
            $this->dataPersistor->clear('builderio_product_collection');

            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
            }

            $this->dataPersistor->set('builderio_product_collection', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

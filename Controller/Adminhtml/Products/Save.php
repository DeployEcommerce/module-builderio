<?php
/**
 * @Author:    Brandon van Rensburg
 * @Copyright: 2025 DeployEcommerce (https://www.techarlie.co.za/)
 * @Package:   DeployEcommerce_BuilderIO
 */
namespace DeployEcommerce\BuilderIO\Controller\Adminhtml\Products;

use Codeception\Exception\ThrowableWrapper;
use DeployEcommerce\BuilderIO\Api\ProductCollectionInterface;
use DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;

class Save extends Action
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var ProductCollectionRepositoryInterface
     */
    private $productCollectionRepository;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ProductCollectionRepositoryInterface $productCollectionRepository
     */
    public function __construct(
        Context                              $context,
        DataPersistorInterface               $dataPersistor,
        ProductCollectionRepositoryInterface $productCollectionRepository,
        private ProductRepositoryInterface   $productRepository,
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->productCollectionRepository = $productCollectionRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('id');

            if (empty($data['id'])) {
                $data['id'] = null;
            }

            try {
                $model = $this->productCollectionRepository->getById($id);
            } catch (LocalizedException $e) {
                $model = $this->productCollectionRepository->create();
            }

            if($model->getType() == ProductCollectionInterface::TYPE_SKU){
                //validate skus in sku list
                try {
                    foreach(explode(",",$data["config"]["sku_list"]) as $sku) {
                       $this->productRepository->get($sku);
                    }
                } catch (\Throwable $e) {
                    $this->messageManager->addError("There was an invalid sku within the sku list");
                    return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                }
            } else {
                $model->setData("config/sku_list", null);
            }

            unset($data['condtions']);

                $model = $model->setData($data);

                $this->productCollectionRepository->save($model);

                //calling get products unset conditions serialised. So if we then resave the conditions will not save
                $newModel = $this->productCollectionRepository->getById($model->getId());

                $model->setProductCount(count($newModel->getProducts()));

                $this->productCollectionRepository->save($model);

                $this->messageManager->addSuccessMessage(__('You saved the product collection.'));
                $this->dataPersistor->clear('builderio_product_collection');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');

            $this->dataPersistor->set('builderio_product_collection', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

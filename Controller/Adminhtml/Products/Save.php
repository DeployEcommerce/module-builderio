<?php
/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */
namespace DeployEcommerce\BuilderIO\Controller\Adminhtml\Products;

use DeployEcommerce\BuilderIO\Api\ProductCollectionInterface;
use DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Throwable;

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
        private UrlInterface                 $urlBuilder
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

            $model->setUrlKey($this->urlBuilder->getBaseUrl(). "rest/all/V1/builderio/collections/". $model->getId());
            $this->productCollectionRepository->save($model);

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

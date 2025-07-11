<?php
/**
 * @Author:    Brandon van Rensburg
 * @Copyright: 2025 DeployEcommerce (https://www.techarlie.co.za/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Controller\Adminhtml\Products;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface;

class Delete extends Action
{
    /**
     * @var \DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface
     */
    protected $productCollectionRepository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface $productCollectionRepository
     */
    public function __construct(
        Context $context,
        ProductCollectionRepositoryInterface $productCollectionRepository
    ) {
        $this->productCollectionRepository = $productCollectionRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $this->productCollectionRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('The product collection has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a product collection to delete.'));
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->authorization->isAllowed('DeployEcommerce_BuilderIO::products');
    }
}

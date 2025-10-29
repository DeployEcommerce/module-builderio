<?php
declare(strict_types=1);

/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIOProductCollections\Controller\Adminhtml\Products;

use DeployEcommerce\BuilderIOProductCollections\Api\ProductCollectionRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    /**
     * @var \DeployEcommerce\BuilderIOProductCollections\Api\ProductCollectionRepositoryInterface
     */
    protected $productCollectionRepository;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \DeployEcommerce\BuilderIOProductCollections\Api\ProductCollectionRepositoryInterface $productCollectionRepository
     */
    public function __construct(
        Context $context,
        ProductCollectionRepositoryInterface $productCollectionRepository
    ) {
        $this->productCollectionRepository = $productCollectionRepository;
        parent::__construct($context);
    }

    /**
     * Execute delete action
     *
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
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a product collection to delete.'));
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Check if user is allowed to perform this action
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->authorization->isAllowed('DeployEcommerce_BuilderIO::products');
    }
}

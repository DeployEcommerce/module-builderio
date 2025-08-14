<?php
/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */
namespace DeployEcommerce\BuilderIO\Controller\Adminhtml\Products;

use DeployEcommerce\BuilderIO\Api\ProductCollectionInterface;
use DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface;
use DeployEcommerce\BuilderIO\Api\ProductCollectionInterfaceFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        protected ProductCollectionRepositoryInterface $productCollectionRepository,
        protected ProductCollectionInterfaceFactory $productCollectionInterfaceFactory,
        protected Registry $registry,
        protected ForwardFactory $forwardFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return Page
     */
    public function execute(): Page
    {
        $id = (int) $this->getRequest()->getParam('id');

        if ($id) {
            $model = $this->productCollectionRepository->getById($id);
            if (!$model) {
                $this->messageManager->addErrorMessage(__('This product collection no longer exists.'));

                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $model = $this->productCollectionInterfaceFactory->create();
        }

        $this->registry->register('builderio_productcollection', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Product Collection') : __('New Product Collection'),
            $id ? __('Edit Product Collection') : __('New Product Collection')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Product Collection'));
        return $resultPage;
    }
}

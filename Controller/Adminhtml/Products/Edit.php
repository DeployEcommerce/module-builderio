<?php
/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */
namespace DeployEcommerce\BuilderIO\Controller\Adminhtml\Products;

use DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface;
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
        $id = $this->getRequest()->getParam("id");

        $model = $this->productCollectionRepository->getById($id);

        if(!$model){
            $this->forwardFactory->create([
                "action" => "builderio/products/index",
            ]);
        }

        $this->registry->register('builderio_productcollection', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Product Collection'));
        return $resultPage;
    }
}

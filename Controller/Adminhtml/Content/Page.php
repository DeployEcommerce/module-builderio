<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 */

namespace DeployEcommerce\BuilderIO\Controller\Adminhtml\Content;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

class Page extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'DeployEcommerce_BuilderIO::listing_page';

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('DeployEcommerce_BuilderIO::listing_page');
        $resultPage->addBreadcrumb(__('Content Page'), __('Content Page'));
        $resultPage->addBreadcrumb(__('Manage Content Pages'), __('Manage Content Pages'));
        $resultPage->getConfig()->getTitle()->prepend(__('Content Page List'));

        return $resultPage;
    }
}

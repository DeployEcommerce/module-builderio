<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Controller\Adminhtml\Webhook;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Index
 *
 * This class is a controller for the Webhook index page.
 *
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    public const ADMIN_RESOURCE = 'DeployEcommerce_BuilderIO::management';

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('DeployEcommerce_BuilderIO::management');
        $resultPage->addBreadcrumb(__('Webhook'), __('Webhook'));
        $resultPage->addBreadcrumb(__('Manage Webhooks'), __('Manage Webhooks'));
        $resultPage->getConfig()->getTitle()->prepend(__('Webhook List'));

        return $resultPage;
    }
}

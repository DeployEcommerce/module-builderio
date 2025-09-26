<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Controller\Adminhtml\Config;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use DeployEcommerce\BuilderIO\Service\BuilderIO\AdminApi;

/**
 * Class Ajax
 *
 * This class is a controller for the Builder.io configuration AJAX requests.
 *
 */
class ConnectionAjax extends Action
{

    /**
     * ConnectionAjax constructor.
     *
     * @param Context $context
     * @param AdminApi $adminApi
     */
    public function __construct(
        Context $context,
        private AdminApi $adminApi
    ) {
        parent::__construct($context);
    }

    public const ADMIN_RESOURCE = 'DeployEcommerce_BuilderIO::listing';

    /**
     * Execute the AJAX request.
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws GuzzleException
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $store = (int) $this->getRequest()->getParam('store');

        if (!$store) {
            $resultPage->setStatusHeader(400);
            return $resultPage;
        }

        $response = $this->adminApi->postContent(
            "{\n\t\"query\": \"query{settings id}\",\n\t\"variables\": {}\n}",
            $this->getRequest()->getParam('store')
        );

        if ($response === null) {
            $resultPage->setStatusHeader(500);
            return $resultPage;
        }

        if ($response->getStatusCode() !== 200) {
            $resultPage->setStatusHeader(500);
            return $resultPage;
        }

        $resultPage->setJsonData($response->getBody());

        return $resultPage;
    }
}

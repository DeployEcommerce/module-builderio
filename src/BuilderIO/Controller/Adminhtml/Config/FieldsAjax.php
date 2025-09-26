<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Controller\Adminhtml\Config;

use DeployEcommerce\BuilderIO\System\Config;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use DeployEcommerce\BuilderIO\Service\BuilderIO\AdminApi;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Ajax
 *
 * This class is a controller for the Builder.io configuration AJAX requests.
 *
 */
class FieldsAjax extends Action
{

    /**
     * FieldsAjax constructor.
     *
     * @param Context $context
     * @param AdminApi $adminApi
     * @param Json $json
     * @param Config $config
     */
    public function __construct(
        Context $context,
        private AdminApi $adminApi,
        private Json $json,
        private Config $config
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
            "{\n\t\"query\": \"query{settings id models{id kind}}\",\n\t\"variables\": {}\n}",
            $store
        );

        if ($response === null) {
            $resultPage->setStatusHeader(500);
            return $resultPage;
        }

        if ($response->getStatusCode() !== 200) {
            $resultPage->setStatusHeader(500);
            return $resultPage;
        }

        $data = $this->json->unserialize($response->getBody()->getContents());

        if (!isset($data['data']['models'])) {
            $resultPage->setStatusHeader(500);
            return $resultPage;
        }

        $counter = 0;
        foreach ($data['data']['models'] as $model) {
            if ($model['kind'] !== 'page') {
                continue;
            }
            $updateResponse = $this->adminApi->postContent(
                sprintf(
                    "{\n\t\"query\": \"mutation{updateModel(body:{id:\\\"%s\\\" data:{fields:[{name:\\\"Title\\\" helperText:\\\"SEO page title\\\" type:\\\"text\\\" autoFocus:false required:false},{name:\\\"Description\\\" helperText:\\\"SEO page Description\\\" type:\\\"Long text\\\" autoFocus:false required:false},{name:\\\"Keywords\\\" helperText:\\\"SEO page keywords\\\" type:\\\"text\\\" autoFocus:false required:false}]}}){id}}\",\n\t\"variables\": {}\n}",
                    $model['id']
                ),
                $store
            );

            if ($updateResponse->getStatusCode() === 200) {
                $counter++;
            }
        }

        $resultPage->setJsonData("{\"status\": \"success\", \"count\": $counter}");

        return $resultPage;
    }
}

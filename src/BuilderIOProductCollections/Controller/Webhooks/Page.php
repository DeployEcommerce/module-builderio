<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIOProductCollections\Controller\Webhooks;

use DeployEcommerce\BuilderIO\Api\Data\WebhookInterface;
use DeployEcommerce\BuilderIO\Api\Data\WebhookInterfaceFactory;
use DeployEcommerce\BuilderIO\Api\WebhookRepositoryInterface;
use DeployEcommerce\BuilderIO\Model\Queue\Handler\Handler;
use DeployEcommerce\BuilderIO\System\Config;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Psr\Log\LoggerInterface;

/**
 * Class Page
 *
 * This class handles the page webhook requests for the Builder.io integration.
 * It implements the CsrfAwareActionInterface to manage CSRF validation.
 *
 */
class Page implements CsrfAwareActionInterface
{

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var ResponseInterface
     */
    private ResponseInterface $response;

    /**
     * Page constructor.
     *
     * @param Context $context
     * @param WebhookRepositoryInterface $webhookRepository
     * @param WebhookInterfaceFactory $webhookFactory
     * @param DateTime $dateTime
     * @param Json $json
     * @param LoggerInterface $logger
     * @param PublisherInterface $publisher
     * @param Handler $handler
     * @param Config $config
     */
    public function __construct(
        private Context $context,
        private WebhookRepositoryInterface $webhookRepository,
        private WebhookInterfaceFactory $webhookFactory,
        private DateTime $dateTime,
        private Json $json,
        private LoggerInterface $logger,
        private PublisherInterface $publisher,
        private Handler $handler,
        private Config $config
    ) {
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
    }

    /**
     * Execute the page webhook request.
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function execute()
    {
        if (!$this->request->isPost()) {
            return $this->response;
        }

        if ($this->config->getWebhookSecretKey() !== $this->request->getHeader('x-builderio-secret-key')) {
            $this->response->setHttpResponseCode(401);
            $this->response->setBody('Missing Header x-builderio-secret-key');
            return $this->response;
        }

        $data = $this->request->getContent();

        if (!empty($data)) {
            $data = $this->json->unserialize($data);

            if ($data['newValue'] == null) {
                $data['newValue'] = $data['previousValue'];
            }

            $webhook = $this->webhookFactory->create();
            $webhook->setData([
                WebhookInterface::OPERATION => $data['operation'],
                WebhookInterface::MODEL_NAME => $data['modelName'],
                WebhookInterface::OWNER_ID => $data['newValue']['ownerId'],
                WebhookInterface::CREATED_DATE => $this->dateTime->date(
                    'Y-m-d H:i:s',
                    (floor($data['newValue']['createdDate']/1000))
                ),
                WebhookInterface::BUILDERIO_ID => $data['newValue']['id'],
                WebhookInterface::VERSION => $data['newValue']['@version'],
                WebhookInterface::NAME => $data['newValue']['name'],
                WebhookInterface::MODEL_ID => $data['newValue']['modelId'],
                WebhookInterface::PUBLISHED => $data['newValue']['published'],
                WebhookInterface::META => $this->json->serialize($data['newValue']['meta']),
                WebhookInterface::PRIORTIY => $data['newValue']['priority'],
                WebhookInterface::QUERY => $this->json->serialize($data['newValue']['query']),
                WebhookInterface::WEBHOOK_DATA => $this->json->serialize($data['newValue']['data']),
                WebhookInterface::METRICS => $this->json->serialize($data['newValue']['metrics']),
                WebhookInterface::VARIATIONS => $this->json->serialize($data['newValue']['variations']),
                WebhookInterface::LAST_UPDATED => $this->dateTime->date(
                    'Y-m-d H:i:s',
                    (floor($data['newValue']['lastUpdated']/1000))
                ),
                WebhookInterface::FIRST_PUBLISHED => $this->dateTime->date(
                    'Y-m-d H:i:s',
                    (floor($data['newValue']['firstPublished']/1000))
                ),
                WebhookInterface::PREVIEW_URL => $data['newValue']['previewUrl'],
                WebhookInterface::TEST_RATIO => $data['newValue']['testRatio'],
                WebhookInterface::SCREENSHOT => $data['newValue']['screenshot'],
                WebhookInterface::CREATED_BY => $data['newValue']['createdBy'],
                WebhookInterface::LAST_UPDATED_BY => $data['newValue']['lastUpdatedBy'],
                WebhookInterface::FOLDERS => $this->json->serialize($data['newValue']['folders'])
            ]);

            try {
                $webhook = $this->webhookRepository->save($webhook);

                if ($this->config->isAsyncProcessingEnabled()) {
                    $this->publisher->publish('builderio.content', $webhook->getWebhookId());
                } else {
                    $this->handler->execute($webhook->getWebhookId());
                }

            } catch (Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $this->response;
    }

    /**
     * Create a CSRF validation exception.
     *
     * @param RequestInterface $request
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }

    /**
     * Validate the CSRF token.
     *
     * @param RequestInterface $request
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}

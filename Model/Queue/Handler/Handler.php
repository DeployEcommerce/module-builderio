<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model\Queue\Handler;

use DeployEcommerce\BuilderIO\Api\ContentPageRepositoryInterface;
use DeployEcommerce\BuilderIO\Api\Data\ContentPageInterfaceFactory;
use DeployEcommerce\BuilderIO\Api\Data\WebhookInterfaceFactory;
use DeployEcommerce\BuilderIO\Api\WebhookRepositoryInterface;
use DeployEcommerce\BuilderIO\Model\WebhookModel;
use DeployEcommerce\BuilderIO\Service\BuilderIO\Page as PageService;
use DeployEcommerce\BuilderIO\System\Config;
use GuzzleHttp\Exception\GuzzleException;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Handler
 *
 * This class handles the processing of webhooks in the queue for the Builder.io integration.
 * It processes pages, creates and removes URLs based on the webhook operations.
 *
 */
class Handler
{
    /**
     * @var WebhookModel
     */
    private WebhookModel $webhook;

    /**
     * Handler constructor.
     *
     * @param Json $json
     * @param LoggerInterface $logger
     * @param PageService $pageService
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param UrlFinderInterface $urlFinder
     * @param WebhookRepositoryInterface $webhookRepository
     * @param ContentPageRepositoryInterface $contentPageRepository
     * @param ContentPageInterfaceFactory $contentPageFactory
     * @param Config $config
     */
    public function __construct(
        private Json $json,
        private LoggerInterface $logger,
        private PageService $pageService,
        private UrlRewriteFactory $urlRewriteFactory,
        private UrlFinderInterface $urlFinder,
        private WebhookRepositoryInterface $webhookRepository,
        private ContentPageRepositoryInterface $contentPageRepository,
        private ContentPageInterfaceFactory $contentPageFactory,
        private Config $config
    ) {
    }

    /**
     * Execute the webhook handler.
     *
     * @param int $webhook_id
     * @return void
     * @throws GuzzleException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(int $webhook_id): void
    {
        $this->webhook = $this->webhookRepository->getById($webhook_id);
        $meta = $this->json->unserialize($this->webhook->getMeta(), true);

        if (isset($meta['kind']) && $meta['kind'] == 'page') {
            if ($this->webhook->getOperation() == 'publish' ||
                $this->webhook->getOperation() == 'draft') {
                $this->processPage();
            } elseif ($this->webhook->getOperation() == 'delete' ||
                $this->webhook->getOperation() == 'archive') {
                $this->deletePage();
            }
        }
    }

    /**
     * Process the page content for the webhook.
     *
     * @return void
     * @throws GuzzleException
     */
    private function processPage()
    {
        $url = $this->webhook->getUrlPath();
        $model_name = $this->webhook->getModelName();

        $page = $this->json->unserialize($this->pageService->fetchContentApi($url, $model_name), true);

        try {
            $contentPage = $this->contentPageRepository->findByBuilderioPageId($page['id']);
        } catch (\Exception $e) {
            $contentPage = $this->contentPageFactory->create();
        }

        $contentPage
            ->setBuilderioPageId($page['id'])
            ->setUrl($page['data']['url'])
            ->setTitle($page['data']['title'])
            ->setMetaDescription($page['data']['Description']??'')
            ->setMetaKeywords($page['data']['Keywords']??'')
            ->setModelName($model_name)
            ->setStoreIds(implode(
                ',',
                $this->config->getMappedStoreFromWorkspace($this->webhook->getOwnerId())
            ))
            ->setStatus($page['published']??'')
            ->setHtml($page['html']);

        try {
            $this->contentPageRepository->save($contentPage);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    private function deletePage()
    {
        try {
            $contentPage = $this->contentPageRepository->findByBuilderioPageId($this->webhook->getBuilderioId());
            $this->contentPageRepository->delete($contentPage);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}

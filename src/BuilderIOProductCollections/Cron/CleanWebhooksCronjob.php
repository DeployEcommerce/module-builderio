<?php
declare(strict_types=1);
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIOProductCollections\Cron;

use DeployEcommerce\BuilderIO\Api\WebhookRepositoryInterface;
use DeployEcommerce\BuilderIO\System\Config;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class CleanOldWebhooks
 *
 * This class provides a cronjob to clean old webhooks older than 60 days.
 *
 */
class CleanWebhooksCronjob
{
    /**
     * CleanOldWebhooks constructor.
     *
     * @param WebhookRepositoryInterface $webhookRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DateTime $dateTime
     * @param Config $config
     */
    public function __construct(
        private WebhookRepositoryInterface $webhookRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder,
        private DateTime $dateTime,
        private Config $config,
    ) {
    }

    /**
     * Execute the cronjob to delete old webhooks.
     *
     * @return void
     * @throws LocalizedException
     */
    public function execute(): void
    {
        $currentDate = $this->dateTime->gmtDate();
        $dateThreshold = $this->dateTime->date(
            'Y-m-d H:i:s',
            strtotime(sprintf('-%s days', (int)$this->config->getWebhookLifetime()), strtotime($currentDate))
        );

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(
                'created_date',
                $dateThreshold,
                'lt'
            )
            ->create();

        $webhooks = $this->webhookRepository->getList($searchCriteria);

        foreach ($webhooks->getItems() as $webhook) {
            $this->webhookRepository->delete($webhook);
        }
    }
}

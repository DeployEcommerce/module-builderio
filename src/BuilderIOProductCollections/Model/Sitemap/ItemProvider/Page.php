<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Model\Sitemap\ItemProvider;

use DeployEcommerce\BuilderIO\Model\ResourceModel\ContentPageModel\ContentPageCollectionFactory;
use DeployEcommerce\BuilderIO\System\Config;
use Magento\Sitemap\Model\SitemapItemInterfaceFactory;

/**
 * Class Page
 *
 * This class provides the Page item provider for the sitemap.
 *
 */
class Page implements ItemProviderInterface
{
    /**
     * Page constructor.
     *
     * @param ContentPageCollectionFactory $contentPageCollectionFactory
     * @param Config $config
     * @param SitemapItemInterfaceFactory $itemFactory
     */
    public function __construct(
        private ContentPageCollectionFactory $contentPageCollectionFactory,
        private Config $config,
        private SitemapItemInterfaceFactory $itemFactory
    ) {
    }

    /**
     * Get the items for the sitemap.
     *
     * @param int $storeId
     * @return array
     */
    public function getItems($storeId): array
    {
        $items = [];

        if ($this->config->isSitemapEnabled($storeId)) {
            $collection = $this->contentPageCollectionFactory->create();

            foreach ($collection as $page) {
                $items[] = $this->itemFactory->create([
                    'url' => $page->getUrl(),
                    'updatedAt' => $page->getUpdatedAt(),
                    'priority' => $this->config->getSitemapPriority($storeId),
                    'changeFrequency' => $this->config->getSitemapChangeFrequency($storeId),
                ]);
            }
        }

        return $items;
    }
}

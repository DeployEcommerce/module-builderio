<?php
declare(strict_types=1);
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
namespace DeployEcommerce\BuilderIO\Model\Sitemap\ItemProvider;

use Magento\Sitemap\Model\SitemapItemInterface;

/**
 * Sitemap item provider interface
 *
 * @api
 * @since 100.3.0
 */
interface ItemProviderInterface
{
    /**
     * Get sitemap items
     *
     * @param int $storeId
     * @return SitemapItemInterface[]
     * @since 100.3.0
     */
    public function getItems($storeId): array;
}

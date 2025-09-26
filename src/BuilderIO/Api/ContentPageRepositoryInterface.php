<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Api;

use DeployEcommerce\BuilderIO\Api\Data\ContentPageInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Interface ContentPageRepositoryInterface
 * @api
 */
interface ContentPageRepositoryInterface
{
    /**
     * Saves a content page
     *
     * @param ContentPageInterface $contentPage
     * @return ContentPageInterface
     * @throws LocalizedException
     */
    public function save(ContentPageInterface $contentPage);

    /**
     * Finds a content page by the entity id
     *
     * @param int $contentId
     * @return ContentPageInterface
     * @throws LocalizedException
     */
    public function getById(int $contentId);

    /**
     * Finds a content page by the builder.io page id
     *
     * @param null|int|string $contentId
     * @return bool
     * @throws LocalizedException
     */
    public function findByBuilderioPageId($contentId);

    /**
     * Deletes a content page
     *
     * @param ContentPageInterface $contentPage
     * @return void
     * @throws LocalizedException
     */
    public function delete(ContentPageInterface $contentPage);

    /**
     * Lists content pages
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ContentPageSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}

<?php
declare(strict_types=1);

/**
 * @Copyright: 2025 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * @api
 */
interface ProductCollectionRepositoryInterface
{
    /**
     * I need this to return products in json form
     *
     * @param int $id
     * @return \DeployEcommerce\BuilderIO\Api\Data\ProductCollectionSearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id): ProductCollectionInterface;

    /**
     * Save product collection.
     *
     * @param mixed $productCollection
     * @return mixed
     */
    public function save($productCollection);

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @inheritDoc
     */
    public function delete($productCollection);

    /**
     * @inheritDoc
     */
    public function deleteById($id);
}

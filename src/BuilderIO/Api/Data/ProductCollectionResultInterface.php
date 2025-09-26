<?php
/**
 * @Copyright: 2025 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Api\Data;

interface ProductCollectionResultInterface
{

    /**
     * Get product collection by collection ID.
     *
     * @param int $collectionId
     * @return array
     */
    public function getProductCollection(int $collectionId);
}

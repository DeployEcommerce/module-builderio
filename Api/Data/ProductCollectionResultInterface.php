<?php

namespace DeployEcommerce\BuilderIO\Api\Data;

interface ProductCollectionResultInterface
{
    /**
     * @param int $collectionId
     * @return array
     */
    public function getProductCollection(int $collectionId);
}

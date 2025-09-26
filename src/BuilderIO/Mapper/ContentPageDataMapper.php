<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Mapper;

use DeployEcommerce\BuilderIO\Model\ContentPageModel;
use DeployEcommerce\BuilderIO\Api\Data\ContentPageInterface;
use DeployEcommerce\BuilderIO\Api\Data\ContentPageInterfaceFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class WebhookDataMapper
 *
 * This class maps Magento models to Data Transfer Objects (DTOs) for the Builder.io integration.
 * It uses the WebhookInterfaceFactory to create instances of WebhookInterface and populate them with data.
 *
 */
class ContentPageDataMapper
{

    /**
     * @param ContentPageInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        private ContentPageInterfaceFactory $entityDtoFactory
    ) {
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|ContentPageInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var ContentPageModel $item */
        foreach ($collection->getItems() as $item) {
            /** @var ContentPageInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}

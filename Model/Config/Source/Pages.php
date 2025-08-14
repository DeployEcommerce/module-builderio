<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
namespace DeployEcommerce\BuilderIO\Model\Config\Source;

use DeployEcommerce\BuilderIO\Model\ContentPageRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Pages
 *
 * This class is used to provide a list of CMS pages for the Builder.io integration.
 *
 */
class Pages implements OptionSourceInterface
{
    public function __construct(
        private ContentPageRepository $contentPageRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $pages = $this->contentPageRepository->getList($searchCriteria);

        if ($pages->getTotalCount()) {
            $items = $pages->getItems();
            foreach ($items as $page) {
                $options[] = ['value' => $page->getId(), 'label' => $page->getTitle()];
            }
        }

        return $options;
    }
}

<?php
/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Ui\DataProvider;

use DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection\Collection;
use Magento\CatalogRule\Model\Data\Condition\Converter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;

class ProductCollectionFormDataProvider extends DataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Collection $collection,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        protected Converter $converter,
        private ProductCollectionRepositoryInterface $productCollectionRepository,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collection;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        $id = $this->request->getParam("id");

        $productCollection = $this->productCollectionRepository->getById($id);

        return [$id => $productCollection->getData()];
    }
}

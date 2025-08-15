<?php
/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Ui\DataProvider;

use DeployEcommerce\BuilderIO\Api\ProductCollectionInterfaceFactory;
use DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface;
use DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection\Collection;
use Magento\CatalogRule\Model\Data\Condition\Converter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;

class ProductCollectionFormDataProvider extends DataProvider
{
    /**
     * @var array
     */
    protected array $loadedData;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * ProductCollectionFormDataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Collection $collection
     * @param Reporting $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param Converter $converter
     * @param ProductCollectionRepositoryInterface $productCollectionRepository
     * @param ProductCollectionInterfaceFactory $productCollectionInterfaceFactory
     * @param array $meta
     * @param array $data
     */
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
        private ProductCollectionInterfaceFactory $productCollectionInterfaceFactory,
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

    /**
     * Retrieve data for the data provider
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $id = $this->request->getParam("id");

        if ($id) {
            try {
                $productCollection = $this->productCollectionRepository->getById($id);
            } catch (LocalizedException $exception) {
                $productCollection = $this->productCollectionInterfaceFactory->create();
            }
        } else {
            $productCollection = $this->productCollectionInterfaceFactory->create();
        }

        $this->loadedData[$productCollection->getId()] = $productCollection->getData();

        return $this->loadedData;
    }
}

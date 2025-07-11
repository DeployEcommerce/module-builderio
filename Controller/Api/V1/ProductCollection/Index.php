<?php
/**
 * @Author:    Brandon van Rensburg
 * @Copyright: 2025 DeployEcommerce (https://www.techarlie.co.za/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Controller\Api\V1\ProductCollection;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Webapi\Rest\Response;
use DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface;
use DeployEcommerce\BuilderIO\Helper\Data as HelperData;

class Index implements HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface
     */
    protected $productCollectionRepository;

    /**
     * @var \DeployEcommerce\BuilderIO\Helper\Data
     */
    protected $helperData;

    /**
     * @param \Magento\Framework\Controller\ResultFactory $resultFactory
     * @param \DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface $productCollectionRepository
     * @param \DeployEcommerce\BuilderIO\Helper\Data $helperData
     */
    public function __construct(
        ResultFactory $resultFactory,
        ProductCollectionRepositoryInterface $productCollectionRepository,
        HelperData $helperData
    ) {
        $this->resultFactory = $resultFactory;
        $this->productCollectionRepository = $productCollectionRepository;
        $this->helperData = $helperData;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Webapi\Rest\Response $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $collectionId = $this->getRequest()->getParam('id');

        try {
            $productCollection = $this->productCollectionRepository->getById($collectionId);
            $products = $this->helperData->getProductsByCollection($productCollection);
            $result->setData(['products' => $products, 'count' => count($products)]);
        } catch (\Exception $e) {
            $result->setHttpResponseCode(Response::HTTP_NOT_FOUND);
            $result->setData(['message' => $e->getMessage()]);
        }

        return $result;
    }
}

<?php
/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Block\Adminhtml\Edit;

use DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;

    /**
     * @var \DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface
     */
    protected $productCollectionRepository;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \DeployEcommerce\BuilderIO\Api\ProductCollectionRepositoryInterface $productCollectionRepository
     */
    public function __construct(
        Context $context,
        ProductCollectionRepositoryInterface $productCollectionRepository
    ) {
        $this->context = $context;
        $this->productCollectionRepository = $productCollectionRepository;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        try {
            return $this->productCollectionRepository->getById(
                $this->context->getRequest()->getParam('id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}

<?php
declare(strict_types=1);

/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */


namespace DeployEcommerce\BuilderIO\ViewModel\Preview;

use DeployEcommerce\BuilderIO\System\Config;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class Page
 *
 * This class is responsible for providing the data required to render the Builder.io preview page.
 * It implements the Magento\Framework\View\Element\Block\ArgumentInterface interface.
 *
 */
class Page implements ArgumentInterface
{
    /**
     * Page constructor.
     *
     * @param Config $settings
     * @param RequestInterface $request
     */
    public function __construct(
        private Config $settings,
        private RequestInterface $request
    ) {
    }

    /**
     * Get the API key for the Builder.io integration.
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->settings->getPublicKey();
    }

    /**
     * Get the model name for the preview page.
     *
     * @return string
     */
    public function getModelName()
    {
        return $this->request->getParam('model') ?: 'page';
    }
}

<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Block\System\Config;

use DeployEcommerce\BuilderIO\System\Config;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Button;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

/**
 * Class Connect
 *
 * This class provides the functionality for the "Connect" button in the system configuration.
 * It extends the Magento Form Field and uses a custom template to render the button.
 *
 */
class Connect extends Field
{
    /**
     * @var string
     */
    protected $_template = 'DeployEcommerce_BuilderIO::system/config/connect.phtml';

    /**
     * Connect constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param array $data
     * @param SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        Context $context,
        private Config $config,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ){
        parent::__construct($context, $data, $secureRenderer);
    }


    /**
     * Return element html
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Return ajax url for connect button
     *
     * @return string
     */
    public function getAjaxConnectUrl()
    {
        return $this->getUrl('builderio/config/connectionajax', ['store' => $this->getRequest()->getParam('store', 0)]);
    }

    /**
     * Generate Connect button html
     *
     * @return string
     * @throws LocalizedException
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            Button::class
        )->setData(
            [
                'id' => 'connect_button',
                'label' => __('Connect to Builder.io Workspace'),
                'disabled' => !$this->config->getPublicKey() && !$this->config->getPrivateKey(),
            ]
        );

        return $button->toHtml();
    }
}

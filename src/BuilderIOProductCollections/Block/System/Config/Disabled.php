<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Disabled
 *
 * This class provides the functionality to disable form fields in the system configuration.
 * It extends the Magento Form Field and overrides the _getElementHtml method to disable the field if it has a value.
 *
 */
class Disabled extends Field
{
    /**
     * Override the _getElementHtml method to disable the field if it has a value.
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        if ($element->getEscapedValue()) {
            $element->setDisabled('disabled');
        }

        return $element->getElementHtml();
    }
}

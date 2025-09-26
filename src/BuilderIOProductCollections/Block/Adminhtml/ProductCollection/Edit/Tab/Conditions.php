<?php
declare(strict_types=1);

/**
 * @Author:    Brandon Bishop
 * @Copyright: 2025 DeployEcommerce (https://www.deploy.co.uk
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIOProductCollections\Block\Adminhtml\ProductCollection\Edit\Tab;

use DeployEcommerce\BuilderIO\Model\ProductCollectionFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset;
use Magento\CatalogRule\Model\Data\Condition\Converter;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Rule\Block\Conditions as RuleConditions;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

/**
 * Conditions tab
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Conditions extends Generic implements TabInterface
{
    /**
     * @var Fieldset
     */
    protected $_rendererFieldset;

    /**
     * @var RuleConditions
     */
    protected $_conditions;
    /**
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Converter $converter
     * @param FormFactory $formFactory
     * @param RuleConditions $conditions
     * @param Fieldset $rendererFieldset
     * @param ProductCollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        protected Converter $converter,
        FormFactory $formFactory,
        RuleConditions $conditions,
        Fieldset $rendererFieldset,
        ProductCollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->_rendererFieldset = $rendererFieldset;
        $this->_conditions = $conditions;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Get tab title.
     *
     * @return \Magento\Framework\Phrase
     * @throws LocalizedException
     */
    public function getTabTitle()
    {
        return __('Conditions');
    }

    /**
     * Can show tab.
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Is hidden.
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * To HTML.
     *
     * @return string
     * @throws LocalizedException
     */
    public function toHtml()
    {
        return parent::toHtml();
    }

    /**
     * Prepare form.
     *
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('builderio_productcollection');
        if (!$model) {
            $model = $this->productCollectionFactory->create();
        }

        $form = $this->addTabToForm($model);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Add tab to form.
     *
     * @param mixed $model
     * @param string $fieldsetId
     * @param string $formName
     * @return Form
     * @throws LocalizedException
     */
    protected function addTabToForm($model, $fieldsetId = 'conditions_fieldset', $formName = 'builderio_products_form')
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rule_');

        $renderer = $this->_rendererFieldset->setTemplate(
            'Magento_CatalogRule::promo/fieldset.phtml'
        )->setNewChildUrl(
            $this->getUrl('catalog_rule/promo_catalog/newConditionHtml/form/rule_conditions_fieldset')
        );

        $fieldset = $form->addFieldset(
            $fieldsetId,
            [
                'legend' => __(
                    'Apply the rule only if the following conditions are met (leave blank for all products).'
                )
            ]
        )->setRenderer(
            $renderer
        );

        $fieldset->addField(
            'conditions',
            'text',
            [
                'name' => 'rule',
                'label' => __('Conditions'),
                'title' => __('Conditions'),
                'data-form-part' => $formName
            ]
        )->setRule(
            $model
        )->setRenderer(
            $this->_conditions
        );

        $this->setConditionFormName($model->getConditions(), $fieldsetId);
        return $form;
    }

    /**
     * Set condition form name.
     *
     * @param mixed $conditions
     * @param string $formName
     * @return void
     */
    protected function setConditionFormName($conditions, $formName)
    {
        $conditions->setJsFormObject($formName);
    }

    /**
     * Get tab label.
     *
     * @return string|null
     */
    public function getTabLabel()
    {
        return null;
    }

    /**
     * Get tab class.
     *
     * @return string|null
     */
    public function getTabClass()
    {
        return null;
    }

    /**
     * Get tab URL.
     *
     * @return string|null
     */
    public function getTabUrl()
    {
        return null;
    }

    /**
     * Is AJAX loaded.
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }
}

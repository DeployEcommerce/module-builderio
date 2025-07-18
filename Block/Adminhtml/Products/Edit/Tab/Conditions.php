<?php
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Block\Adminhtml\Products\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Rule\Block\Conditions as RuleConditions;

/**
 * Conditions tab
 */
class Conditions extends Generic implements TabInterface
{
    /**
     * @var string
     */
    protected $_nameInLayout = 'conditions_apply_to';

    /**
     * @var RuleConditions
     */
    protected RuleConditions $ruleConditions;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param RuleConditions $ruleConditions
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        RuleConditions $ruleConditions,
        array $data = []
    ) {
        $this->ruleConditions = $ruleConditions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return Conditions
     * @throws LocalizedException
     */
    protected function _prepareForm(): Conditions
    {
        $model = $this->_coreRegistry->registry('builderio_product_collection');
        $form = $this->addTabToForm($model);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Add tab to form
     *
     * @param $model
     * @param string $fieldsetId
     * @param string $formName
     *
     * @return \Magento\Framework\Data\Form
     * @throws LocalizedException
     */
    protected function addTabToForm(
        $model,
        string $fieldsetId = 'conditions_fieldset',
        string $formName = 'builderio_product_collection_form'
    ): Form {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            $model->load($id);
        }

        $conditions = $model->getConditions();
        /**@var \Magento\Framework\Data\Form $form*/
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rule_');
        $renderer = $this->ruleConditions
            ->setTemplate('Magento_CatalogRule::promo/conditions.phtml')
            ->setFormName($form->getHtmlIdPrefix());
        $fieldset = $form->addFieldset(
            $fieldsetId,
            [
                'legend' => __(
                    'Conditions (don\'t add conditions if rule is applied to all products)'
                )
            ]
        )->setRenderer($renderer);
        $fieldset->addField(
            'conditions',
            'text',
            [
                'name' => 'conditions',
                'label' => __('Conditions'),
                'title' => __('Conditions'),
                'required' => true,
                'data-form-part' => $formName,
            ]
        )->setRule($model)->setRenderer($this->ruleConditions);
        $form->setValues($model->getData());
        $this->setConditionFormName($conditions, $formName);

        return $form;
    }

    /**
     * Get tab label
     *
     * @return string
     */
    public function getTabLabel(): string
    {
        return __('Conditions');
    }

    /**
     * Get tab title
     *
     * @return string
     */
    public function getTabTitle(): string
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab
     *
     * @return boolean
     */
    public function canShowTab(): bool
    {
        return true;
    }

    /**
     * Is hidden
     *
     * @return boolean
     */
    public function isHidden(): bool
    {
        return false;
    }
}

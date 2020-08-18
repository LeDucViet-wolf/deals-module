<?php

namespace Demo\Deal\Block\Adminhtml\Post\Edit\Tab;

use Demo\Deal\Model\System\Config\Product;
use Demo\Deal\Model\System\Config\Status;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class Info extends Generic implements TabInterface
{
    protected $status;
    protected $product;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Product $product,
        Status $status,
        array $data = []
    )
    {
        $this->status = $status;
        $this->product = $product;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('demo_deal');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('post_');
        $form->setFieldNameSuffix('post');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Deal Product Detail')]
        );

        if ($model->getId()) {
            $fieldset->addField(
                'id',
                'hidden',
                ['name' => 'id']
            );
        }
        $fieldset->addField(
            'product',
            'select',
            [
                'name' => 'product',
                'label' => __('Product'),
                'title' => __('Product'),
                'options' => $this->product->toOptionArray(),
                'required' => true
            ]
        );
        $fieldset->addField(
            'deal_price',
            'text',
            array(
                'name' => 'deal_price',
                'label' => __('Deal Price'),
                'title' => __('Deal Price'),
                'required' => true
            )
        );
        $fieldset->addField(
            'deal_qty',
            'text',
            array(
                'name' => 'deal_qty',
                'label' => __('Deal Qty'),
                'title' => __('Deal Qty'),
                'required' => true
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'name' => 'status',
                'label' => __('Status'),
                'options' => $this->status->toOptionArray(),
                'required' => true
            )
        );
        $fieldset->addField(
            'time_start',
            'date',
            [
                'name' => 'time_start',
                'label' => __('Time Start'),
                'date_format' => 'yyyy-MM-dd',
                'time_format' => 'hh:mm:ss',
                'required' => true
            ]
        );
        $fieldset->addField(
            'time_end',
            'date',
            [
                'name' => 'time_end',
                'label' => __('Time End'),
                'date_format' => 'yyyy-MM-dd',
                'time_format' => 'hh:mm:ss',
                'required' => true
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return __('Posts Info');
    }

    public function getTabTitle()
    {
        return __('Posts Info');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}

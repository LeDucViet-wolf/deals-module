<?php
namespace Demo\Magento\Block\Adminhtml\Post\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
// use Magento\Cms\Model\Wysiwyg\Config;
use Demo\Magento\Model\System\Config\Status;
use Demo\Magento\Model\System\Config\Product;

class Info extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    // protected $_wysiwygConfig;

    /**
     * @var Demo\Magento\Model\System\Config\Status
     */
    protected $_status;
    protected $product;
    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Status $status
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        // Config $wysiwygConfig,
        Product $product,
        Status $status,
        array $data = []
    ) {
        // $this->_wysiwygConfig = $wysiwygConfig;
        $this->_status = $status;
        $this->product = $product;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('demo_deal');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('post_');
        $form->setFieldNameSuffix('post');
        // new filed

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
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
                'name'      => 'product',
                'label'     => __('Product'),
                'title' => __('Product'),
//                'minlength' => 5,
//                'required' => true
                'options' => $this->product->toOptionArray()
            ]
        );
        $fieldset->addField(
            'deal_price',
            'text',
            array(
                'name'      => 'deal_price',
                'label'     => __('Deal Price'),
                'title' => __('Deal Price'),
                'required' => true
            )
        );
        $fieldset->addField(
            'deal_qty',
            'text',
            array(
                'name'      => 'deal_qty',
                'label'     => __('Deal Qty'),
                'title' => __('Deal Qty'),
                'required' => true
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'name'      => 'status',
                'label'     => __('Status'),
                'title' => __('Status'),
//                'required' => true
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
    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Posts Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Posts Info');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}

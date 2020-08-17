<?php

namespace Demo\Magento\Block\Adminhtml\Post\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('post_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Post Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'post_info',
            [
                'label' => __('Post'),
                'title' => __('Post'),
                'content' => $this->getLayout()->createBlock(
                    'Demo\Magento\Block\Adminhtml\Post\Edit\Tab\Info'
                )->toHtml(),
                'active' => true
            ]
        );

        return parent::_beforeToHtml();
    }
}

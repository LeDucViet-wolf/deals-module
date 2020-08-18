<?php

namespace Demo\Deal\Controller\Adminhtml\Post;


use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $result_page_factory = false;
    protected $core_registry;

    public function __construct(
        Context $context,
        PageFactory $result_page_factory
    )
    {
        parent::__construct($context);
        $this->result_page_factory = $result_page_factory;
    }

    public function execute()
    {
        $result_page = $this->result_page_factory->create();
        $result_page->getConfig()->getTitle()->prepend((__('Deal Products')));

        return $result_page;
    }
}

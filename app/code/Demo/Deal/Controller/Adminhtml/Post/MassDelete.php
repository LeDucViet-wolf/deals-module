<?php

namespace Demo\Deal\Controller\Adminhtml\Post;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Result\PageFactory;
use Demo\Deal\Model\PostFactory;
use Magento\Ui\Component\MassAction\Filter;
use Demo\Deal\Model\ResourceModel\Post\CollectionFactory;

class MassDelete extends Action
{
    protected $page_factory;
    protected $request;
    protected $post_factory;
    protected $filter;
    protected $module_factory;

    public function __construct(
        Context $context,
        PageFactory $page_factory,
        Http $request,
        Filter $filter,
        PostFactory $post_factory,
        CollectionFactory $module_factory
    )
    {
        $this->page_factory = $page_factory;
        $this->request = $request;
        $this->post_factory = $post_factory;
        $this->filter = $filter;
        $this->module_factory = $module_factory;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->module_factory->create());
        $collection_size = $collection->getSize();

        foreach ($collection as $child) {
            $child->delete();
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.',$collection_size));
        return $this->_redirect('max/post/index');
    }
}

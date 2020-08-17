<?php

namespace Demo\Magento\Controller\Adminhtml\Post;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Result\PageFactory;
use Demo\Magento\Model\PostFactory;
use Magento\Ui\Component\MassAction\Filter;
use Demo\Magento\Model\ResourceModel\Post\CollectionFactory;

class MassDelete extends Action
{
    protected $_pageFactory;
    protected $_request;
    protected $_postsFactory;
    protected $filter;
    protected $moduleFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        Http $request,
        Filter $filter,
        PostFactory $postsFactory,
        CollectionFactory $moduleFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_request = $request;
        $this->_postsFactory = $postsFactory;
        $this->filter = $filter;
        $this->moduleFactory = $moduleFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->moduleFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $child) {
            $child->delete();
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.',$collectionSize));
        return $this->_redirect('max/post/index');
    }
}

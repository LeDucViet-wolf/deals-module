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
//        $deleteIds = $this->getRequest()->getParam('selected');
//
//        $collection = $this->moduleFactory->create();
//
//        $collection->addFieldToFilter('id', array('in' => $deleteIds));
//        $count = 0;
        $collection = $this->filter->getCollection($this->moduleFactory->create());
//        var_dump($collection);
        $collectionSize = $collection->getSize();
        foreach ($collection as $child) {
//            var_dump($child);
            $child->delete();
//            $count++;
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.',$collectionSize));

//        var_dump($collection);
//        $id = 3;
//        $id = $this->_request->getParam('id');
//        $post = $this->_postsFactory->create();
//        $result = $post->setId($deleteIds);
//        if ($result = $result->delete()){
//            $this->messageManager->addSuccessMessage(__('You deleted the data.'));
//        } else {
//            $this->messageManager->addErrorMessage(__('Data was not deleted.'));
//        }
        return $this->_redirect('max/post/index');
    }
}

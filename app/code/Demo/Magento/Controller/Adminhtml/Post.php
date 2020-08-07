<?php

namespace Demo\Magento\Controller\Adminhtml;

use Demo\Magento\Model\PostFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Post extends Action
{
    protected $_coreRegistry;
    protected $_resultPageFactory;
    protected $_postsFactory;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        PostFactory $postsFactory
    )
    {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_postsFactory = $postsFactory;

    }

    public function execute()
    {

    }

    protected function _isAllowed()
    {
        return true;
    }
}

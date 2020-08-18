<?php

namespace Demo\Deal\Controller\Adminhtml;

use Demo\Deal\Model\PostFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;


class Post extends Action
{
    protected $coreRegistry;
    protected $resultPageFactory;
    protected $postsFactory;
    protected $config;

    public function __construct(
        Context     $context,
        Registry    $coreRegistry,
        PageFactory $resultPageFactory,
        PostFactory $postsFactory,
        ScopeConfigInterface $config
    )
    {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->postsFactory = $postsFactory;
        $this->config = $config;

    }

    public function execute()
    {

    }

    protected function _isAllowed()
    {
        $configValue = $this->config->getValue('helloworld_section_id/general/enable');
        if ($configValue != 0) {
            return true;
        } else {
            return false;
        }
    }
}

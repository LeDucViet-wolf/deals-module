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
    protected $core_registry;
    protected $result_page_factory;
    protected $post_factory;
    protected $config;

    public function __construct(
        Context     $context,
        Registry    $core_registry,
        PageFactory $result_page_factory,
        PostFactory $post_factory,
        ScopeConfigInterface $config
    )
    {
        parent::__construct($context);
        $this->core_registry = $core_registry;
        $this->result_page_factory = $result_page_factory;
        $this->post_factory = $post_factory;
        $this->config = $config;

    }

    public function execute()
    {

    }

    protected function _isAllowed()
    {
        $config_value = $this->config->getValue('helloworld_section_id/general/enable');
        if ($config_value != 0) {
            return true;
        } else {
            return false;
        }
    }
}

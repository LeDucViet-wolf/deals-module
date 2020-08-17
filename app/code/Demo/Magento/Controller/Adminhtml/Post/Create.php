<?php

namespace Demo\Magento\Controller\Adminhtml\Post;

use Demo\Magento\Controller\Adminhtml\Post;

class Create extends Post
{
    public function execute()
    {
        $this->_forward('edit');
    }
}

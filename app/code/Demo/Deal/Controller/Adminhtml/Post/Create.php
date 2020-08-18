<?php

namespace Demo\Deal\Controller\Adminhtml\Post;

use Demo\Deal\Controller\Adminhtml\Post;

class Create extends Post
{
    public function execute()
    {
        $this->_forward('edit');
    }
}

<?php
namespace Demo\Magento\Controller\Adminhtml\Post;

use Demo\Magento\Controller\Adminhtml\Post;

class Create extends Post
{
    /**
     * Create new news action
     *
     * @return void
     */
    public function execute()
    {
        // die(__METHOD__);
        $this->_forward('edit');
    }
}

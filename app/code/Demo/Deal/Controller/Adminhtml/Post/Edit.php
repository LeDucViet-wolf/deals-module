<?php

namespace Demo\Deal\Controller\Adminhtml\Post;

use Demo\Deal\Controller\Adminhtml\Post;
use Magento\Backend\Model\View\Result\Page;

class Edit extends Post
{
    public function execute()
    {
        $postId = $this->getRequest()->getParam('id');

        $model = $this->post_factory->create();

        if ($postId) {
            $model->load($postId);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This news no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $data = $this->_session->getNewsData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->core_registry->register('demo_deal', $model);
        $result_page = $this->result_page_factory->create();
        $result_page->setActiveMenu('Demo_Deal::hello');
        $result_page->getConfig()->getTitle()->prepend(__('Posts'));

        return $result_page;
    }
}

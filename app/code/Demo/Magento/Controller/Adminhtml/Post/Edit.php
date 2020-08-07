<?php

namespace Demo\Magento\Controller\Adminhtml\Post;

use Demo\Magento\Controller\Adminhtml\Post;
use Magento\Backend\Model\View\Result\Page;

class Edit extends Post
{
    /**
     * @return void
     */
    public function execute()
    {
//         die(__METHOD__);
        $postId = $this->getRequest()->getParam('id');

        $model = $this->_postsFactory->create();

        if ($postId) {
            $model->load($postId);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This news no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        // Restore previously entered form data from session
        $data = $this->_session->getNewsData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('demo_deal', $model);

        /** @var Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Demo_Magento::hello');
        $resultPage->getConfig()->getTitle()->prepend(__('Posts'));

        return $resultPage;
    }
}

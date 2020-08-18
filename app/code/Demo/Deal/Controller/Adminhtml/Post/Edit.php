<?php

namespace Demo\Deal\Controller\Adminhtml\Post;

use Demo\Deal\Controller\Adminhtml\Post;
use Magento\Backend\Model\View\Result\Page;

class Edit extends Post
{
    /**
     * @return void
     */
    public function execute()
    {
        $postId = $this->getRequest()->getParam('id');

        $model = $this->postsFactory->create();

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
        $this->coreRegistry->register('demo_deal', $model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Demo_Deal::hello');
        $resultPage->getConfig()->getTitle()->prepend(__('Posts'));

        return $resultPage;
    }
}

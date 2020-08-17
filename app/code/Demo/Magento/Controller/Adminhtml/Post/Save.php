<?php

namespace Demo\Magento\Controller\Adminhtml\Post;

use Exception;
use Demo\Magento\Controller\Adminhtml\Post;

class Save extends Post
{
    public function execute()
    {
        $isPost = $this->getRequest()->getPost();

        if ($isPost) {
            $postsModel = $this->postsFactory->create();
            $postsId = $this->getRequest()->getParam('id');

            if ($postsId) {
                $postsModel->load($postsId);
            }
            $formData = $this->getRequest()->getParam('post');
            $postsModel->setData($formData);

            try {
                $postsModel->save();

                $this->messageManager->addSuccess(__('The news has been saved.'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $postsModel->getId(), '_current' => true]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }

            $this->_getSession()->setFormData($formData);
            $this->_redirect('*/*/edit', ['id' => $postsId]);
        }
    }
}

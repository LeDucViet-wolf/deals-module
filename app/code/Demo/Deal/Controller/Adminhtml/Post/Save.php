<?php

namespace Demo\Deal\Controller\Adminhtml\Post;

use Exception;
use Demo\Deal\Controller\Adminhtml\Post;

class Save extends Post
{
    public function execute()
    {
        $is_post = $this->getRequest()->getPost();

        if ($is_post) {
            $post_model = $this->post_factory->create();
            $post_id = $this->getRequest()->getParam('id');

            if ($post_id) {
                $post_model->load($post_id);
            }
            $formData = $this->getRequest()->getParam('post');
            $post_model->setData($formData);

            try {
                $post_model->save();

                $this->messageManager->addSuccess(__('The products has been saved.'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $post_model->getId(), '_current' => true]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }

            $this->_getSession()->setFormData($formData);
            $this->_redirect('*/*/edit', ['id' => $post_id]);
        }
    }
}

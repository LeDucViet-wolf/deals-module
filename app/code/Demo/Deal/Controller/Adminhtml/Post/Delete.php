<?php

namespace Demo\Deal\Controller\Adminhtml\Post;

use Demo\Deal\Model\Post;
use Exception;
use Magento\Backend\App\Action;

class Delete extends Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $result_redirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $model = $this->_objectManager->create(Post::class);
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The product has been deleted.'));
                return $result_redirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $result_redirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a product to delete.'));
        return $result_redirect->setPath('*/*/');
    }
}


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
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $model = $this->_objectManager->create(Post::class);
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The news has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a news to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}


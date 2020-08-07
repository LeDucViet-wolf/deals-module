<?php

namespace Demo\Magento\Controller\Adminhtml\Post;

use Exception;
use Demo\Magento\Model\Post;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

class Delete extends Action
{
    /**
     * Authorization level
     *
     * @see _isAllowed()
     */

    /**
     * Delete action
     *
     * @return Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        /** @var Redirect $resultRedirect */
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
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a news to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}

?>

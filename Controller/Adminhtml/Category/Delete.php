<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Post;

use Exception;
use Magento\Backend\Model\View\Result\Redirect;
use ZT\Blog\Controller\Adminhtml\AbstractPost;
use ZT\Blog\Model\Post;

/**
 * Blog post delete controller
 */
class Delete extends AbstractPost
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PWA_Blog::post_delete';

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
                // init model and delete
                /** @var Post $model */
                $model = $this->_postFactory->create();
                $model->load($id);
                $model->delete();

                // display success message
                $this->messageManager->addSuccessMessage(__('The blog post has been deleted.'));

                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }

        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find that blog post to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Tag;

use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use ZT\Blog\Controller\Adminhtml\AbstractTag;
use ZT\Blog\Model\Tag;

/**
 * Blog tag edit controller
 */
class Edit extends AbstractTag
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PWA_Blog::tag_save';

    /**
     * Edit blog tag
     *
     * @return Page|Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('tag_id');

        /** @var Tag $model */
        $model = $this->_tagFactory->create();

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This tag no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('blog_tag', $model);

        // 5. Build edit form
        /** @var Page $resultPage */
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Blog Tag') : __('New Blog Tag'),
            $id ? __('Edit Blog Tag') : __('New Blog Tag')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Blog Tags'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Blog Tag'));

        return $resultPage;
    }
}

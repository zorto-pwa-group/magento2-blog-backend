<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Post;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Exception;
use ZT\Blog\Controller\Adminhtml\AbstractPost;
use ZT\Blog\Model\ResourceModel\Post\Collection;

/**
 * Blog post change status controller
 */
class MassStatus extends AbstractPost
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PWA_Blog::post_save';

    /**
     * Execute action
     *
     * @return Redirect
     * @throws LocalizedException|\Exception
     */
    public function execute()
    {
        $ids = $this->getRequest()->getParam('id');
        $status = $this->getRequest()->getParam('status');

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /** @var Collection $collection */
        $collection = $model = $this->_postFactory->create()->getCollection();
        $collection->addFieldToFilter('post_id', ['IN' => $ids]);
        try {
            foreach ($collection as $item) {
                $item->setIsActive($status);
                $item->save();
            }

            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been changed status.', $collection->getSize())
            );
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('There are some errors when changing post statuses.'));
            return $resultRedirect->setPath('*/*/');
        }

        return $resultRedirect->setPath('*/*/');
    }
}

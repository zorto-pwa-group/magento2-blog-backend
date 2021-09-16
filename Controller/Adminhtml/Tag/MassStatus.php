<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Tag;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Exception;
use ZT\Blog\Controller\Adminhtml\AbstractTag;
use ZT\Blog\Model\ResourceModel\Tag\Collection;

/**
 * Blog tag change status controller
 */
class MassStatus extends AbstractTag
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PWA_Blog::tag_save';

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
        $collection = $model = $this->_tagFactory->create()->getCollection();
        $collection->addFieldToFilter('tag_id', ['IN' => $ids]);
        try {
            foreach ($collection as $item) {
                $item->setIsActive($status);
                $item->save();
            }

            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been changed status.', $collection->getSize())
            );
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('There are some errors when changing tag statuses.'));
            return $resultRedirect->setPath('*/*/');
        }

        return $resultRedirect->setPath('*/*/');
    }
}

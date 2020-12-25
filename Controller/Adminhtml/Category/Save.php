<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Category;

use Exception;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use ZT\Blog\Controller\Adminhtml\AbstractCategory;
use ZT\Blog\Helper\Data;
use ZT\Blog\Model\Category;

/**
 * Blog category save controller
 */
class Save extends AbstractCategory implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'ZT_Blog::category_save';

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data['id']) && empty($data['category_id'])) {
                $data['category_id'] = null;
            }

            /** @var Category $model */
            $model = $this->_categoryFactory->create();

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                try {
                    $model = $this->_categoryRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This blog category no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);
            try {
                $this->_beforeSave($model);
                $this->_categoryRepository->save($model);
                $id = !empty($id) ? $id : $model->getId();
                if ($this->getRequest()->getParam('isAjax')) {
                    $block = $this->_objectManager->create(\Magento\Framework\View\Layout::class)->getMessagesBlock();
                    $block->setMessages($this->messageManager->getMessages(true));
                    $this->getResponse()->setBody(json_encode(
                        [
                            'messages' => $block->getGroupedHtml(),
                            'error' => 0,
                            'model' => $model->toArray(),
                        ]
                    ));
                    return;
                }
                $this->messageManager->addSuccessMessage(__('You saved the category.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage(__('Something went wrong while saving the blog category.'));
            }
            $this->_dataPersistor->set('pwablog_category', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $model
     */
    protected function _beforeSave($model)
    {
        /* Prepare URL key from title */
        if (!$model->getIdentifier()) {
            $title = $model->getTitle();
            $urlKey = $this->_objectManager->create(Data::class)->urlKeyGeneration($title);
            $model->setIdentifier($urlKey);
        }
    }
}

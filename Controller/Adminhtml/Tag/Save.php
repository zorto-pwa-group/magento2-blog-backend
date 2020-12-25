<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Tag;

use Exception;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use ZT\Blog\Controller\Adminhtml\AbstractTag;
use ZT\Blog\Helper\Data;
use ZT\Blog\Model\Tag;

/**
 * Blog tag save controller
 */
class Save extends AbstractTag implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'ZT_Blog::tag_save';

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
            if (empty($data['id']) && empty($data['tag_id'])) {
                $data['tag_id'] = null;
            }
            /** @var Tag $model */
            $model = $this->_tagFactory->create();

            $id = $this->getRequest()->getParam('tag_id');
            if ($id) {
                try {
                    $model = $this->_tagRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This blog tag no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);
            try {
                if (!$id) {
                    $model->setId(null);
                }
                $this->_beforeSave($model);
                $this->_tagRepository->save($model);
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
                $id = !empty($id) ? $id : $model->getId();
                $this->messageManager->addSuccessMessage(__('You saved the tag.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the blog tag.'));
            }
            $this->_dataPersistor->set('pwablog_tag', $data);
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

<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Post;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Request\DataPersistorInterface;
use ZT\Blog\Controller\Adminhtml\AbstractPost;

/**
 * Blog post list controller
 */
class Index extends AbstractPost
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'ZT_Blog::post';

    /**
     * Index action
     *
     * @return Page
     */
    public function execute()
    {
        if ($this->getRequest()->getParam('ajax')) {
            $this->_forward('grid');
            return;
        }
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('ZT_Blog::post');
        $resultPage->addBreadcrumb(__('PWA Blog'), __('Posts'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Blog Posts'));

        $dataPersistor = $this->_objectManager->get(DataPersistorInterface::class);
        $dataPersistor->clear('pwablog_post');

        return $resultPage;
    }
}

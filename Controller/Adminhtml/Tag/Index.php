<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Tag;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Request\DataPersistorInterface;
use ZT\Blog\Controller\Adminhtml\AbstractTag;

/**
 * Blog tag list controller
 */
class Index extends AbstractTag
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PWA_Blog::tag';

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
        $resultPage->setActiveMenu('ZT_Blog::tag');
        $resultPage->addBreadcrumb(__('PWA Blog'), __('Tags'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Blog Tags'));

        $dataPersistor = $this->_objectManager->get(DataPersistorInterface::class);
        $dataPersistor->clear('pwablog_tag');

        return $resultPage;
    }
}

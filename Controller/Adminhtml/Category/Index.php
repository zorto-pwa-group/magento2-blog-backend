<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Category;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Request\DataPersistorInterface;
use ZT\Blog\Controller\Adminhtml\AbstractCategory;

/**
 * Blog category list controller
 */
class Index extends AbstractCategory
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'ZT_Blog::catagory';

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
        $resultPage->setActiveMenu('ZT_Blog::category');
        $resultPage->addBreadcrumb(__('PWA Blog'), __('Categories'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Blog Categories'));

        $dataPersistor = $this->_objectManager->get(DataPersistorInterface::class);
        $dataPersistor->clear('pwablog_category');

        return $resultPage;
    }
}

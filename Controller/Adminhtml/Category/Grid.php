<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Category;

use ZT\Blog\Controller\Adminhtml\AbstractCategory;

/**
 * Blog category grid controller
 */
class Grid extends AbstractCategory
{
    /**
     * Index action
     *
     */
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}

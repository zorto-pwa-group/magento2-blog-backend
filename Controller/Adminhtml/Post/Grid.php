<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Post;

use ZT\Blog\Controller\Adminhtml\AbstractPost;

/**
 * Blog post grid controller
 */
class Grid extends AbstractPost
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

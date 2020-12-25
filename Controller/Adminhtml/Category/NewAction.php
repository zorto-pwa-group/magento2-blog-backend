<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Category;

use ZT\Blog\Controller\Adminhtml\AbstractCategory;

/**
 * Blog category create new controller
 */
class NewAction extends AbstractCategory
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}

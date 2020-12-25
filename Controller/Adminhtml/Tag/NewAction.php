<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Tag;

use ZT\Blog\Controller\Adminhtml\AbstractTag;

/**
 * Blog tag create new controller
 */
class NewAction extends AbstractTag
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}

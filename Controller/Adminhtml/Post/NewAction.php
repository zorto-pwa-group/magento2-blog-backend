<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Post;

use ZT\Blog\Controller\Adminhtml\AbstractPost;

/**
 * Blog post create new controller
 */
class NewAction extends AbstractPost
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}

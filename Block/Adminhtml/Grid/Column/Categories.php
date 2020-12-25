<?php
/**
 * Copyright © 2019 ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Block\Adminhtml\Grid\Column;

use Magento\Backend\Block\Widget\Grid\Column;

/**
 * Admin blog grid categories
 */
class Categories extends Column
{
    /**
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_rendererTypes['category'] = 'ZT\Blog\Block\Adminhtml\Grid\Column\Render\Category';
        $this->_filterTypes['category'] = 'ZT\Blog\Block\Adminhtml\Grid\Column\Filter\Category';
    }
}

<?php
/**
 * Copyright © 2019 ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Block\Adminhtml\Grid\Column;

use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Framework\Model\AbstractModel;

/**
 * Admin blog grid statuses
 */
class Statuses extends Column
{
    /**
     * Add to column decorated status
     *
     * @return array
     */
    public function getFrameCallback()
    {
        return [$this, 'decorateStatus'];
    }

    /**
     * Decorate status column values
     * @param $value
     * @param $row
     * @return string
     */
    public function decorateStatus($value, $row)
    {
        if ($row->getIsActive() || $row->getStatus()) {
            if ($row->getStatus() == 2) {
                $cell = '<span class="grid-severity-minor"><span>' . $value . '</span></span>';
            } else {
                $cell = '<span class="grid-severity-notice"><span>' . $value . '</span></span>';
            }
        } else {
            $cell = '<span class="grid-severity-critical"><span>' . $value . '</span></span>';
        }
        return $cell;
    }
}

<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Model;

use Magento\Framework\Model\AbstractModel;
use ZT\Blog\Api\Data\TagInterface;

/**
 * @method getTitle()
 */
class Tag extends AbstractModel implements TagInterface
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'ztpwa_blog_tag';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'blog_tag';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->_getData(self::TAG_ID);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ZT\Blog\Model\ResourceModel\Tag');
    }
}

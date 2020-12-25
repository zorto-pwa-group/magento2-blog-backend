<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Model;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Model\AbstractModel;
use ZT\Blog\Api\Data\CategoryInterface;

/**
 * Category model
 *
 * @method ResourceModel\Category _getResource()
 * @method ResourceModel\Category getResource()
 * @method int getStoreId()
 * @method $this setStoreId(int $value)
 * @method string getTitle()
 * @method $this setTitle(string $value)
 * @method string getMetaKeywords()
 * @method $this setMetaKeywords(string $value)
 * @method $this setMetaDescription(string $value)
 * @method string getIdentifier()
 * @method $this setIdentifier(string $value)
 * @method getPath()
 */
class Category extends AbstractModel implements CategoryInterface
{

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'ztpwa_blog_category';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'blog_category';

    /**
     * Retrieve parent category
     * @return Category || false
     */
    public function getParentCategory()
    {
        $k = 'parent_category';
        if (null === $this->getData($k)) {
            $this->setData($k, false);
            if ($pId = $this->getParentId()) {
                $category = clone $this;
                $category->load($pId);
                if ($category->getId()) {
                    $this->setData($k, $category);
                }
            }
        }

        return $this->getData($k);
    }

    /**
     * Retrieve parent category id
     * @return int
     */
    public function getParentId()
    {
        $parentIds = $this->getParentIds();
        if ($parentIds) {
            return $parentIds[count($parentIds) - 1];
        }

        return 0;
    }

    /**
     * Retrieve parent category ids
     * @return array
     */
    public function getParentIds()
    {
        $k = 'parent_ids';
        if (!$this->hasData($k)) {
            $this->setData(
                $k,
                $this->getPath() ? explode('/', $this->getPath()) : []
            );
        }

        return $this->getData($k);
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->_getData(self::CATEGORY_ID);
    }

    /**
     * Retrieve children category ids
     * @param bool $grandchildren
     * @return array
     */
    public function getChildrenIds($grandchildren = true)
    {
        $k = 'children_ids';
        if (!$this->hasData($k)) {
            $categories = ObjectManager::getInstance()
                ->create($this->_collectionName);

            $allIds = $ids = [];
            /** @var Category $category */
            foreach ($categories as $category) {
                if ($category->isParent($this)) {
                    $allIds[] = $category->getId();
                    if ($category->getLevel() == $this->getLevel() + 1) {
                        $ids[] = $category->getId();
                    }
                }
            }

            $this->setData('all_' . $k, $allIds);
            $this->setData($k, $ids);
        }

        return $this->getData(
            ($grandchildren ? 'all_' : '') . $k
        );
    }

    /**
     * Check if current category is parent category
     * @param self $category
     * @return boolean
     */
    public function isParent($category)
    {
        if (is_object($category)) {
            $category = $category->getId();
        }

        return in_array($category, $this->getParentIds());
    }

    /**
     * Retrieve category depth level
     * @return int
     */
    public function getLevel()
    {
        return count($this->getParentIds());
    }

    /**
     * Check if current category is child category
     * @param self $category
     * @return boolean
     */
    public function isChild($category)
    {
        return $category->isParent($this);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ZT\Blog\Model\ResourceModel\Category');
    }
}

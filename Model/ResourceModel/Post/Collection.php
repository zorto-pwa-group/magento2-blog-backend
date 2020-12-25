<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Model\ResourceModel\Post;

use Magento\Framework\DataObject;
use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use ZT\Blog\Api\Data\PostInterface;
use ZT\Blog\Model\Category;
use ZT\Blog\Model\Tag;

/**
 * Blog post collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = PostInterface::POST_ID;

    /**
     * @var Category
     */
    protected $category;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ZT\Blog\Model\Post', 'ZT\Blog\Model\ResourceModel\Post');
        $this->_map['fields']['identifier'] = 'main_table.identifier';
        $this->_map['fields']['post_id'] = 'main_table.post_id';
        $this->_map['fields']['is_active'] = 'main_table.is_active';
        $this->_map['fields']['author'] = 'CONCAT(admin_user.firstname, " ", admin_user.lastname)';
    }

    /**
     * @param string $field
     * @param null $condition
     * @return AbstractCollection|Collection
     */

    public function addFieldToFilter($field, $condition = null)
    {
        if ($field[0] == 'category' ) {
            $this->_addFieldToFiltersBefore('category');
            return $this->addCategoryFilter($condition);
        }
        if ($field[0] == 'tag' ) {
            $this->_addFieldToFiltersBefore('tag');
            return $this->addTagFilter($condition);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add category filter to collection
     * @param array|int|Category  $category
     * @return Collection
     */
    public function addCategoryFilter($category)
    {
        $filter = $category[0]['eq'];
        if (!$this->getFlag('category_filter_added')) {
            if ($category instanceof Category) {

            } else {
                if(is_int($filter)){
                    $this->getSelect()->where('category.category_id = '.$filter);
                }
                if(is_string($filter)){
                    $this->getSelect()->where('category.identifier = ?', $filter);
                }
            }
        }
        $this->setFlag('category_filter_added', true);
        return $this;
    }

    /**
     * Add tag filter to collection
     * @param array|int|Tag  $tag
     * @return Collection
     */
    public function addTagFilter($tag)
    {
        $filter = $tag[0]['eq'];
        if (!$this->getFlag('tag_filter_added')) {
            if ($tag instanceof Category) {

            } else {
                if(is_int($filter)){
                    $this->getSelect()->where('tag.tag_id = '.$filter);
                }
                if(is_string($filter)){
                    $this->getSelect()->where('tag.identifier = ?', $filter);
                }
            }
        }
        $this->setFlag('tag_filter_added', true);
        return $this;
    }

    /**
     * Get SQL for get record count
     *
     * Extra GROUP BY strip added.
     *
     * @return Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Select::GROUP);

        return $countSelect;
    }

    /**
     * Join store relation table if there is store filter
     * @param string $key
     * @return void
     */
    protected function _addFieldToFiltersBefore($key)
    {
        $joinOptions = new DataObject;
        $joinOptions->setData([
            'key' => $key,
            'fields' => [],
        ]);
        $this->getSelect()->joinRight(
            [$key.'_post' => $this->getTable('ztpwa_blog_post_'.$key)],
            'main_table.post_id = '.$key.'_post.post_id',
            [$key.'_id']
        );
        $this->getSelect()->joinRight(
            [$key => $this->getTable('ztpwa_blog_'.$key)],
            $key.'_post.'.$key.'_id = '.$key.'.'.$key.'_id',
            []
        );

        $this->getSelect()->group(
            'main_table.post_id'
        );
    }

    /**
     * Get post author
     * @return AbstractCollection|void
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->joinLeft(
            ['admin_user' => $this->getTable('admin_user')],
            'main_table.author_id = admin_user.user_id',
            ['author' =>'CONCAT(firstname, " ", lastname)']
        );
    }
}

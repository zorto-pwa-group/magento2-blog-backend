<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Blog category resource model
 */
class Post extends AbstractDb
{

    const POST_CATEGORY_TABLE = 'ztpwa_blog_post_category';
    const POST_TAG_TABLE = 'ztpwa_blog_post_tag';
    const RELATED_POST_TABLE = 'ztpwa_blog_post_relatedpost';
    const RELATED_PRODUCT_TABLE = 'ztpwa_blog_post_relatedproduct';

    /**
     * Initialize resource model
     * Get table name from config
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ztpwa_blog_post', 'post_id');
    }

    /**
     * Assign post to categories, tags
     *
     * @param AbstractModel $object
     * @return AbstractModel
     */
    protected function _afterSave(AbstractModel $object)
    {
        /* Remove category & tag links */
        $this->_removeLinkedRecords($object, self::POST_CATEGORY_TABLE);
        $this->_removeLinkedRecords($object, self::POST_TAG_TABLE);
        if(!empty($object->getData('related_posts'))){
            $this->_removeLinkedRecords($object, self::RELATED_POST_TABLE);
        }
        if(!empty($object->getData('related_products'))){
            $this->_removeLinkedRecords($object, self::RELATED_PRODUCT_TABLE);
        }
        /* Add category & tag links */
        $linkedTypes = ['category' => 'categories', 'tag' => 'tags'];
        foreach ($linkedTypes as $linkType => $dataKey) {
            $newIds = (array)$object->getData($dataKey);
            foreach ($newIds as $key => $id) {
                if (!$id) {
                    unset($newIds[$key]);
                }
            }
            if (is_array($newIds)) {
                $this->_addLinkedRecords(
                    $object,
                    $newIds,
                    'ztpwa_blog_post_' . $linkType,
                    $linkType . '_id'
                );
            }
        }

        /* Add related posts & related products links */
        $linkedTypes = ['post' => 'related_posts', 'product' => 'related_products'];
        foreach ($linkedTypes as $linkType => $dataKey) {
            $newIds = (array)$object->getData($dataKey);
            foreach ($newIds as $key => $id) {
                if (!$id) {
                    unset($newIds[$key]);
                }
            }
            if (is_array($newIds)) {
                $this->_addLinkedRecords(
                    $object,
                    $newIds,
                    'ztpwa_blog_post_related' . $linkType,
                    'related_id'
                );
            }
        }
        return parent::_afterSave($object);
    }

    /**
     * @param AbstractModel $object
     * @param $tableName
     */
    protected function _removeLinkedRecords(
        AbstractModel $object,
        $tableName
    ) {
        $table = $this->getTable($tableName);
        $where = ['post_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($table, $where);
    }

    /**
     * Update post connections
     * @param AbstractModel $object
     * @param array $insert
     * @param $tableName
     * @param $linkType
     */
    protected function _addLinkedRecords(
        AbstractModel $object,
        array $insert,
        $tableName,
        $linkType
    ) {
        $table = $this->getTable($tableName);
        $data = [];
        foreach ($insert as $value) {
            $data[] = ['post_id' => (int)$object->getId(), $linkType => $value];
        }
        if(!empty($data)) {
            $this->getConnection()->insertMultiple($table, $data);
        }
    }

    /**
     * Perform operations after object load
     *
     * @param AbstractModel $object
     * @return AbstractModel
     */
    protected function _afterLoad(AbstractModel $object)
    {
        if ($object->getId()) {
            $categories = $this->lookupCategoryIds($object->getId());
            $object->setCategories($categories);

            $tags = $this->lookupTagIds($object->getId());
            $object->setTags($tags);
            $author = $this->_getAuthor($object->getAuthorId());
            $object->setAuthor($author);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Get post author
     * @param  int $authorId
     * @return array
     */
    protected function _getAuthor($authorId)
    {
        $adapter = $this->getConnection();

        $select = $adapter->select()->from(
            $this->getTable('admin_user'),
            'CONCAT(admin_user.firstname, " ", admin_user.lastname)'
        )->where(
            'user_id = ?',
            (int)$authorId
        );
        $authors = $adapter->fetchCol($select);
        return $authors[0];
    }

    /**
     * Get ids to which specified item is assigned
     * @param  int $postId
     * @param  string $tableName
     * @param  string $field
     * @return array
     */
    protected function _lookupIds($postId, $tableName, $field)
    {
        $adapter = $this->getConnection();

        $select = $adapter->select()->from(
            $this->getTable($tableName),
            $field
        )->where(
            'post_id = ?',
            (int)$postId
        );

        return $adapter->fetchCol($select);
    }

    /**
     * Get category ids to which specified item is assigned
     *
     * @param int $postId
     * @return array
     */
    public function lookupCategoryIds($postId)
    {
        return $this->_lookupIds($postId, 'ztpwa_blog_post_category', 'category_id');
    }

    /**
     * Get tag ids to which specified item is assigned
     *
     * @param int $postId
     * @return array
     */
    public function lookupTagIds($postId)
    {
        return $this->_lookupIds($postId, 'ztpwa_blog_post_tag', 'tag_id');
    }
}

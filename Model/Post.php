<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use ZT\Blog\Api\Data\PostInterface;
use ZT\Blog\Model\ResourceModel\Category\Collection;
use ZT\Blog\Model\ResourceModel\Post\Collection as PostCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;


/**
 * @method getAuthorId()
 * @method setAuthorId($getId)
 * @method getCreationTime()
 * @method setUpdateTime(string $todayDate)
 * @method setCreationTime(string $todayDate)
 * @method getPublishTime()
 * @method setPublishTime(string $todayDate)
 */
class Post extends AbstractModel implements PostInterface
{

    /**
     * Base media folder path
     */
    const BASE_MEDIA_PATH = 'pwa_blog';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'ztpwa_blog_post';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'blog_post';

    /**
     * @var PostCollection
     */
    protected $_relatedPostsCollection;

    /**
     * @var ProductCollectionFactory
     */
    protected $_productCollectionFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        ProductCollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_relatedPostsCollection = clone($this->getCollection());
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ZT\Blog\Model\ResourceModel\Post');
    }

    /**
     * Get related posts of current post
     * @return array
     */
    public function getRelatedPosts()
    {
        if (!$this->hasData('related_posts')) {
            $collection = $this->_relatedPostsCollection
                ->addFieldToFilter('post_id', ['neq' => $this->getId()]);
            $collection->getSelect()->joinLeft(
                ['rl' => $this->getResource()->getTable('ztpwa_blog_post_relatedpost')],
                'main_table.post_id = rl.related_id',
                ['related_id']
            )->where(
                'rl.post_id = ?',
                $this->getId()
            );
            $this->setData('related_posts', $collection);
        }
        return $this->getData('related_posts');
    }

    /**
     * Get related products of current post
     * @return array ['related_products' => object]
     */
    public function getRelatedProducts()
    {
        if (!$this->hasData('related_products')) {
            $collection = $this->_productCollectionFactory->create();
            $collection->getSelect()->joinLeft(
                ['rl' => $this->getResource()->getTable('ztpwa_blog_post_relatedproduct')],
                'e.entity_id = rl.related_id',
                ['related_id']
            )->where(
                'rl.post_id = ?',
                $this->getId()
            );
            $this->setData('related_products', $collection);
        }
        return $this->getData('related_products');
    }

    /**
     * Retrieve post parent categories
     * @return Collection
     */
//    public function getParentCategories()
//    {
//        if ($this->_parentCategories === null) {
//            $this->_parentCategories = $this->_categoryCollectionFactory->create()
//                ->addFieldToFilter('category_id', ['in' => $this->getCategories()])
//                ->addStoreFilter($this->getStoreId())
//                ->addActiveFilter()
//                ->setOrder('position');
//        }
//        return $this->_parentCategories;
//    }

    /**
     * Retrieve parent category
     * @return Category || false
     */
//    public function getParentCategory()
//    {
//        $k = 'parent_category';
//        if (null === $this->getData($k)) {
//            $this->setData($k, false);
//            foreach ($this->getParentCategories() as $category) {
//                if ($category->isVisibleOnStore($this->getStoreId())) {
//                    $this->setData($k, $category);
//                    break;
//                }
//            }
//        }
//
//        return $this->getData($k);
//    }

    /**
     * Retrieve post parent categories count
     * @return int
     */
//    public function getCategoriesCount()
//    {
//        return count($this->getParentCategories());
//    }

}

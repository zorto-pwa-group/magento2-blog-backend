<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Model;

use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use ZT\Blog\Api\CategoryRepositoryInterface;
use ZT\Blog\Api\Data\CategoryInterface;
use ZT\Blog\Model\ResourceModel\Category as ResourceCategory;
use ZT\Blog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Api\SearchResultsFactory;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @var ResourceBlock|ResourceCategory
     */
    protected $_resourceCategory;
    /**
     * @var CategoryFactory|CategoryFactory
     */
    protected $_categoryFactory;
    /**
     * @var CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var SearchResultsFactory
     */
    private $searchResultsFactory;

    /**
     * CategoryRepository constructor.
     * @param ResourceCategory $resource
     * @param CategoryFactory $categoryFactory
     * @param CollectionFactory $categoryCollectionFactory
     * @param SearchResultsFactory $searchResultsFactory
     */
    public function __construct(
        ResourceCategory $resource,
        CategoryFactory $categoryFactory,
        CollectionFactory $categoryCollectionFactory,
        SearchResultsFactory $searchResultsFactory
    ) {
        $this->_resourceCategory = $resource;
        $this->_categoryFactory = $categoryFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Save Category data
     *
     * @param CategoryInterface $category
     * @return Category
     * @throws CouldNotSaveException
     */
    public function save(CategoryInterface $category)
    {
        try {
            $this->_resourceCategory->save($category);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $category;
    }

    /**
     * Delete Category by given Category Identity
     *
     * @param string $categoryId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($categoryId)
    {
        return $this->delete($this->getById($categoryId));
    }

    /**
     * Delete Category
     *
     * @param CategoryInterface $category
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CategoryInterface $category)
    {
        try {
            $this->_resourceCategory->delete($category);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Load Category data by given Category Identity
     *
     * @param string $categoryId
     * @return Category
     * @throws NoSuchEntityException
     */
    public function getById($categoryId)
    {
        $category = $this->_categoryFactory->create();
        $this->_resourceCategory->load($category, $categoryId);
        if (!$category->getId()) {
            throw new NoSuchEntityException(__('Category with id "%1" does not exist.', $categoryId));
        }
        return $category;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->categoryCollectionFactory->create();

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        /** @var searchResultsInterface $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setItems($collection->getData());

        return $searchResult;
    }

    /**
     * Load Category data by given Category Identifier
     *
     * @param string $cateId
     * @return CategoryInterface
     * @throws NoSuchEntityException
     */
    public function getByIdentifier($cateId)
    {
        $cate = $this->_categoryFactory->create();
        $this->_resourceCategory->load($cate, $cateId, 'identifier');
        if (!$cate->getId()) {
            throw new NoSuchEntityException(__('Category with identifier "%1" does not exist.', $cateId));
        }
        return $cate;
    }
}

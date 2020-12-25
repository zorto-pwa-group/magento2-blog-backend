<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use ZT\Blog\Api\Data\CategoryInterface;

interface CategoryRepositoryInterface
{
    /**
     * Save category.
     *
     * @param CategoryInterface $category
     * @return CategoryInterface
     * @throws LocalizedException
     */
    public function save(CategoryInterface $category);

    /**
     * Retrieve category.
     *
     * @param int $categoryId
     * @return CategoryInterface
     * @throws LocalizedException
     */
    public function getById($categoryId);

    /**
     * Delete category.
     *
     * @param CategoryInterface $category
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(CategoryInterface $category);

    /**
     * Delete category by ID.
     *
     * @param int $categoryId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($categoryId);

    /**
     * Retrieve Post matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResults
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}

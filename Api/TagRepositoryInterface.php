<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use ZT\Blog\Api\Data\TagInterface;

interface TagRepositoryInterface
{
    /**
     * Save tag.
     *
     * @param TagInterface $tag
     * @return TagInterface
     * @throws LocalizedException
     */
    public function save(TagInterface $tag);

    /**
     * Retrieve tag.
     *
     * @param int $tagId
     * @return TagInterface
     * @throws LocalizedException
     */
    public function getById($tagId);

    /**
     * Delete tag.
     *
     * @param TagInterface $tag
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(TagInterface $tag);

    /**
     * Delete tag by ID.
     *
     * @param int $tagId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($tagId);
}

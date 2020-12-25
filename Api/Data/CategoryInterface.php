<?php
/**
 * Copyright ZT. All rights reserved..
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace ZT\Blog\Api\Data;

/**
 * Blog Category interface.
 * @api
 * @since 100.0.2
 */
interface CategoryInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const CATEGORY_ID = 'category_id';
    const IDENTIFIER = 'identifier';
    const TITLE = 'title';
    const CONTENT = 'content';
    const IS_ACTIVE = 'is_active';
    const POSITION = 'position';
    const PATH = 'path';
    /**#@-*/

    /**
     * Category's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Because this module does not use REST or SOAP API,
     * so setter & getter functions in API Data Interface is not needed.
     *
     */

    /**
     * Retrieve parent category ids
     * @return array
     */
    public function getParentIds();

    /**
     * Retrieve parent category id
     * @return array
     */
    public function getParentId();
}

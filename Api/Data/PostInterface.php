<?php
/**
 * Copyright ZT. All rights reserved..
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace ZT\Blog\Api\Data;

/**
 * Blog Post interface.
 * @api
 * @since 100.0.2
 */
interface PostInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const POST_ID = 'post_id';
    const IDENTIFIER = 'identifier';
    const TITLE = 'title';
    const META_TITLE = 'meta_title';
    const META_KEYWORDS = 'meta_keywords';
    const META_DESCRIPTION = 'meta_description';
    const CONTENT = 'content';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME = 'update_time';
    const PUBLISH_TIME = 'publish_time';
    const IS_ACTIVE = 'is_active';
    const POSITION = 'position';
    const SHORT_CONTENT = 'short_content';
    const FEATURED_IMG = 'featured_img';
    /**#@-*/

    /**
     * Post's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
}

<?php
/**
 * Copyright ZT. All rights reserved..
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace ZT\Blog\Api\Data;

/**
 * Blog Tag interface.
 * @api
 * @since 100.0.2
 */
interface TagInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const TAG_ID = 'tag_id';
    const POST_ID = 'post_id';
    const IDENTIFIER = 'identifier';
    const TITLE = 'title';
    const META_TITLE = 'meta_title';
    const META_KEYWORDS = 'meta_keywords';
    const META_DESCRIPTION = 'meta_description';
    const CONTENT = 'content';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Because this module does not use REST or SOAP API,
     * so setter & getter functions in API Data Interface is not needed.
     */
}

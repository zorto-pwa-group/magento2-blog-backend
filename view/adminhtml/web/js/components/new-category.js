/**
 * Copyright © 2019 ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

define([
    'ZT_Blog/js/components/new-tag'
], function (Select) {
    'use strict';

    return Select.extend({

        /**
         * Normalize option object.
         *
         * @param {Object} data - Option object.
         * @returns {Object}
         */
        parseData: function (data) {
            return {
                'is_active': data.model['is_active'],
                level: data.model['level'],
                value: data.model['category_id'],
                label: data.model['title'],
                parent: data.model['parent_id']
            };
        }
    });
});

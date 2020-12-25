/**
 * Copyright © 2019 ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

define([
    'Magento_Ui/js/form/element/abstract'
], function (AbstractElement) {
    'use strict';

    return AbstractElement.extend({
        defaults: {
            elementTmpl: 'ZT_Blog/form/element/link'
        },

        initialize: function () {
            this._super();

            var value = this.value();
            this.url = value.url;
            this.title = value.title;
            this.text = value.text;
        },

    });
});
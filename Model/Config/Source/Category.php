<?php
/**
 * Copyright © 2019 ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace ZT\Blog\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use ZT\Blog\Model\ResourceModel\Category\CollectionFactory;

/**
 * Used in recent post widget
 *
 */
class Category implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var array
     */
    protected $options;

    /**
     * Initialize dependencies.
     *
     * @param CollectionFactory $authorCollectionFactory
     * @param void
     */
    public function __construct(
        CollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this->toOptionArray() as $item) {
            $array[$item['value']] = $item['label'];
        }
        return $array;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [['label' => __('Please select'), 'value' => 0]];
            $collection = $this->categoryCollectionFactory->create();
            $collection->setOrder('position')
                ->getTreeOrderedArray();

            foreach ($collection as $item) {
                $this->options[] = [
                    'label' => $this->_getSpaces($item->getLevel()) . ' ' . $item->getTitle() .
                        ($item->getIsActive() ? '' : ' (' . __('Disabled') . ')'),
                    'value' => $item->getId(),
                ];
            }
        }

        return $this->options;
    }

    /**
     * Generate spaces
     * @param int $n
     * @return string
     */
    protected function _getSpaces($n)
    {
        $s = '';
        for ($i = 0; $i < $n; $i++) {
            $s .= '--- ';
        }

        return $s;
    }
}

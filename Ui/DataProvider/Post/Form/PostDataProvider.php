<?php
/**
 * Copyright © 2019 ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Ui\DataProvider\Post\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use ZT\Blog\Model\Post;
use ZT\Blog\Model\ResourceModel\Post\Collection;
use ZT\Blog\Model\ResourceModel\Post\CollectionFactory;

/**
 * Class DataProvider
 */
class PostDataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $postCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $postCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $postCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currentStore = $storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $items = $this->collection->getItems();
        /** @var $post Post */
        foreach ($items as $post) {
            $post = $post->load($post->getId());
            $data = $post->getData();

            /* Prepare Featured Image */
            $map = [
                'featured_img' => 'getFeaturedImg'
            ];
            foreach ($map as $key => $method) {
                if (isset($data[$key])) {
                    $name = $data[$key];
                    unset($data[$key]);
                    $data[$key][0] = [
                        'name' => $name,
                        'url' => $mediaUrl . '/'.$post->$method()
                    ];
                }
            }

            $data['data'] = ['links' => []];
            $items = $item2s = [];
            /* Prepare related posts */
            $collection = $post->getRelatedPosts();
            foreach ($collection as $item) {
                $itemData = $item->getData();
                $itemData['id'] = $item->getId();
                /* Fix for big request data array */
                foreach (['content', 'short_content', 'meta_description'] as $field) {
                    if (isset($itemData[$field])) {
                        unset($itemData[$field]);
                    }
                }
                /* End */
                $items[] = $itemData;
            }
            $data['data']['links']['post'] = $items;

            /* Prepare related products */
            $productRelated = $post->getRelatedProducts()
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('name');
            foreach ($productRelated as $item2) {
                $itemData = $item2->getData();
                $itemData['id'] = $item2->getId();
                /* Fix for big request data array */
                foreach (['content', 'short_content', 'meta_description'] as $field) {
                    if (isset($itemData[$field])) {
                        unset($itemData[$field]);
                    }
                }
                /* End */
                $item2s[] = $itemData;
            }
            $data['data']['links']['product'] = $item2s;
            /* Set data */
            $this->loadedData[$post->getId()] = $data;
        }

        $data = $this->dataPersistor->get('blog_post_form_data');
        if (!empty($data)) {
            $post = $this->collection->getNewEmptyItem();
            $post->setData($data);
            $this->loadedData[$post->getId()] = $post->getData();
            $this->dataPersistor->clear('blog_post_form_data');
        }

        return $this->loadedData;
    }
}

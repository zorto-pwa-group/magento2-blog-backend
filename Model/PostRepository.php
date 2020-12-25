<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Model;

use Exception;
use ZT\Blog\Model\ResourceModel\Post\Collection;
use Magento\Framework\Api\searchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use ZT\Blog\Api\Data\PostInterface;
use ZT\Blog\Api\PostRepositoryInterface;
use ZT\Blog\Model\ResourceModel\Post as ResourcePost;
use ZT\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

class PostRepository implements PostRepositoryInterface
{
    /**
     * @var ResourcePost
     */
    protected $_resourcePost;
    /**
     * @var PostFactory
     */
    protected $_postFactory;
    /**
     * @var CollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * @var SearchResultsFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * PostRepository constructor.
     * @param ResourcePost $resource
     * @param PostFactory $postFactory
     * @param CollectionFactory $postCollectionFactory
     * @param SearchResultsFactory $searchResultsFactory
     */
    public function __construct(
        ResourcePost $resource,
        PostFactory $postFactory,
        CollectionFactory $postCollectionFactory,
        SearchResultsFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->_resourcePost = $resource;
        $this->_postFactory = $postFactory;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Save Post data
     *
     * @param PostInterface $post
     * @return PostInterface
     * @throws CouldNotSaveException
     */
    public function save(PostInterface $post)
    {
        try {
            $this->_resourcePost->save($post);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $post;
    }

    /**
     * Delete Post by given Post Identity
     *
     * @param string $postId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($postId)
    {
        return $this->delete($this->getById($postId));
    }

    /**
     * Delete Post
     *
     * @param PostInterface $post
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(PostInterface $post)
    {
        try {
            $this->_resourcePost->delete($post);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Load Post data by given Post Identity
     *
     * @param string $postId
     * @return PostInterface
     * @throws NoSuchEntityException
     */
    public function getById($postId)
    {
        $post = $this->_postFactory->create();
        $this->_resourcePost->load($post, $postId);
        if (!$post->getId()) {
            throw new NoSuchEntityException(__('Post with id "%1" does not exist.', $postId));
        }
        return $post;
    }

    /**
     * Load Post data by given Post Identifier
     *
     * @param string $postId
     * @return PostInterface
     * @throws NoSuchEntityException
     */
    public function getByIdentifier($postId)
    {
        $post = $this->_postFactory->create();
        $this->_resourcePost->load($post, $postId, 'identifier');
        if (!$post->getId()) {
            throw new NoSuchEntityException(__('Post with identifier "%1" does not exist.', $postId));
        }
        return $post;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->postCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);
        /** @var searchResultsInterface $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setItems($collection->getData());

        return $searchResult;
    }

    /**
     * Retrieve collection processor
     *
     * @deprecated 102.0.0
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Magento\Cms\Model\Api\SearchCriteria\PageCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}

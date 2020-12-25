<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Model;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use ZT\Blog\Api\Data\TagInterface;
use ZT\Blog\Api\TagRepositoryInterface;
use ZT\Blog\Model\ResourceModel\Tag as ResourceTag;
use ZT\Blog\Model\ResourceModel\Tag\CollectionFactory;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

class TagRepository implements TagRepositoryInterface
{
    /**
     * @var ResourceTag
     */
    protected $_resourceTag;
    /**
     * @var TagFactory
     */
    protected $_tagFactory;
    /**
     * @var CollectionFactory
     */
    protected $tagCollectionFactory;

    /**
     * @var SearchResultsFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * TagRepository constructor.
     * @param ResourceTag $resource
     * @param TagFactory $tagFactory
     * @param CollectionFactory $tagCollectionFactory
     * @param SearchResultsFactory $searchResultsFactory
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        ResourceTag $resource,
        TagFactory $tagFactory,
        CollectionFactory $tagCollectionFactory,
        SearchResultsFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->_resourceTag = $resource;
        $this->_tagFactory = $tagFactory;
        $this->tagCollectionFactory = $tagCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Save Tag data
     *
     * @param TagInterface $tag
     * @return Tag
     * @throws CouldNotSaveException
     */
    public function save(TagInterface $tag)
    {
        try {
            $this->_resourceTag->save($tag);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $tag;
    }

    /**
     * Delete Tag by given Tag Identity
     *
     * @param string $tagId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($tagId)
    {
        return $this->delete($this->getById($tagId));
    }

    /**
     * Delete Tag
     *
     * @param TagInterface $tag
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(TagInterface $tag)
    {
        try {
            $this->_resourceTag->delete($tag);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Load Tag data by given Tag Identity
     *
     * @param string $tagId
     * @return Tag
     * @throws NoSuchEntityException
     */
    public function getById($tagId)
    {
        $tag = $this->_tagFactory->create();
        $this->_resourceTag->load($tag, $tagId);
        if (!$tag->getId()) {
            throw new NoSuchEntityException(__('Tag with id "%1" does not exist.', $tagId));
        }
        return $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->tagCollectionFactory->create();

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

    /**
     * Load Tag data by given Tag Identifier
     *
     * @param string $tagId
     * @return TagInterface
     * @throws NoSuchEntityException
     */
    public function getByIdentifier($tagId)
    {
        $tag = $this->_tagFactory->create();
        $this->_resourceTag->load($tag, $tagId, 'identifier');
        if (!$tag->getId()) {
            throw new NoSuchEntityException(__('Tag with identifier "%1" does not exist.', $tagId));
        }
        return $tag;
    }
}

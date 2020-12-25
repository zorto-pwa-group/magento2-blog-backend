<?php
namespace ZT\Blog\Ui\DataProvider\Post\Related;

use Magento\Ui\DataProvider\AbstractDataProvider;
use ZT\Blog\Model\ResourceModel\Post\Collection;
use ZT\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;

/**
 * Class PostDataProvider get post data
 */
class PostDataProvider extends AbstractDataProvider
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var post
     */
    private $post;

    /**
     * @var Registry
     */

    protected $_registry;

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        Registry $registry,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->_registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        /** @var Collection $collection */
        $collection = parent::getCollection();

        if (!$this->getPost()) {
            return $collection;
        }
        $collection->addFieldToFilter(
            $collection->getIdFieldName(),
            ['nin' => [$this->getPost()->getId()]]
        );

        return $this->addCollectionFilters($collection);
    }

    /**
     * Retrieve post
     *
     * @return PostInterface|null
     */
    protected function getPost()
    {
        if (null !== $this->post) {
            return $this->post;
        }

        $this->post = $this->_registry->registry('blog_post');
        if (!$this->post) {
            return null;
        }

        return $this->post;
    }
}

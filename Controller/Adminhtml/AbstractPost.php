<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use ZT\Blog\Api\PostRepositoryInterface;
use ZT\Blog\Model\PostFactory;

/**
 * Abstract admin controller
 */
abstract class AbstractPost extends Action
{
    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var DataPersistorInterface
     */
    protected $_dataPersistor;

    /**
     * @var PostFactory
     */
    protected $_postFactory;

    /**
     * @var PostRepositoryInterface
     */
    protected $_postRepository;

    /**
     * @var TimezoneInterface
     */
    protected $_timezone;

    /**
     * AbstractPost constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param LayoutFactory $resultLayoutFactory
     * @param ForwardFactory $resultForwardFactory
     * @param Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     * @param PostFactory|null $postFactory
     * @param PostRepositoryInterface|null $postRepository
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        LayoutFactory $resultLayoutFactory,
        ForwardFactory $resultForwardFactory,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        PostFactory $postFactory = null,
        PostRepositoryInterface $postRepository = null,
        TimezoneInterface $timezone
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_dataPersistor = $dataPersistor;
        $this->_postFactory = $postFactory
            ?: ObjectManager::getInstance()->get(PostFactory::class);
        $this->_postRepository = $postRepository
            ?: ObjectManager::getInstance()
                ->get(PostRepositoryInterface::class);
        $this->_timezone = $timezone;
        parent::__construct($context);
    }
}

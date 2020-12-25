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
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use ZT\Blog\Api\CategoryRepositoryInterface;
use ZT\Blog\Model\CategoryFactory;

/**
 * Abstract admin controller
 */
abstract class AbstractCategory extends Action
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
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $_categoryRepository;

    /**
     * AbstractCategory constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param LayoutFactory $resultLayoutFactory
     * @param ForwardFactory $resultForwardFactory
     * @param Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     * @param CategoryFactory|null $categoryFactory
     * @param CategoryRepositoryInterface|null $categoryRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        LayoutFactory $resultLayoutFactory,
        ForwardFactory $resultForwardFactory,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        CategoryFactory $categoryFactory = null,
        CategoryRepositoryInterface $categoryRepository = null
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_dataPersistor = $dataPersistor;
        $this->_categoryFactory = $categoryFactory
            ?: ObjectManager::getInstance()->get(CategoryFactory::class);
        $this->_categoryRepository = $categoryRepository
            ?: ObjectManager::getInstance()
                ->get(CategoryRepositoryInterface::class);
        parent::__construct($context);
    }
}

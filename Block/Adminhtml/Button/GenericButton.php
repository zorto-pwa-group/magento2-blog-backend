<?php

namespace ZT\Blog\Block\Adminhtml\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\AuthorizationInterface;

class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var AuthorizationInterface
     */
    protected $authorization;

    /**
     * GenericButton constructor.
     * @param Context $context
     * @param AuthorizationInterface|null $authorization
     */
    public function __construct(
        Context $context,
        $authorization = null
    ) {
        $this->context = $context;
        $this->authorization = $authorization
            ?: ObjectManager::getInstance()->get(
                AuthorizationInterface::class
            );
    }

    /**
     * Return CMS block ID
     *
     * @return int|null
     */
    public function getObjectId()
    {
        return $this->context->getRequest()->getParam('id');
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}

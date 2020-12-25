<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Upload\Image;

use Exception;
use Magento\Catalog\Controller\Adminhtml\Category\Image\Upload;
use Magento\Framework\Controller\ResultFactory;

/**
 * Blog image upload controller
 */
abstract class Action extends Upload
{
    /**
     * File key
     *
     * @var string
     */
    protected $_fileKey;

    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir($this->_fileKey);

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}

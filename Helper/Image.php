<?php

namespace ZT\Blog\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\Factory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Blog image helper
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Image extends AbstractHelper
{
    /**
     * Default quality value (for JPEG images only).
     *
     * @var int
     */
    protected $_quality = 100;
    protected $_keepAspectRatio = true;
    protected $_keepFrame = true;
    protected $_keepTransparency = true;
    protected $_constrainOnly = true;
    protected $_backgroundColor = [255, 255, 255];
    protected $_baseFile;
    protected $_newFile;

    protected $_imageFactory;
    protected $_mediaDirectory;
    protected $_storeManager;

    public function __construct(
        Context $context,
        Factory $imageFactory,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager
    ) {
        $this->_imageFactory = $imageFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @param string $baseFile
     * @return $this
     */
    public function init($baseFile)
    {
        $this->_newFile = '';
        $this->_baseFile = $baseFile;
        return $this;
    }

    /**
     * Resize featured_img of blog post
     */
    public function resizeFeaturedImg(){
        $sizes = $this->getSize();
        foreach ($sizes as $size){
            if(!empty($size[0]) && !empty($size[1])){
                $this->resize($size[0], $size[1]);
            }
        }
    }

    /**
     * @return array
     */
    public function getSize(){
        return [];
    }

    /**
     * @param int $width
     * @param int|null $height
     * @return $this
     */
    protected function resize($width, $height = null)
    {
        if ($this->_baseFile) {
            $path = $width . 'x' . $height . '/cache/';
            $this->_newFile = $path . '/' . $this->_baseFile;
            if (!$this->fileExists($this->_newFile)) {
                $this->resizeBaseFile($width, $height);
            }
        }
        return $this;
    }

    /**
     * @param string $filename
     * @return bool
     */
    protected function fileExists($filename)
    {
        return $this->_mediaDirectory->isFile($filename);
    }

    /**
     * @param int $width
     * @param int $height
     * @return $this
     */
    protected function resizeBaseFile($width, $height)
    {
        if (!$this->fileExists($this->_baseFile)) {
            $this->_baseFile = null;
            return $this;
        }

        $processor = $this->_imageFactory->create(
            $this->_mediaDirectory->getAbsolutePath($this->_baseFile)
        );
        $processor->keepAspectRatio($this->_keepAspectRatio);
        $processor->keepFrame($this->_keepFrame);
        $processor->keepTransparency($this->_keepTransparency);
        $processor->constrainOnly($this->_constrainOnly);
        $processor->backgroundColor($this->_backgroundColor);
        $processor->quality($this->_quality);
        $processor->resize($width, $height);

        $newFile = $this->_mediaDirectory->getAbsolutePath($this->_newFile);
        $processor->save($newFile);
        unset($processor);

        return $this;
    }
}

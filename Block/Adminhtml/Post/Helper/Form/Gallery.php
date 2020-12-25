<?php
/**
 * Copyright © 2019 ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

/**
 * Blog post gallery
 */

namespace ZT\Blog\Block\Adminhtml\Post\Helper\Form;

use Magento\Framework\Data\Form;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Context;
use ZT\Blog\Block\Adminhtml\Post\Helper\Form\Gallery\Content;

class Gallery extends AbstractBlock
{
    /**
     * Gallery field name suffix
     *
     * @var string
     */
    protected $fieldNameSuffix = 'post';

    /**
     * Gallery html id
     *
     * @var string
     */
    protected $htmlId = 'media_gallery';

    /**
     * Gallery name
     *
     * @var string
     */
    protected $name = 'media_gallery';

    /**
     * Html id for data scope
     *
     * @var string
     */
    protected $image = 'image';

    /**
     * @var string
     */
    protected $formName = 'blog_post_form';

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param Form $form
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Form $form,
        $data = []
    ) {
        $this->registry = $registry;
        $this->form = $form;
        parent::__construct($context, $data);
    }

    /**
     * Get product images
     *
     * @return array|null
     */
    public function getImages()
    {
        $result = [];
        if (!$this->registry->registry('current_model')) {
            return [];
        }
        $gallery = $this->registry->registry('current_model')->getGalleryImages();

        if (count($gallery)) {
            $result['images'] = [];
            $position = 1;
            foreach ($gallery as $image) {
                $result['images'][] = [
                    'value_id' => $image->getFile(),
                    'file' => $image->getFile(),
                    'label' => basename($image->getFile()),
                    'position' => $position,
                    'url' => $image->getUrl(),
                ];
                $position++;
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFieldNameSuffix()
    {
        return $this->fieldNameSuffix;
    }

    /**
     * @return string
     */
    public function getDataScopeHtmlId()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return $this->getElementHtml();
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        return $this->getContentHtml();
    }

    /**
     * Prepares content block
     *
     * @return string
     */
    public function getContentHtml()
    {
        $content = $this->getChildBlock('content');
        if (!$content) {
            $content = $this->getLayout()->createBlock(
                Content::class,
                '',
                [
                    'config' => [
                        'parentComponent' => 'blog_post_form.blog_post_form.block_gallery.block_gallery'
                    ]
                ]
            );
        }

        $content
            ->setId($this->getHtmlId() . '_content')
            ->setElement($this)
            ->setFormName($this->formName);
        $galleryJs = $content->getJsObjectName();
        $content->getUploader()->getConfig()->setMegiaGallery($galleryJs);
        return $content->toHtml();
    }

    /**
     * @return string
     */
    protected function getHtmlId()
    {
        return $this->htmlId;
    }
}

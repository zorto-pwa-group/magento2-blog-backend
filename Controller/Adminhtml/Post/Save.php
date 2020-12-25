<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Controller\Adminhtml\Post;

use Exception;
use Magento\Backend\Model\Auth\Session;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use ZT\Blog\Controller\Adminhtml\AbstractPost;
use ZT\Blog\Model\Post;
use ZT\Blog\Helper\Data;

/**
 * Blog post save controller
 */
class Save extends AbstractPost implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'ZT_Blog::post_save';

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data['post_id']) && empty($data['post_id'])) {
                $data['post_id'] = null;
            }

            /** @var Post $model */
            $model = $this->_postFactory->create();

            $id = $this->getRequest()->getParam('post_id');
            if ($id) {
                try {
                    $model = $this->_postRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This blog post no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            $hasError = false;
            $model->setData($data);
            try {
                $this->_prepareSave($model);
                $this->_postRepository->save($model);
                $id = !empty($id) ? $id : $model->getPostId();
                $this->messageManager->addSuccessMessage(__('You saved the post.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
                }
            } catch (LocalizedException $e) {
                $hasError = true;
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (Exception $e) {
                $hasError = true;
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the blog post.'));
            }
            if ($hasError) {
                $this->_dataPersistor->set('pwablog_post', $data);
                if ($id) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
                }
                return $resultRedirect->setPath('*/*/new');
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Prepare data before model save
     * Set current admin as author
     * Set featured image
     * Set creation time
     * Set update time
     * Set publish time
     * @param  Post $model
     * @return void
     */
    protected function _prepareSave($model)
    {
        $data = $model->getData();
        /* Prepare author */
        if (!$model->getAuthorId()) {
            $authSession = $this->_objectManager->get(Session::class);
            $model->setAuthorId($authSession->getUser()->getId());
        }

        $todayDate = $this->_timezone->date()->format('Y-m-d H:i:s');

        /* Prepare update time */
        if ($model->getCreationTime()) {
            $model->setUpdateTime($todayDate);
        }

        /* Prepare creation time */
        if (!$model->getCreationTime()) {
            $model->setCreationTime($todayDate);
        }

        /* Prepare publish date */
        if (!$model->getPublishTime()) {
            $model->setPublishTime($todayDate);
        }
        /* Prepare URL key from title */
        if (!$model->getIdentifier()) {
            $title = $model->getTitle();
            $urlKey = $this->_objectManager->create(Data::class)->urlKeyGeneration($title);
            $model->setIdentifier($urlKey);
        }

        /* Prepare related post */
        if (isset($data['blog_related_post_listing']) && is_array($data['blog_related_post_listing'])) {
            $relatedPosts = [];
            foreach ($data['blog_related_post_listing'] as $related_post){
                $relatedPosts[] = $related_post['post_id'];
            }
            $model->setData('related_posts', $relatedPosts);
        }

        /* Prepare related product */
        if (isset($data['blog_related_product_listing']) && is_array($data['blog_related_product_listing'])) {
            $relatedProducts = [];
            foreach ($data['blog_related_product_listing'] as $related_product){
                $relatedProducts[] = $related_product['entity_id'];
            }
            $model->setData('related_products', $relatedProducts);
        }

        /* Prepare images */
        if (isset($data['featured_img']) && is_array($data['featured_img'])) {
            if (!empty($data['featured_img']['delete'])) {
                $model->setData('featured_img', null);
            } else {
                if (isset($data['featured_img'][0]['name']) && isset($data['featured_img'][0]['tmp_name'])) {
                    $image = $data['featured_img'][0]['name'];
                    $imageUploader = $this->_objectManager->get(
                        \ZT\Blog\ImageUpload::class
                    );
                    $image = $imageUploader->moveFileFromTmp($image);
                    $model->setData('featured_img', Post::BASE_MEDIA_PATH . '/' . $image);
                    $blogImageHelper = $this->_objectManager->get(
                        \ZT\Blog\Helper\Image::class
                    );
                    $blogImageHelper->init($model->getFeaturedImg())->resizeFeaturedImg();
                } else {
                    if (isset($data['featured_img'][0]['name'])) {
                        $model->setData('featured_img', $data['featured_img'][0]['name']);
                    }
                }
            }
        } else {
            $model->setData('featured_img', null);
        }
    }
}

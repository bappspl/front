<?php

namespace Product\Service;

use CmsIr\Category\Model\Category;
use CmsIr\System\Model\Block;
use Product\Model\Product;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findAllActiveProductsForProductList($langId)
    {
        $products = $this->getProductTable()->getBy(array('status_id' => 1));


    }

    public function findProductWithBlocks($entity, $langId)
    {
        /* @var $entity Product */
        $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Product', 'entity_id' => $entity->getId(), 'language_id' => $langId));
        $entity->setBlocks($blocks);

        /* @var $block Block */
        foreach($blocks as $block)
        {
            $fieldName = $block->getName();

            switch ($fieldName)
            {
                case 'product_name':
                    $entity->setProductName($block->getValue());
                    break;
                case 'content':
                    $entity->setProductDescription($block->getValue());
                    break;
            }
        }

        $categoryId = $entity->getCategoryId();
        $category = $this->getCategoryTable()->getOneBy(array('id' => $categoryId));
        $entity->setCategoryName($category->getName());

        $files = $this->getFileTable()->getBy(array('entity_type' => 'Product', 'entity_id' => $entity->getId()));
        $entity->setFiles($files);

        return $entity;
    }

    /**
     * @return \CmsIr\System\Model\LanguageTable
     */
    public function getLanguageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\LanguageTable');
    }

    /**
     * @return \CmsIr\Category\Model\CategoryTable
     */
    public function getCategoryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Category\Model\CategoryTable');
    }

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
    }

    /**
     * @return \Product\Model\ProductTable
     */
    public function getProductTable()
    {
        return $this->getServiceLocator()->get('Product\Model\ProductTable');
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
    }

    /**
     * @return \CmsIr\Tag\Service\TagService
     */
    public function getTagService()
    {
        return $this->getServiceLocator()->get('CmsIr\Tag\Model\TagService');
    }

    /**
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}

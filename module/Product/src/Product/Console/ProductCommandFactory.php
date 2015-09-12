<?php

namespace Product\Console;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductCommandFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ProductCommand
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mainServiceLocator = $serviceLocator->getServiceLocator();
        $categoryService = $mainServiceLocator->get('CmsIr\Category\Service\CategoryService');
        $disctionaryTable = $mainServiceLocator->get('CmsIr\Dictionary\Model\DictionaryTable');
        $productTable = $mainServiceLocator->get('Product\Model\ProductTable');
        $statusTable = $mainServiceLocator->get('CmsIr\System\Model\StatusTable');

        $command = new ProductCommand($categoryService, $disctionaryTable, $productTable, $statusTable);
        return $command;
    }
}
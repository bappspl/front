<?php

namespace Product\Console;

use CmsIr\Category\Model\Category;
use CmsIr\Category\Service\CategoryService;
use CmsIr\Dictionary\Model\Dictionary;
use CmsIr\Dictionary\Model\DictionaryTable;
use CmsIr\System\Model\Block;
use CmsIr\System\Model\BlockTable;
use CmsIr\System\Model\StatusTable;
use Product\Model\Product;
use Product\Model\ProductTable;
use Zend\Console\Prompt;
use Zend\Mvc\Controller\AbstractActionController;
use CmsIr\System\Util\Inflector;


class ProductCommand extends AbstractActionController
{
    /**
     * @var \Zend\Console\Adapter\AdapterInterface
     */
    protected $console;
    /**
     * @var CategoryService
     */
    protected $categoryService;
    /**
     * @var DictionaryTable
     */
    protected $dictionaryTable;
    /**
     * @var ProductTable
     */
    protected $productTable;
    /**
     * @var StatusTable
     */
    protected $statusTable;
    /**
     * @var StatusTable
     */
    protected $blockTable;

    public function __construct(CategoryService $categoryService, DictionaryTable $dictionaryTable, ProductTable $productTable, StatusTable $statusTable, BlockTable $blockTable)
    {
        $this->categoryService = $categoryService;
        $this->dictionaryTable = $dictionaryTable;
        $this->productTable = $productTable;
        $this->statusTable = $statusTable;
        $this->blockTable = $blockTable;
    }

    protected function writeLine($message)
    {
        $this->getConsole()->writeLine($message . PHP_EOL);
    }

    public function parseCsvAction()
    {
        $row = 1;
        $naglowki = array('category', 'catalog_number', 'name', 'class', 'length', 'height', 'width', 'volume', 'weight', 'unit_m3', 'unit_m2', 'unit_mb', 'unit_kg', 'unit_l', 'price');

        if (($handle = fopen("./public/produkty.csv", "r")) !== FALSE)
        {
            $this->writeLine(sprintf('Parsowanie pliku..'));

            $produkty = array();
            $kategoria = '';
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE)
            {
                $num = count($data);
                $row++;


                if(strlen($data[0]) > 0)
                {
                    $kategoria = $data[0];
                }

                $obj = array();
                $obj[$naglowki[0]] = $kategoria;

                for ($c = 1; $c < $num; $c++)
                {
                    $wartosc = $data[$c];
                    if (strpos($naglowki[$c], 'unit') !== false && $wartosc !== '-')
                    {
                        $unit = explode('_', $naglowki[$c]);
                        $obj['unit'] = $unit[1];
                    } else
                    {

                        if ($wartosc === '-')
                        {
                            $wartosc = null;
                        }

                        $obj[$naglowki[$c]] = $wartosc;
                    }

                }

                $produkty[] = $obj;


            }
            fclose($handle);

            $this->writeLine(sprintf('Plik sparsowany, nastepuje zwolnienie blokady..'));
            $this->writeLine(sprintf(' - Tworzenie produktow - '));

            foreach($produkty as $produkt)
            {
                $category = $produkt['category'];
                $catalogNumber = $produkt['catalog_number'];
                $name = $produkt['name'];
                $class = $produkt['class'];
                $length = $produkt['length'];
                $height = $produkt['height'];
                $width = $produkt['width'];
                $volume = $produkt['volume'];
                $weight = $produkt['weight'];

                if(array_key_exists('unit', $produkt))
                {
                    $unit = $produkt['unit'];
                } else
                {
                    $unit = null;
                }

                $price = $produkt['price'];

                if($price && strpos($price, ',') !== false)
                {
                    $price = substr($price, 0, strpos($price, ','));
                    $price = str_replace(' ', '', $price);
                }

                $categoryId = $this->getCategoryIdByCategoryName($category);

                if($class)
                {
                    $classId = $this->getClassIdByName($class, $categoryId);
                } else
                {
                    $classId = null;
                }

                if($length)
                {
                    $lengthId = $this->getDictionaryIdByNameAndCategory($length, 'length');
                } else
                {
                    $lengthId = null;
                }

                if($height)
                {
                    $heightId = $this->getDictionaryIdByNameAndCategory($height, 'height');
                } else
                {
                    $heightId = null;
                }

                if($width)
                {
                    $widthId = $this->getDictionaryIdByNameAndCategory($width, 'width');
                } else
                {
                    $widthId = null;
                }

                if($volume)
                {
                    $volumeId = $this->getDictionaryIdByNameAndCategory($volume, 'volume');
                } else
                {
                    $volumeId = null;
                }

                if($weight)
                {
                    $weightId = $this->getDictionaryIdByNameAndCategory($weight, 'weight');
                } else
                {
                    $weightId = null;
                }

                if($unit)
                {
                    $unitId = $this->getDictionaryIdByNameAndCategory($unit, 'unit');
                } else
                {
                    $unitId = null;
                }

                $this->writeLine(sprintf(' - Zapis produktu - '));

                /* @var $newProduct \Product\Model\Product */
                $newProduct = $this->checkProductAlreadyExists($categoryId, $name,  $catalogNumber);
                if(!$newProduct)
                {
                    $this->writeLine(sprintf('Produkt nie istnieje - tworzenie'));
                    /* @var $inactiveStatus \CmsIr\System\Model\Status */
                    $inactiveStatus = $this->statusTable->getOneBy(array('slug' => 'inactive'));
                    $inactiveStatusId = $inactiveStatus->getId();

                    $newProduct = new Product();
                    $newProduct->setStatusId($inactiveStatusId);
                } else
                {
                    $this->writeLine(sprintf('Produkt istnieje - aktualizacja'));
                }

                $newProduct->setName($name);
                $newProduct->setSlug(Inflector::slugify($catalogNumber.'-'.$name));
                $newProduct->setPrice($price);
                $newProduct->setCatalogNumber($catalogNumber);
                $newProduct->setCategoryId($categoryId);
                $newProduct->setClassId($classId);
                $newProduct->setLengthId($lengthId);
                $newProduct->setHeightId($heightId);
                $newProduct->setWidthId($widthId);
                $newProduct->setVolumeId($volumeId);
                $newProduct->setWeightId($weightId);
                $newProduct->setUnitId($unitId);

                $produktId = $this->productTable->save($newProduct);
                $this->saveBlockForEntity($produktId, 'Product', $newProduct);
                $this->writeLine(sprintf(' - Utworzono produkt - '));
            }
        }

        $date = new \DateTime();

        $this->writeLine(sprintf('Koniec %s', $date->format('Y-m-d H:i:s') . PHP_EOL));
    }

    protected function saveBlockForEntity($entityId, $entityType, $entity)
    {
        $entities = array(
            'Product' => array('product_name', 'description'),
            'Category' => array('title', 'content'),
            'Dictionary' => array('title', 'content')
        );
        $block = $this->blockTable->getOneBy(array('entity_id' => $entityId, 'entity_type' => $entityType));
        if(!$block)
        {
            $this->writeLine(sprintf('Brak blokow dla: '.$entityType.', o nazwie: '.$entity->getName()));

            $fields = $entities[$entityType];

            for($i=1;$i<4;$i++)
            {

                foreach($fields as $field)
                {
                    $newBlock = new Block();
                    $newBlock->setEntityId($entityId);
                    $newBlock->setEntityType($entityType);
                    $newBlock->setLanguageId($i);
                    $newBlock->setName($field);
                    $newBlock->setValue($entity->getName());

                    $this->blockTable->save($newBlock);
                    $this->writeLine(sprintf('Utworzono blok dla: '.$entityType.', o nazwie: '.$entity->getName().', lang Id: '.$i));
                }

            }

        }
    }

    protected function checkProductAlreadyExists($categoryId, $name,  $catalogNumber)
    {
        $product = $this->productTable->getOneBy(array(
            'category_id' => $categoryId,
            'name' => $name,
            'catalog_number' => $catalogNumber
        ));

        return $product;
    }

    protected function getCategoryIdByCategoryName($category)
    {
        $this->writeLine(sprintf('Sprawdzam, czy istnieje kategoria:' . $category));
        /* @var $categoryEntity \CmsIr\Category\Model\Category */
        $categoryEntity = $this->categoryService->getCategoryTable()->getOneBy(array('name' => $category));

        //istnieje kategoria - bierzemy id
        if($categoryEntity)
        {
            $categoryId = $categoryEntity->getId();
            $this->writeLine(sprintf('Istnieje -> zwracam id -> '. $categoryId));
            $this->saveBlockForEntity($categoryId, 'Category', $categoryEntity);
        } else // nie istnieje kategoria - tworzymy
        {
            $newCategory = new Category();
            $newCategory->setName($category);
            $newCategory->setFilename(null);
            $categoryId = $this->categoryService->getCategoryTable()->save($newCategory);
            $this->writeLine(sprintf('Nie istnieje -> tworze nowa -> zwracam id -> '. $categoryId));
            $this->saveBlockForEntity($categoryId, 'Category', $newCategory);
        }

        return $categoryId;
    }

    protected function getDictionaryIdByNameAndCategory($name, $category)
    {
        $this->writeLine(sprintf('Sprawdzam, czy istnieje slownik: '.$name.' z kategorii: '. $category));
        /* @var $dictionaryEntity \CmsIr\Dictionary\Model\Dictionary */
        $dictionaryEntity = $this->dictionaryTable->getOneBy(array('name' => $name, 'category' => $category));

        //istnieje slownik - bierzemy id
        if($dictionaryEntity)
        {
            $dictionaryId = $dictionaryEntity->getId();
            $this->writeLine(sprintf('Istnieje -> zwracam id -> '. $dictionaryId));
            $this->saveBlockForEntity($dictionaryId, 'Dictionary', $dictionaryEntity);
        } else // nie istnieje slownik - tworzymy
        {
            $newDictionary = new Dictionary();
            $newDictionary->setName($name);
            $newDictionary->setCategory($category);
            $dictionaryId = $this->dictionaryTable->save($newDictionary);
            $this->writeLine(sprintf('Nie istnieje -> tworze nowy -> zwracam id -> '. $dictionaryId));
            $this->saveBlockForEntity($dictionaryId, 'Dictionary', $newDictionary);
        }

        return $dictionaryId;
    }

    protected function getClassIdByName($class, $categoryId)
    {
        $this->writeLine(sprintf('Sprawdzam, czy istnieje slownik: '.$class.' z kategorii: '. $categoryId));
        /* @var $dictionaryEntity \CmsIr\Dictionary\Model\Dictionary */
        $dictionaryEntity = $this->dictionaryTable->getOneBy(array('name' => $class, 'category' => 'class'));

        //istnieje slownik - bierzemy id
        if($dictionaryEntity)
        {
            $classId = $dictionaryEntity->getId();
            $this->writeLine(sprintf('Istnieje -> zwracam id -> '. $classId));
            $this->saveBlockForEntity($classId, 'Dictionary', $dictionaryEntity);
        } else // nie istnieje slownik - tworzymy
        {
            $newDictionary = new Dictionary();
            $newDictionary->setName($class);
            $newDictionary->setCategory('class');
            $newDictionary->setCategoryId($categoryId);
            $classId = $this->dictionaryTable->save($newDictionary);
            $this->writeLine(sprintf('Nie istnieje -> tworze nowy -> zwracam id -> '. $classId));
            $this->saveBlockForEntity($classId, 'Dictionary', $newDictionary);
        }

        return $classId;
    }

    /**
     * @return \Zend\Console\Adapter\AdapterInterface
     */
    protected function getConsole()
    {
        if ($this->console === null)
        {
            $this->console = $this->getServiceLocator()->get('console');
        }

        return $this->console;
    }
}
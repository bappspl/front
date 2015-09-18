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
        $naglowki = array(
            'category_pl',
            'category_de',
            'category_en',
            'catalog_number',
            'name_pl',
            'name_de',
            'name_en',
            'class', 'length', 'height', 'width', 'volume', 'weight', 'unit_m3', 'unit_m2', 'unit_mb', 'unit_kg', 'unit_l', 'price');

        if (($handle = fopen("./public/produkty_new.csv", "r")) !== FALSE)
        {
            $this->writeLine(sprintf('Parsowanie pliku..'));

            $produkty = array();
            $kategoriaPL = '';
            $kategoriaDE = '';
            $kategoriaEN = '';
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE)
            {
                $num = count($data);
                $row++;


                if(strlen($data[0]) > 0)
                {
                    $kategoriaPL = $data[0];
                    $kategoriaDE = $data[1];
                    $kategoriaEN = $data[2];
                }

                $obj = array();
                $obj[$naglowki[0]] = $kategoriaPL;
                $obj[$naglowki[1]] = $kategoriaDE;
                $obj[$naglowki[2]] = $kategoriaEN;

                for ($c = 3; $c < $num; $c++)
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
                $categoryPL = $produkt['category_pl'];
                $categoryEN = $produkt['category_en'];
                $categoryDE = $produkt['category_de'];
                $catalogNumber = $produkt['catalog_number'];
                $namePL = $produkt['name_pl'];
                $nameDE = $produkt['name_de'];
                $nameEN = $produkt['name_en'];
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

                $categoryId = $this->getCategoryIdByCategoryName($categoryPL, $categoryDE, $categoryEN);

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
                $newProduct = $this->checkProductAlreadyExists($categoryId, $namePL,  $catalogNumber);
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

                $newProduct->setName($namePL);
                $newProduct->setSlug(Inflector::slugify($catalogNumber.'-'.$namePL));
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
                $this->saveBlockForEntity($produktId, 'Product', $newProduct, $nameDE, $nameEN);
                $this->writeLine(sprintf(' - Utworzono produkt - '));
            }
        }

        $date = new \DateTime();

        $this->writeLine(sprintf('Koniec %s', $date->format('Y-m-d H:i:s') . PHP_EOL));
    }

    protected function saveBlockForEntity($entityId, $entityType, $entity, $de = null, $en = null)
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

                    if($de && $en)
                    {
                        switch($i)
                        {
                            case 1:
                                $newBlock->setValue($entity->getName());
                            break;
                            case 2:
                                $newBlock->setValue($en);
                            break;
                            case 3:
                                $newBlock->setValue($de);
                            break;
                        }
                    } else
                    {
                        $newBlock->setValue($entity->getName());
                    }


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

    protected function getCategoryIdByCategoryName($categoryPL, $categoryDE, $categoryEN)
    {
        $this->writeLine(sprintf('Sprawdzam, czy istnieje kategoria:' . $categoryPL));
        /* @var $categoryEntity \CmsIr\Category\Model\Category */
        $categoryEntity = $this->categoryService->getCategoryTable()->getOneBy(array('name' => $categoryPL));

        //istnieje kategoria - bierzemy id
        if($categoryEntity)
        {
            $categoryId = $categoryEntity->getId();
            $this->writeLine(sprintf('Istnieje -> zwracam id -> '. $categoryId));
            $this->saveBlockForEntity($categoryId, 'Category', $categoryEntity, $categoryDE, $categoryEN);
        } else // nie istnieje kategoria - tworzymy
        {
            $newCategory = new Category();
            $newCategory->setName($categoryPL);
            $newCategory->setFilename(null);
            $categoryId = $this->categoryService->getCategoryTable()->save($newCategory);
            $this->writeLine(sprintf('Nie istnieje -> tworze nowa -> zwracam id -> '. $categoryId));
            $this->saveBlockForEntity($categoryId, 'Category', $newCategory, $categoryDE, $categoryEN);
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
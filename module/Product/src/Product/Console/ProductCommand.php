<?php

namespace Product\Console;

use CmsIr\Category\Model\Category;
use CmsIr\Category\Service\CategoryService;
use CmsIr\Dictionary\Model\Dictionary;
use CmsIr\Dictionary\Model\DictionaryTable;
use Product\Model\ProductTable;
use Zend\Console\Prompt;
use Zend\Mvc\Controller\AbstractActionController;


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

    public function __construct(CategoryService $categoryService, DictionaryTable $dictionaryTable, ProductTable $productTable)
    {
        $this->categoryService = $categoryService;
        $this->dictionaryTable = $dictionaryTable;
        $this->productTable = $productTable;
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

                for ($c=1; $c < $num; $c++)
                {
                    if(strpos($naglowki[$c], 'unit') !== false)
                    {
                        $unit = explode('_', $naglowki[$c]);
                        $obj['unit'] = $unit[1];
                    } else
                    {
                        $wartosc = $data[$c];
                        if($wartosc === '-')
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

            foreach($produkty as $produkt)
            {
                $category = $produkt['category'];
                $catalogNumber = $produkt['catalog_number'];
                $name = $produkt['name'];
                $class = $produkt['class'];
                $length = $produkt['length'];
                $height = $produkt['height'];
                $unit = $produkt['unit'];
                $price = $produkt['price'];

                $categoryId = $this->getCategoryIdByCategoryName($category);
                $classId = $this->getClassIdByName($class, $categoryId);
                $lengthId = $this->getDictionaryIdByNameAndCategory($length, 'class');

            }
        }

        $date = new \DateTime();

        $this->writeLine(sprintf('Koniec %s', $date->format('Y-m-d H:i:s') . PHP_EOL));
    }

    protected function getCategoryIdByCategoryName($category)
    {
        /* @var $categoryEntity \CmsIr\Category\Model\Category */
        $categoryEntity = $this->categoryService->getCategoryTable()->getOneBy(array('name' => $category));

        //istnieje kategoria - bierzemy id
        if($categoryEntity)
        {
            $categoryId = $categoryEntity->getId();
        } else // nie istnieje kategoria - tworzymy
        {
            $newCategory = new Category();
            $newCategory->setName($category);
            $newCategory->setFilename('');
            $categoryId = $this->categoryService->getCategoryTable()->save($newCategory);
        }

        return $categoryId;
    }

    protected function getDictionaryIdByNameAndCategory($name, $category)
    {
        /* @var $dictionaryEntity \CmsIr\Dictionary\Model\Dictionary */
        $dictionaryEntity = $this->dictionaryTable->getOneBy(array('name' => $name, 'category' => $category));

        //istnieje slownik - bierzemy id
        if($dictionaryEntity)
        {
            $categoryId = $dictionaryEntity->getId();
        } else // nie istnieje slownik - tworzymy
        {
            $newDictionary = new Dictionary();
            $newDictionary->setName($name);
            $newDictionary->setCategory($category);
            $categoryId = $this->dictionaryTable->save($newDictionary);
        }

        return $categoryId;
    }

    protected function getClassIdByName($class, $categoryId)
    {
        /* @var $dictionaryEntity \CmsIr\Dictionary\Model\Dictionary */
        $dictionaryEntity = $this->dictionaryTable->getOneBy(array('name' => $class, 'category' => 'class'));

        //istnieje slownik - bierzemy id
        if($dictionaryEntity)
        {
            $categoryId = $dictionaryEntity->getId();
        } else // nie istnieje slownik - tworzymy
        {
            $newDictionary = new Dictionary();
            $newDictionary->setName($class);
            $newDictionary->setCategory('class');
            $newDictionary->setCategoryId($categoryId);
            $categoryId = $this->dictionaryTable->save($newDictionary);
        }

        return $categoryId;
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
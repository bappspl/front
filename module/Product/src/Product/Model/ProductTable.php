<?php
namespace Product\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductTable extends ModelTable implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getCategoriesForAllProducts($langId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array(
            'id',
            'category_id' => new Expression('COUNT(category_id)'),
        ));
        $select->join('cms_category', 'cms_category.id = product.category_id', array('category' => 'name'), 'inner');
        $select->join('cms_block', new Expression("cms_block.entity_id = cms_category.id AND cms_block.entity_type = 'Category'"), array('category_name' => 'value'), 'inner');
        $select->group('category_id');
        $select->where(array(
            'cms_block.language_id' => $langId,
            'cms_block.name' => 'title',
        ));

        $resultSet = $this->tableGateway->selectWith($select);

        $result = array();

        foreach($resultSet as $entity)
        {
            $result[$entity->getId()] = array(
                'category' => $entity->getCategory(),
                'counter' => $entity->getCategoryId(),
                'category_name' => $entity->getCategoryName()
            );
        }

        return $result;
    }

    public function deleteProduct($ids)
    {
        if(!is_array($ids))
        {
            $ids = array($ids);
        }

        foreach($ids as $id)
        {
            $this->tableGateway->delete(array('id' => $id));
        }
    }

    public function getDataToDisplay ($filteredRows, $columns)
    {
        $dataArray = array();
        foreach($filteredRows as $row) {

            $tmp = array();
            foreach($columns as $column){
                $column = 'get'.ucfirst($column);
                if($column == 'getStatus')
                {
                    $tmp[] = $this->getLabelToDisplay($row->getStatusId());
                } else
                {
                    $tmp[] = $row->$column();
                }
            }
            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getLabelToDisplay ($labelValue)
    {
        $status = $this->getStatusTable()->getBy(array('id' => $labelValue));
        $currentStatus = reset($status);
        $currentStatus->getName() == 'Active' ? $checked = 'label-primary' : $checked = 'label-default';
        $currentStatus->getName() == 'Active' ? $name = 'Aktywna' : $name= 'Nieaktywna';

        $template = '<span class="label ' . $checked . '">' .$name . '</span>';
        return $template;
    }

    public function save(Product $product)
    {
        $data = array(
            'name' => $product->getName(),
            'status_id' => $product->getStatusId(),
            'price' => $product->getPrice(),
            'catalog_number' => $product->getCatalogNumber(),
            'bestseller' => $product->getBestseller(),
            'show_price' => $product->getShowPrice(),
            'category_id' => $product->getCategoryId(),

            'class_id' => $product->getClassId(),
            'length_id' => $product->getLengthId(),
            'height_id' => $product->getHeightId(),
            'width_id' => $product->getWidthId(),
            'volume_id' => $product->getVolumeId(),
            'weight_id' => $product->getWeightId(),
            'unit_id' => $product->getUnitId(),
        );

        $id = (int) $product->getId();
        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else
        {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Product id does not exist');
            }
        }

        return $id;
    }

    public function changeStatusProduct($ids, $statusId)
    {
        if(!is_array($ids))
        {
            $ids = array($ids);
        }
        $data = array('status_id'  => $statusId);
        foreach($ids as $id)
        {
            $this->tableGateway->update($data, array('id' => $id));
        }
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

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }
}
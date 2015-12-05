<?php
namespace Disc\Model;

use CmsIr\System\Model\ModelTable;
use CmsIr\System\Util\Inflector;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RecordTable extends ModelTable implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getDatatables($columns, $data, $discId)
    {
        $displayFlag = false;

        $allRows = $this->getAll();
        $countAllRows = count($allRows);

        $trueOffset = (int) $data->iDisplayStart;
        $trueLimit = (int) $data->iDisplayLength;

        $sorting = array('id', 'asc');
        if(isset($data->iSortCol_0)) {
            $sorting = $this->getSortingColumnDir($columns, $data);
        }

        $where = array();
        if ($data->sSearch != '') {
            $columnsToSearch = array('id', 'name', 'status_id', 'catalog_number');
            $where = array(
                new Predicate\PredicateSet(
                    $this->getFilterPredicate($columnsToSearch, $data),
                    Predicate\PredicateSet::COMBINED_BY_OR
                )
            );
            $where['disc_id'] = $discId;
        } else {
            $where['disc_id'] = $discId;
        }

        $filteredRows = $this->tableGateway->select(function(Select $select) use ($trueLimit, $trueOffset, $sorting, $where){
            $select
                ->where($where)
                ->order($sorting[0] . ' ' . $sorting[1]);
        });

        $dataArray = $this->getDataToDisplay($filteredRows, $columns);

        if($displayFlag == true) {
            $countFilteredRows = $filteredRows->count();
        } else {
            $countFilteredRows = $countAllRows;
        }

        return array('iTotalRecords' => $countAllRows, 'iTotalDisplayRecords' => $countFilteredRows, 'aaData' => $dataArray);
    }

    public function deleteRecord($ids)
    {
        if(!is_array($ids)) {
            $ids = array($ids);
        }

        foreach($ids as $id) {
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
                if($column == 'getStatus') {
                    $tmp[] = $this->getLabelToDisplay($row->getStatusId());
                } else {
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

    public function save(Record $record)
    {
        $data = array(
            'name' => $record->getName(),
            'slug' => Inflector::slugify($record->getName()),
            'status_id'  => $record->getStatusId(),
            'title'  => $record->getTitle(),
            'content'  => $record->getContent(),
            'listen'  => $record->getListen(),
            'buy'  => $record->getBuy(),
            'disc_id'  => $record->getDiscId(),
        );

        $id = (int) $record->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Disc id does not exist');
            }
        }

        return $id;
    }

    public function changeStatusRecord($ids, $statusId)
    {
        if(!is_array($ids)) {
            $ids = array($ids);
        }
        $data = array('status_id'  => $statusId);
        foreach($ids as $id) {
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
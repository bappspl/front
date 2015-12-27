<?php

namespace Disc\Service;

use CmsIr\Page\Model\Page;
use CmsIr\System\Model\Block;
use CmsIr\System\Model\Language;
use CmsIr\System\Model\Status;
use Disc\Model\Disc;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;

class DiscService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findAll()
    {
        $discs = $this->getDiscTable()->getBy(array('status_id' => 1), 'position asc');

        if(!$discs) {
            return null;
        }

        /* @var $disc Disc */
        foreach ($discs as $disc) {

            $category = $this->getCategoryTable()->getOneBy(array('id' => $disc->getCategoryId()));
            $disc->setCategory($category);
        }

        return $discs;
    }

    public function findOneBy($id)
    {
        /* @var $disc Disc */
        $disc = $this->getDiscTable()->getOneBy(array('id' => $id));

        $records = $this->getRecordTable()->getBy(array('disc_id' => $id));

        $disc->setRecords($records);

        return $disc;
    }

    /**
     * @return \Disc\Model\DiscTable
     */
    public function getDiscTable()
    {
        return $this->getServiceLocator()->get('Disc\Model\DiscTable');
    }

    /**
     * @return \Disc\Model\RecordTable
     */
    public function getRecordTable()
    {
        return $this->getServiceLocator()->get('Record\Model\RecordTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\Category\Model\CategoryTable
     */
    public function getCategoryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Category\Model\CategoryTable');
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

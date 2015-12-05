<?php
namespace Disc\Controller;

use Disc\Form\RecordForm;
use Disc\Form\RecordFormFilter;
use Disc\Model\Record;
use CmsIr\System\Model\Block;
use CmsIr\System\Util\Inflector;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class RecordController extends AbstractActionController
{
    public function listAction()
    {
        $discId = $this->params()->fromRoute('disc_id');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'statusId', 'status', 'id');

            $listData = $this->getRecordTable()->getDatatables($columns, $data, $discId);

            $output = array(
                "sEcho" => $this->getRequest()->getPost('sEcho'),
                "iTotalRecords" => $listData['iTotalRecords'],
                "iTotalDisplayRecords" => $listData['iTotalDisplayRecords'],
                "aaData" => $listData['aaData']
            );

            $jsonObject = Json::encode($output, true);
            echo $jsonObject;
            return $this->response;
        }

        $viewParams = array();
        $viewParams['discId'] = $discId;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function createAction()
    {
        $discId = $this->params()->fromRoute('disc_id');

        $statuses = $this->getStatusService()->findAsAssocArray();

        $form = new RecordForm($statuses);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new RecordFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $record = new Record();
                $record->exchangeArray($form->getData());

                $record->setDiscId($discId);

                $this->getRecordTable()->save($record);

                $this->flashMessenger()->addMessage('Piosenka została dodana poprawnie.');

                return $this->redirect()->toRoute('disc/records', array('disc_id' => $discId));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['discId'] = $discId;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editAction()
    {
        $discId = $this->params()->fromRoute('disc_id');
        $id = $this->params()->fromRoute('record_id');

        /* @var $record Record */
        $record = $this->getRecordTable()->getOneBy(array('id' => $id));

        if(!$record)  {
            return $this->redirect()->toRoute('records', array('disc_id' => $discId));
        }

        $categories = $this->getCategoryService()->findAsAssocArray('music');
        $statuses = $this->getStatusService()->findAsAssocArray();

        $form = new RecordForm($statuses, $categories);
        $form->bind($record);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new RecordFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $record->setDiscId($discId);
                $this->getRecordTable()->save($record);

                $this->flashMessenger()->addMessage('Piosenka została edytowana poprawnie.');

                return $this->redirect()->toRoute('disc/records', array('disc_id' => $discId));
            }
        }

        $viewParams = array();
        $viewParams['record'] = $record;
        $viewParams['form'] = $form;
        $viewParams['discId'] = $discId;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $discId = $this->params()->fromRoute('disc_id');
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('record_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('records');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');

                if(!is_array($id)) {
                    $id = array($id);
                }

                $this->getRecordTable()->deleteRecord($id);
                $this->flashMessenger()->addMessage('Piosenka została usunięta poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('records', array('disc_id' => $discId));
        }

        return array(
            'id'    => $id,
            'record' => $this->getRecordTable()->getOneBy(array('id' => $id))
        );
    }

    public function changeStatusAction()
    {
        $discId = $this->params()->fromRoute('disc_id');
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('record_id');

        if (!$id) {
            return $this->redirect()->toRoute('record');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz') {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getRecordTable()->changeStatusRecord($id, $statusId);

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('records', array('disc_id' => $discId));
        }

        return array();
    }

    /**
     * @return \Disc\Model\RecordTable
     */
    public function getRecordTable()
    {
        return $this->getServiceLocator()->get('Record\Model\RecordTable');
    }

    /**
     * @return \CmsIr\System\Service\StatusService
     */
    public function getStatusService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\StatusService');
    }

    /**
     * @return \CmsIr\Category\Service\CategoryService
     */
    public function getCategoryService()
    {
        return $this->getServiceLocator()->get('CmsIr\Category\Service\CategoryService');
    }
}
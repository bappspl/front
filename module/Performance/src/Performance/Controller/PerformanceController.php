<?php
namespace Performance\Controller;

use Performance\Form\PerformanceForm;
use Performance\Form\PerformanceFormFilter;
use Performance\Model\Performance;
use CmsIr\System\Model\Block;
use CmsIr\System\Util\Inflector;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class PerformanceController extends AbstractActionController
{
    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'date', 'statusId', 'status', 'id');

            $listData = $this->getPerformanceTable()->getDatatables($columns,$data);

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

        return new ViewModel();
    }

    public function createAction()
    {
        $statuses = $this->getStatusService()->findAsAssocArray();

        $form = new PerformanceForm($statuses);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new PerformanceFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $performance = new Performance();
                $performance->exchangeArray($form->getData());

                $this->getPerformanceTable()->save($performance);

                $this->flashMessenger()->addMessage('Koncert został dodany poprawnie.');

                return $this->redirect()->toRoute('performance');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('performance_id');

        /* @var $performance Performance */
        $performance = $this->getPerformanceTable()->getOneBy(array('id' => $id));

        if(!$performance)  {
            return $this->redirect()->toRoute('performance');
        }

        $statuses = $this->getStatusService()->findAsAssocArray();

        $form = new PerformanceForm($statuses);
        $form->bind($performance);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new PerformanceFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getPerformanceTable()->save($performance);

                $this->flashMessenger()->addMessage('Koncert został edytowany poprawnie.');

                return $this->redirect()->toRoute('performance');
            }
        }

        $viewParams = array();
        $viewParams['performance'] = $performance;
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('performance_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('performance');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');

                if(!is_array($id)) {
                    $id = array($id);
                }

                $this->getPerformanceTable()->deletePerformance($id);
                $this->flashMessenger()->addMessage('Koncert został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('performance');
        }

        return array(
            'id'    => $id,
            'performance' => $this->getPerformanceTable()->getOneBy(array('id' => $id))
        );
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('performance_id');

        if (!$id) {
            return $this->redirect()->toRoute('performance');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz') {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getPerformanceTable()->changeStatusPerformance($id, $statusId);

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('performance');
        }

        return array();
    }

    public function uploadFilesMainAction ()
    {
        if (!empty($_FILES)) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];

            $file = explode('.', $targetFile);
            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->destinationUploadDir.$targetFile)) {
                echo $targetFile;
            } else {
                echo 0;
            }
        }
        return $this->response;
    }

    public function uploadFilesAction ()
    {
        if (!empty($_FILES)) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];
            $file = explode('.', $targetFile);

            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->uploadDir.$targetFile)) {
                echo $targetFile;
            } else {
                echo 0;
            }

        }
        return $this->response;
    }

    public function deletePhotoAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $filePath = $request->getPost('filePath');

            if(!empty($id)) {
                $this->getFileTable()->deleteFile($id);
                unlink('./public'.$filePath);

            } else {
                unlink('./public'.$filePath);
            }
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
        return $this->response;
    }

    public function deletePhotoMainAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $filePath = $request->getPost('filePath');

            if(!empty($id)) {
                $this->getFileTable()->deleteFile($id);
                unlink('./public'.$filePath);

            } else {
                unlink('./public'.$filePath);
            }
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
        return $this->response;
    }

    /**
     * @return \Performance\Model\PerformanceTable
     */
    public function getPerformanceTable()
    {
        return $this->getServiceLocator()->get('Performance\Model\PerformanceTable');
    }

    /**
     * @return \CmsIr\System\Service\StatusService
     */
    public function getStatusService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\StatusService');
    }
}
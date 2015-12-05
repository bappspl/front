<?php
namespace Disc\Controller;

use Disc\Form\DiscForm;
use Disc\Form\DiscFormFilter;
use Disc\Model\Disc;
use CmsIr\System\Model\Block;
use CmsIr\System\Util\Inflector;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class DiscController extends AbstractActionController
{
    protected $uploadDir = 'public/temp_files/disc/';
    protected $destinationUploadDir = 'public/files/disc/';

    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'statusId', 'status', 'id', 'position');

            $listData = $this->getDiscTable()->getDatatables($columns,$data);

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
        $categories = $this->getCategoryService()->findAsAssocArray('music');
        $statuses = $this->getStatusService()->findAsAssocArray();

        $form = new DiscForm($statuses, $categories);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new DiscFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $disc = new Disc();
                $disc->exchangeArray($form->getData());

                $this->getDiscTable()->save($disc);

                $this->flashMessenger()->addMessage('Płyta została dodana poprawnie.');

                return $this->redirect()->toRoute('disc');
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
        $id = $this->params()->fromRoute('disc_id');

        /* @var $disc Disc */
        $disc = $this->getDiscTable()->getOneBy(array('id' => $id));

        if(!$disc)  {
            return $this->redirect()->toRoute('disc');
        }

        $categories = $this->getCategoryService()->findAsAssocArray('music');
        $statuses = $this->getStatusService()->findAsAssocArray();

        $form = new DiscForm($statuses, $categories);
        $form->bind($disc);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new DiscFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getDiscTable()->save($disc);

                $this->flashMessenger()->addMessage('Płyta została edytowana poprawnie.');

                return $this->redirect()->toRoute('disc');
            }
        }

        $viewParams = array();
        $viewParams['disc'] = $disc;
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('disc_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('disc');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');

                if(!is_array($id)) {
                    $id = array($id);
                }

                $this->getDiscTable()->deleteDisc($id);
                $this->flashMessenger()->addMessage('Płyta została usunięta poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('disc');
        }

        return array(
            'id'    => $id,
            'disc' => $this->getDiscTable()->getOneBy(array('id' => $id))
        );
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('disc_id');

        if (!$id) {
            return $this->redirect()->toRoute('disc');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz') {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getDiscTable()->changeStatusDisc($id, $statusId);

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('disc');
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

    public function changePositionAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $position = $request->getPost('position');

            $this->getDiscTable()->changePosition($position);
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
        return $this->response;
    }

        /**
     * @return \Disc\Model\DiscTable
     */
    public function getDiscTable()
    {
        return $this->getServiceLocator()->get('Disc\Model\DiscTable');
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
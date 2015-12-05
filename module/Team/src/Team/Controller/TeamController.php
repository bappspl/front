<?php
namespace Team\Controller;

use Team\Form\TeamForm;
use Team\Form\TeamFormFilter;
use Team\Model\Team;
use CmsIr\System\Model\Block;
use CmsIr\System\Util\Inflector;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class TeamController extends AbstractActionController
{
    protected $uploadDir = 'public/temp_files/team/';
    protected $destinationUploadDir = 'public/files/team/';

    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'statusId', 'status', 'id');

            $listData = $this->getTeamTable()->getDatatables($columns,$data);

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

        $form = new TeamForm($statuses);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new TeamFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $team = new Team();
                $team->exchangeArray($form->getData());

                $this->getTeamTable()->save($team);

                $this->flashMessenger()->addMessage('Członek został dodany poprawnie.');

                return $this->redirect()->toRoute('team');
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
        $id = $this->params()->fromRoute('team_id');

        /* @var $team Team */
        $team = $this->getTeamTable()->getOneBy(array('id' => $id));

        if(!$team)  {
            return $this->redirect()->toRoute('team');
        }

        $statuses = $this->getStatusService()->findAsAssocArray();

        $form = new TeamForm($statuses);
        $form->bind($team);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new TeamFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getTeamTable()->save($team);

                $this->flashMessenger()->addMessage('Członek został edytowany poprawnie.');

                return $this->redirect()->toRoute('team');
            }
        }

        $viewParams = array();
        $viewParams['team'] = $team;
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('team_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('team');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');

                if(!is_array($id)) {
                    $id = array($id);
                }

                $this->getTeamTable()->deleteTeam($id);
                $this->flashMessenger()->addMessage('Członek został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('team');
        }

        return array(
            'id'    => $id,
            'team' => $this->getTeamTable()->getOneBy(array('id' => $id))
        );
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('team_id');

        if (!$id) {
            return $this->redirect()->toRoute('team');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz') {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getTeamTable()->changeStatusTeam($id, $statusId);

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('team');
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

    /**
     * @return \Team\Model\TeamTable
     */
    public function getTeamTable()
    {
        return $this->getServiceLocator()->get('Team\Model\TeamTable');
    }

    /**
     * @return \CmsIr\System\Service\StatusService
     */
    public function getStatusService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\StatusService');
    }
}
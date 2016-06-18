<?php
namespace Konserwator\Controller;

use Konserwator\Form\KonserwatorForm;
use Konserwator\Form\KonserwatorFormFilter;
use Konserwator\Model\Konserwator;
use CmsIr\System\Model\Block;
use CmsIr\System\Util\Inflector;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class KonserwatorController extends AbstractActionController
{
    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'statusId', 'status', 'id');

            $listData = $this->getKonserwatorTable()->getDatatables($columns,$data);

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

        $form = new KonserwatorForm($statuses);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new KonserwatorFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $konserwator = new Konserwator();
                $konserwator->exchangeArray($form->getData());

                $this->getKonserwatorTable()->save($konserwator);

                $this->flashMessenger()->addMessage('Konserwator został dodany poprawnie.');

                return $this->redirect()->toRoute('konserwator');
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
        $id = $this->params()->fromRoute('konserwator_id');

        /* @var $konserwator Konserwator */
        $konserwator = $this->getKonserwatorTable()->getOneBy(array('id' => $id));

        if(!$konserwator)  {
            return $this->redirect()->toRoute('konserwator');
        }

        $statuses = $this->getStatusService()->findAsAssocArray();

        $form = new KonserwatorForm($statuses);
        $form->bind($konserwator);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new KonserwatorFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getKonserwatorTable()->save($konserwator);

                $this->flashMessenger()->addMessage('Konserwator został edytowany poprawnie.');

                return $this->redirect()->toRoute('konserwator');
            }
        }

        $viewParams = array();
        $viewParams['konserwator'] = $konserwator;
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('konserwator_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('konserwator');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');

                if(!is_array($id)) {
                    $id = array($id);
                }

                $this->getKonserwatorTable()->deleteKonserwator($id);
                $this->flashMessenger()->addMessage('Konserwator został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('konserwator');
        }

        return array(
            'id'    => $id,
            'konserwator' => $this->getKonserwatorTable()->getOneBy(array('id' => $id))
        );
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('konserwator_id');

        if (!$id) {
            return $this->redirect()->toRoute('konserwator');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz') {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getKonserwatorTable()->changeStatusKonserwator($id, $statusId);

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('konserwator');
        }

        return array();
    }

    /**
     * @return \Konserwator\Model\KonserwatorTable
     */
    public function getKonserwatorTable()
    {
        return $this->getServiceLocator()->get('Konserwator\Model\KonserwatorTable');
    }

    /**
     * @return \CmsIr\System\Service\StatusService
     */
    public function getStatusService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\StatusService');
    }
}
<?php
namespace Product\Controller;

use CmsIr\Category\Model\Category;
use CmsIr\File\Model\File;
use Product\Form\ProductForm;
use Product\Form\ProductFormFilter;
use Product\Model\Product;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class ProductController extends AbstractActionController
{
    protected $uploadDir = 'public/temp_files/product/';
    protected $destinationUploadDir = 'public/files/product/';

    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'statusId', 'status', 'id');

            $listData = $this->getProductTable()->getDatatables($columns,$data);

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
        $categories = $this->getCategoryService()->findAsAssocArray();
        $tags = $this->getTagService()->findAsAssocArray();

        $form = new ProductForm($categories, $tags);

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $form->setInputFilter(new ProductFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $product = new Product();
                $product->exchangeArray($form->getData());

                $id = $this->getProductTable()->save($product);

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $file)
                    {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDir.'/'.$file);

                        $postFile = new File();
                        $postFile->setFilename($file);
                        $postFile->setEntityId($id);
                        $postFile->setEntityType('Product');
                        $postFile->setMimeType($mimeType);

                        $this->getFileTable()->save($postFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $this->getBlockService()->saveBlocks($id, 'Product', $request->getPost()->toArray(), 'product_name');

                $this->getTagService()->saveTags($request->getPost()->toArray()['tag_id'], $id, 'Product');

                $this->flashMessenger()->addMessage('Produkt został dodany poprawnie.');

                return $this->redirect()->toRoute('product');
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
        $id = $this->params()->fromRoute('product_id');

        /* @var $product Product */
        $product = $this->getProductTable()->getOneBy(array('id' => $id));

        if(!$product)
        {
            return $this->redirect()->toRoute('product');
        }

        $productFiles = $this->getFileTable()->getBy(array('entity_id' => $id, 'entity_type' => 'Product'));

        $blocks = $this->getBlockService()->getBlocks($product, 'Product');
        $tags = $this->getTagService()->findAsAssocArray();

        $categories = $this->getCategoryService()->findAsAssocArray();

        $form = new ProductForm($categories, $tags);
        $form->bind($product);

        $tagsForForm = $this->getTagService()->findAsAssocArrayForEntity( $id, 'Product');

        $form->get('tag_id')->setValue($tagsForForm);

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $form->setInputFilter(new ProductFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $id = $this->getProductTable()->save($product);

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $file)
                    {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDir.'/'.$file);

                        $postFile = new File();
                        $postFile->setFilename($file);
                        $postFile->setEntityId($id);
                        $postFile->setEntityType('Product');
                        $postFile->setMimeType($mimeType);

                        $this->getFileTable()->save($postFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $this->getBlockService()->saveBlocks($id, 'Product', $request->getPost()->toArray(), 'product_name');

                $this->getTagService()->saveTags($request->getPost()->toArray()['tag_id'], $id, 'Product');

                $this->flashMessenger()->addMessage('Produkt został edytowany poprawnie.');

                return $this->redirect()->toRoute('product');
            }
        }

        $viewParams = array();
        $viewParams['product'] = $product;
        $viewParams['form'] = $form;
        $viewParams['productFiles'] = $productFiles;
        $viewParams['blocks'] = $blocks;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('product_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('product');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');

                /* @var $product Product */
                $product = $this->getProductTable()->getOneBy(array('id' => $id));

                if(!is_array($id))
                {
                    $id = array($id);
                }

                foreach($id as $oneId)
                {
                    $productFiles = $this->getFileTable()->getBy(array('entity_type' => 'Product', 'entity_id' => $oneId));

                    if((!empty($productFiles)))
                    {
                        foreach($productFiles as $file)
                        {
                            unlink('./public/files/product/'.$file->getFilename());
                            $this->getFileTable()->deleteFile($file->getId());
                        }
                    }
                }

                $this->getProductTable()->deleteProduct($id);
                $this->flashMessenger()->addMessage('Pordukt został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('product');
        }

        return array(
            'id'    => $id,
            'product' => $this->getProductTable()->getOneBy(array('id' => $id))
        );
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('product_id');

        if (!$id) {
            return $this->redirect()->toRoute('product');
        }

        if ($request->isPost())
        {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz')
            {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getProductTable()->changeStatusProduct($id, $statusId);

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('product');
        }

        return array();
    }

    public function uploadFilesAction()
    {
        if (!empty($_FILES))
        {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];
            $file = explode('.', $targetFile);

            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->uploadDir.$targetFile))
            {
                echo $targetFile;
            } else
            {
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

            if(!empty($id))
            {
                $this->getFileTable()->deleteFile($id);
                unlink('./public'.$filePath);

            } else
            {
                unlink('./public'.$filePath);
            }
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
        return $this->response;
    }

    /**
     * @return \Product\Model\ProductTable
     */
    public function getProductTable()
    {
        return $this->getServiceLocator()->get('Product\Model\ProductTable');
    }

    /**
     * @return \CmsIr\File\Service\FileService
     */
    public function getFileService()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Service\FileService');
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
    }

    /**
     * @return \CmsIr\System\Service\BlockService
     */
    public function getBlockService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\BlockService');
    }

    /**
     * @return \CmsIr\Category\Service\CategoryService
     */
    public function getCategoryService()
    {
        return $this->getServiceLocator()->get('CmsIr\Category\Service\CategoryService');
    }

    /**
     * @return \CmsIr\Tag\Service\TagService
     */
    public function getTagService()
    {
        return $this->getServiceLocator()->get('CmsIr\Tag\Service\TagService');
    }

    /**
     * @return \CmsIr\Dictionary\Model\DictionaryTable
     */
    public function getDictionaryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Dictionary\Model\DictionaryTable');
    }
}
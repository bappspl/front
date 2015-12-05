<?php
namespace Disc\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class DiscForm extends Form
{
    public function __construct($status, $category)
    {
        parent::__construct('Disc');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'id'
            ),
        ));

        $this->add(array(
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
                'name' => 'status_id',
            ),
            'options' => array(
                'label' => 'Status',
                'value_options' => $status
            )
        ));

        $this->add(array(
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
                'name' => 'category_id',
            ),
            'options' => array(
                'label' => 'Kategoria',
                'value_options' => $category,
                'disable_inarray_validator' => true
            )
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'id' => 'name',
                'type'  => 'text',
                'placeholder' => 'Wprowadź nazwę'
            ),
            'options' => array(
                'label' => 'Nazwa',
            ),
        ));


        $this->add(array(
            'name' => 'facebook',
            'attributes' => array(
                'id' => 'facebook',
                'type'  => 'text',
                'placeholder' => 'Wprowadź Url - Facebook'
            ),
            'options' => array(
                'label' => 'Facebook',
            ),
        ));

        $this->add(array(
            'name' => 'content_first',
            'attributes' => array(
                'id' => 'content_first',
                'type'  => 'textarea',
                'placeholder' => 'Wprowadź opis I',
                'class' => 'summernote-lg',
            ),
            'options' => array(
                'label' => 'Opis I',
            ),
        ));

        $this->add(array(
            'name' => 'content_second',
            'attributes' => array(
                'id' => 'content_second',
                'type'  => 'textarea',
                'placeholder' => 'Wprowadź opis II',
                'class' => 'summernote-lg',
            ),
            'options' => array(
                'label' => 'Opis II',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Zapisz',
                'id' => 'submit',
                'class' => 'btn btn-primary pull-right'
            ),
        ));

        $this->add(array(
            'name' => 'filename_main',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'filename-main'
            ),
        ));

        $this->add(array(
            'name' => 'upload_main',
            'attributes' => array(
                'type'  => 'file',
                'id' => 'upload-main',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Upload',
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'id' => 'title',
                'type'  => 'text',
                'placeholder' => 'Wprowadź tytuł'
            ),
            'options' => array(
                'label' => 'Tytuł',
            ),
        ));

        $this->add(array(
            'name' => 'year',
            'attributes' => array(
                'id' => 'year',
                'type'  => 'text',
                'placeholder' => 'Wprowadź rok wydania'            ),
            'options' => array(
                'label' => 'Rok wydania',
            ),
        ));

        $this->add(array(
            'name' => 'album',
            'attributes' => array(
                'id' => 'album',
                'type'  => 'text',
                'placeholder' => 'Wprowadź album'
            ),
            'options' => array(
                'label' => 'Album',
            ),
        ));
    }
}
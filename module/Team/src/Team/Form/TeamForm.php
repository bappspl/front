<?php
namespace Team\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class TeamForm extends Form
{
    public function __construct($status)
    {
        parent::__construct('Team');
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
            'name' => 'first_name',
            'attributes' => array(
                'id' => 'first_name',
                'type'  => 'text',
                'placeholder' => 'Wprowadź imię i nazwisko'
            ),
            'options' => array(
                'label' => 'Imię i nazwisko',
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
            'name' => 'content',
            'attributes' => array(
                'id' => 'content',
                'type'  => 'textarea',
                'placeholder' => 'Wprowadź opis',
                'class' => 'summernote-lg',
            ),
            'options' => array(
                'label' => 'Opis',
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
    }
}
<?php
namespace Performance\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class PerformanceForm extends Form
{
    public function __construct($status)
    {
        parent::__construct('Performance');
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
            'name' => 'url',
            'attributes' => array(
                'id' => 'url',
                'type'  => 'text',
                'placeholder' => 'Wprowadź Url'
            ),
            'options' => array(
                'label' => 'Url',
            ),
        ));

        $this->add(array(
            'name' => 'date',
            'attributes' => array(
                'id' => 'date',
                'type'  => 'text',
                'class'  => 'form-control datetimepicker',
            ),
            'options' => array(
                'label' => 'Data',
            ),
        ));

        $this->add(array(
            'name' => 'content',
            'attributes' => array(
                'id' => 'content',
                'type'  => 'textarea',
                'placeholder' => 'Wprowadź opis koncertu',
                'class' => 'summernote-lg',
            ),
            'options' => array(
                'label' => 'Opis koncertu',
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
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'enter',
            'options' => array(
                'label' => 'What is your gender ?',
                'value_options' => array(
                    '0' => '0',
                    '1' => '1',
                ),
            ),
        ));
    }
}
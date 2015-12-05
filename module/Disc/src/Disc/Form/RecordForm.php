<?php
namespace Disc\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class RecordForm extends Form
{
    public function __construct($status)
    {
        parent::__construct('Record');
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
            'name' => 'listen',
            'attributes' => array(
                'id' => 'listen',
                'type'  => 'text',
                'placeholder' => 'Posłuchaj'
            ),
            'options' => array(
                'label' => 'Posłuchaj',
            ),
        ));

        $this->add(array(
            'name' => 'buy',
            'attributes' => array(
                'id' => 'buy',
                'type'  => 'text',
                'placeholder' => 'Kup'
            ),
            'options' => array(
                'label' => 'Kup',
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
    }
}
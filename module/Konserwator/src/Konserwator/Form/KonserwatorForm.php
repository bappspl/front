<?php
namespace Konserwator\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class KonserwatorForm extends Form
{
    public function __construct($status)
    {
        parent::__construct('Konserwator');
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
                'placeholder' => 'Wprowadź imię i nazwisko'
            ),
            'options' => array(
                'label' => 'Imię i nazwisko',
            ),
        ));

        $this->add(array(
            'name' => 'phone',
            'attributes' => array(
                'id' => 'phone',
                'type'  => 'text',
                'placeholder' => 'Wprowadź telefon'
            ),
            'options' => array(
                'label' => 'Telefon',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'id' => 'email',
                'type'  => 'text',
                'placeholder' => 'Wprowadź email'
            ),
            'options' => array(
                'label' => 'Telefon',
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
                'value_options' => array(
                    '2' => 'Nieaktywny',
                    '1' => 'Aktywny'
                ),
            )
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
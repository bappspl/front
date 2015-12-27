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
            'name' => 'listen_spotify',
            'attributes' => array(
                'id' => 'listen_spotify',
                'type'  => 'text',
                'placeholder' => 'Posłuchaj - spotify'
            ),
            'options' => array(
                'label' => 'Posłuchaj - spotify',
            ),
        ));

        $this->add(array(
            'name' => 'listen_deezer',
            'attributes' => array(
                'id' => 'listen_deezer',
                'type'  => 'text',
                'placeholder' => 'Posłuchaj - deezer'
            ),
            'options' => array(
                'label' => 'Posłuchaj - deezer',
            ),
        ));

        $this->add(array(
            'name' => 'buy_itunes',
            'attributes' => array(
                'id' => 'buy_itunes',
                'type'  => 'text',
                'placeholder' => 'Kup - itunes'
            ),
            'options' => array(
                'label' => 'Kup - itunes',
            ),
        ));

        $this->add(array(
            'name' => 'buy_google',
            'attributes' => array(
                'id' => 'buy_goole',
                'type'  => 'text',
                'placeholder' => 'Kup - googleplay'
            ),
            'options' => array(
                'label' => 'Kup - googleplay',
            ),
        ));

        $this->add(array(
            'name' => 'buy_playthemusic',
            'attributes' => array(
                'id' => 'buy_playthemusic',
                'type'  => 'text',
                'placeholder' => 'Kup - playthemusic'
            ),
            'options' => array(
                'label' => 'Kup - playthemusic',
            ),
        ));

        $this->add(array(
            'name' => 'buy_muzodajnia',
            'attributes' => array(
                'id' => 'buy_muzodajnia',
                'type'  => 'text',
                'placeholder' => 'Kup - muzodajnia'
            ),
            'options' => array(
                'label' => 'Kup - muzodajnia',
            ),
        ));

//        $this->add(array(
//            'name' => 'allegro',
//            'attributes' => array(
//                'id' => 'allegro',
//                'type'  => 'text',
//                'placeholder' => 'Allegro'
//            ),
//            'options' => array(
//                'label' => 'Allegro',
//            ),
//        ));

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
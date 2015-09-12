<?php
namespace Product\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class ProductForm extends Form
{
    public function __construct($categories, $tags)
    {
        parent::__construct('Product');
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
                'value_options' => array(
                    '2' => 'Nieaktywna',
                    '1' => 'Aktywna'
                ),
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
                'value_options' => $categories
            )
        ));

        $this->add(array(
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control select2',
                'name' => 'tag_id',
                'multiple' => 'multiple',
                'id' => 'tag_id',
            ),
            'options' => array(
                'label' => 'Tagi',
                'value_options' => $tags,
                'disable_inarray_validator' => true,
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
            'name' => 'catalog_number',
            'attributes' => array(
                'id' => 'catalog_number',
                'type'  => 'text',
                'placeholder' => 'Wprowadź numer katalogowy'
            ),
            'options' => array(
                'label' => 'Numer katalogowy',
            ),
        ));

        $this->add(array(
            'name' => 'price',
            'attributes' => array(
                'id' => 'price',
                'type'  => 'text',
                'placeholder' => 'Wprowadź cenę'
            ),
            'options' => array(
                'label' => 'Cena',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'bestseller',
            'options' => array(
                'label' => 'Bestseller',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'show_price',
            'options' => array(
                'label' => 'Pokaż cenę',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
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

        $this->add(array(
            'name' => 'filename',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'filename'
            ),
        ));

        $this->add(array(
            'name' => 'upload',
            'attributes' => array(
                'type'  => 'file',
                'id' => 'upload',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Upload',
            ),
        ));
    }
}
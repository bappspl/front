<?php
namespace Team\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class TeamFormFilter extends InputFilter
{
	public function __construct($sm)
	{
        $this->add(array(
            'name'       => 'name',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Uzupełnij pole!'
                        )
                    )
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'url',
            'required' => false,
        ));

        $this->add(array(
            'name'       => 'first_name',
            'required' => false,
        ));

        $this->add(array(
            'name'       => 'function',
            'required' => false,
        ));
	}
}
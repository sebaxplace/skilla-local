<?php
namespace Admin\Form;

use Zend\Form\Form;


class Login extends Form
{

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'nome',
            'options' => array(
                'label' => 'Nome'
            ),
            
            'attributes' => array(
                'class'=>'form-control placeholder-no-fix',
                'placeholder'=> 'Nome',
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'password',
            'options' => array(
                'label' => 'Password'
            ),
            'attributes' => array(
                'class'=>'form-control placeholder-no-fix',
                'placeholder'=> 'Password',
            )
        ));
        $this->add(array(
            'name' => 'send',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Login',
                'class'=>'nostyle'
            )
        ));
    }
}
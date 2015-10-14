<?php
namespace Relatori\Form;

use Zend\Form\Form;


class Buscador extends Form
{

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->add(array(
            'type' => 'text',
            'name' => 'nome',
            'options' => array(
                'label' => 'Nome'
            )
        ));
        $this->add(array(
            'name' => 'send',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Login',
                'class'=>'button success tiny'
            )
        ));
    }
}
<?php
namespace Utenti\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class Registro extends Form {
    public function __construct($name = null){
        parent::__construct($name);
        
        //id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        
        //nombre
        $this->add(array(
            'name'=>'nome',
            'options'=>array(
                'label'=> 'Nome',
            ),
            'attributes'=> array(
                'type'=>'text',
                'class'=>'form-control',
            ),
        ));
        
        //Email
        $this->add(array(
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'options' => array(
                'label' => 'Email'
            ),
            'attributes'=> array(
                 
                'class'=>'form-control',
            ),
        ));
       
        //password
        $this->add(array(
            'type'=>'Zend\Form\Element\Password',
            'name'=>'password',
            'options'=>array(
                'label'=> 'Password',
            ),
            'attributes'=> array(
               
                'class'=>'form-control',
            ),
        ));
        
        //confirmapassword
        $this->add(array(
            'type'=>'Zend\Form\Element\Password',
            'name'=>'confirmarPassword',
            'options'=>array(
                'label'=> 'Confermare Password',
            ),
            'attributes'=> array(
                 
                'class'=>'form-control',
            ),
        ));
       

        //immagine
        $this->add(array(
            'name' => 'immagine',
            'attributes' => array(
                'type'  => 'Zend\Form\Element\File',
            ),
            'options' => array(
                'label' => 'Immagine',
            ),
        ));
        
        
       //stato
        $stato = new Element\Checkbox('stato');
        $stato->setLabel('Stato');
        $stato->setAttribute('class', 'make-switch');
        $this->add($stato);
        
        $this->add(array(
            'name'=>'send',
            'attributes'=>array(
                'type'=>'submit',
                'value'=>'Salva',
                'class'=>'btn green-haze pull-right'
            ),
        ));
    }
}
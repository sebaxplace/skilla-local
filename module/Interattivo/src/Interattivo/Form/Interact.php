<?php
namespace Interattivo\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class Interact extends Form {
    public function __construct($name = null){
        parent::__construct($name);
        
        //id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        //xyz
        $this->add(array(
            'name' => 'xyz',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        //nome
        $this->add(array(
            'name'=>'nome',
            
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        //messaggio
        $this->add(array(
            'name'=>'messaggio',
            'options'=>array(
                'label'=> 'Messaggio',
            ),
            'attributes'=> array(
                'type'=>'text',
                'class'=>'form-control',
            ),
        ));
        
        // Color
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'color',
            'options' => array(
                'label' => 'Colore',
                'empty_option' => 'Scegli un colore =>',
            ),
            'attributes'=> array(
                'class'=>'form-control',
            ),
        ));
        
        // Posterlab
         $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'posterlab',
            'options' => array(
                'label' => 'Posterlab',
                'empty_option' => 'Scegli un posterlab =>',
            ),
            'attributes'=> array(
                'class'=>'form-control',
            ),
        ));
        
       
      
         // tipo
         $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'tipo',
             'options' => array(
                 'label' => 'Tipo',
                 'empty_option' => 'Scegli tipo =>',
             ),
             'attributes'=> array(
                 'class'=>'form-control',
             ),
         ));
        
        
        
        //sessione
        $this->add(array(
            'name' => 'sessione',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        //data
        $this->add(array(
            'name' => 'data',
            'attributes' => array(
                'type' => 'hidden',
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
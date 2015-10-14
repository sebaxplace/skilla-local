<?php
namespace Interattivo\Form;

use Zend\Form\Form;
use Zend\Form\Element;
class Nascosto extends Form {
    public function __construct($name = null){
        parent::__construct($name);
        
        //id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
                'class'=>'id',
                'id'=>'idpostit'
            ),
        ));
        
        //xyz
        $this->add(array(
            'name' => 'xyz',
            'attributes' => array(
                'type' => 'hidden',
                'class'=>'xyz',
            ),
        ));
        
        //nome
        $this->add(array(
            'name'=>'nome',
            'attributes'=> array(
                'type'=>'hidden',
                'id'=>'note-name',
                'class'=>'pr-author',
                
            ),
        ));
        
        //messaggio
       /* $this->add(array(
            'name'=>'messaggio',
            'options'=>array(
                'label'=> 'Messaggio',
            ),
            'attributes'=> array(
                'type'=>'text',
                'class'=>'form-control pr-body pull-left',
                'id'=>'note-body',
                'placeholder'=>'Messaggio',
            ),
        ));*/
        
        $nota = new Element\Textarea('messaggio');
        $nota->setLabel('Contenuto');
        $nota->setAttribute('class', 'mintxt pr-body');
        $nota->setAttribute('placeholder', 'Scrivi qui il testo dell\'intervento');
        $nota->setAttribute('required', 'required');
        $this->add($nota);
        
        //color
        $this->add(array(
            'name' => 'color',
            'attributes' => array(
                'type' => 'hidden',
                'class'=>'color'
            ),
        ));
        
        //posterlab
        $this->add(array(
            'name' => 'posterlab',
            'attributes' => array(
                'type' => 'hidden',
                'class'=>'posterlab',
            ),
        ));
        
       
      
         //tipo
        $this->add(array(
            'name' => 'tipo',
            'attributes' => array(
                'type' => 'hidden',
                'class'=>'tipo',
            ),
        ));
        
        
        
       //sessione
        $this->add(array(
            'name' => 'sessione',
            'attributes' => array(
                'type' => 'hidden',
                'class'=>'sessione'
            ),
        ));
        
        //data
        $this->add(array(
            'name' => 'data',
            'attributes' => array(
                'type' => 'hidden',
                'class'=> 'fecha',
            ),
        ));
        
        //stato
        $this->add(array(
            'name' => 'stato',
            'attributes' => array(
                'type' => 'hidden',
                'class'=>'stato',
            ),
        ));
        
        $this->add(array(
            'name'=>'send',
            'attributes'=>array(
                'type'=>'submit',
                'value'=>'Invia',
                'class'=>'button expand purple',
                'id'=>'note-submit',
                'style'=>'margin:0px !important',
                'onClick'=>'return false',
            ),
        ));
    }
}
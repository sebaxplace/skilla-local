<?php
namespace Contenuti\Form;

//use Zend\Form\Element\File;
use Zend\Form\Element;
use Zend\Form\Form;



class Content extends Form {
    public function __construct($name = null){
        parent::__construct($name);
        
        //id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        
        //titolo
        $this->add(array(
            'name'=>'titolo',
            'options'=>array(
                'label'=> 'Titolo',
            ),
            'attributes'=> array(
                'type'=>'text',
                'class'=>'form-control',
            ),
        ));
        
        //contenuto
        $nota = new Element\Textarea('contenuto');
        $nota->setLabel('Contenuto');
        $nota->setAttribute('class', 'form-control');
        $nota->setAttribute('style', 'min-height:180px;');
        $this->add($nota);
        

        //Background
        $this->add(array(
            'name' => 'background',
            'attributes' => array(
                'type'  => 'Zend\Form\Element\File',
            ),
            'options' => array(
                'label' => 'Background',
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
                 'onChange'=>'javascript:caricaPosizioni()',
                 'id'=>'posterlabs',
            ),
        ));
         
         
         // Posizione
         $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'posizione',
             'options' => array(
                 'label' => 'posizione',
                 'empty_option' => 'Scegli una posizione =>',
                 'disable_inarray_validator' => true,
             ),
             'attributes'=> array(
                 'class'=>'form-control',
                 'id'=>'posizioni',
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
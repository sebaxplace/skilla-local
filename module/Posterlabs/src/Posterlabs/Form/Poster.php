<?php
namespace Posterlabs\Form;

//use Zend\Form\Element\File;
use Zend\Form\Element;
use Zend\Form\Form;



class Poster extends Form {
    public function __construct($name = null){
        parent::__construct($name);
        
        //id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        //statosessione
        $this->add(array(
            'name' => 'statosessione',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        //password visible
        $this->add(array(
            'name' => 'password2',
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
        
        
        // Relatori
         $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'relatori',
            'options' => array(
                'label' => 'Relatori',
                'empty_option' => 'Scegli un relatore =>',
            ),
            'attributes'=> array(
                'class'=>'form-control',
            ),
        ));
        
        
        
      
        
        
        // Steps
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'steps',
            'options' => array(
                'label' => 'Steps',
                'empty_option' => 'Scegli steps =>',
            ),
            'attributes'=> array(
                'class'=>'form-control',
            ),
        ));
        
        //testo
        $nota = new Element\Textarea('nota');
        $nota->setLabel('Nota');
        $nota->setAttribute('class', 'ckeditor form-control');
        $this->add($nota);
        
        //Durata
        $this->add(array(
            'name'=>'durata',
            'options'=>array(
                'label'=> 'Durata Sessione',
            ),
            'attributes'=> array(
                'type'=>'text',
                'class'=>'form-control',
            ),
        ));
        
        
        //Linkedin
        $this->add(array(
            'name'=>'link',
            'options'=>array(
                'label'=> 'Link Report',
            ),
            'attributes'=> array(
                'type'=>'text',
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
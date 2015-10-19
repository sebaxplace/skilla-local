<?php
namespace Interattivo\Form;

use Zend\Form\Form;


class CercaInteractCategoria extends Form
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        // Posterlab
         $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'posterlab',
            'options' => array(
                'label' => 'Posterlab',
                'empty_option' => 'Scegli un posterlab =>',
            ),
            'attributes'=> array(
                'class'=>'form-control input-xlarge select2me',
                'data-placeholder'=>"Select...",
                'required'=>'required'
            ),
        ));
         
         // Sessioni
         $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'sessioni',
             'options' => array(
                 'label' => 'Sessioni',
                 'empty_option' => 'Scegli una sessione =>',
             ),
             'attributes'=> array(
                 'class'=>'form-control input-xlarge select2me',
                 'data-placeholder'=>"Select...",
                 'required'=>'required'
             ),
         ));
         
         // Stato
         $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'stato',
             'options' => array(
                 'label' => 'Stato',
                 'empty_option' => 'Scegli uno stato =>',
             ),
             'attributes'=> array(
                 'class'=>'form-control input-xlarge select2me',
                 'data-placeholder'=>"Select...",
                 'required'=>'required'
             ),
         ));
         
         // Stato
         $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'categoria',
             'options' => array(
                 'label' => 'Stato',
                 'empty_option' => 'Scegli una categoria =>',
             ),
             'attributes'=> array(
                 'class'=>'form-control input-xlarge select2me',
                 'data-placeholder'=>"Select...",
                 'required'=>'required'
             ),
         ));
         
        $this->add(array(
            'name' => 'send',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Cerca',
                'class'=>'btn default inlineblock'
            )
        ));
    }
}
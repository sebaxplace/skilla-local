<?php
namespace Interattivo\Form;

use Zend\Form\Form;


class CercaInteract extends Form
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
                'required'=>'required',
                'onchange' => 'javascript:cargarSelectSessioni()',
                'id' => 'posterlab',
            ),
        ));

         // Sessioni
         $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'sessioni',
             'options' => array(
                 'label' => 'Sessioni',
                 'empty_option' => 'Scegli una Sessione =>',
             ),
             'attributes'=> array(
                 'class'=>'form-control input-xlarge select2me',
                 'data-placeholder'=>"Select...",
                 'required'=>'required',
                 'onchange' => 'javascript:cargarSelectCategoria()',
                 'id'=>'cargasessioni'
             ),
         ));
         
         // Categoria
         $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'categoria',
             'options' => array(
                 'label' => 'Stato',
                 'empty_option' => 'Scegli una Categoria =>',
             ),
             'attributes'=> array(
                 'class'=>'form-control input-xlarge select2me',
                 'data-placeholder'=>"Select...",
                 'onchange' => 'javascript:cargarSelectStato()',
                 'id' => 'cargacategoria',
             ),
         ));
         
         // Stato
         $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'stato',
             'options' => array(
                 'label' => 'Stato',
                 'empty_option' => 'Scegli uno Stato =>',
             ),
             'attributes'=> array(
                 'class'=>'form-control input-xlarge select2me',
                 'data-placeholder'=>"Select...",
                 'onchange' => 'javascript:cargarSelectStato()',
                 'id' => 'cargastato',
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
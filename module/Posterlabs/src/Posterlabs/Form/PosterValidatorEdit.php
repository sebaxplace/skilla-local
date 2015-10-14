<?php
namespace Posterlabs\Form;

use Zend\Validator\StringLength;
use Zend\I18n\Validator\Alnum;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;



class PosterValidatorEdit extends InputFilter{
    protected $opcionesStringLenght = array(
        'min'=>4,
        'max'=>60,
        'messages'=> array(
            StringLength::TOO_SHORT=>'Ci devono essere al meno 4 caratteri',
            StringLength::TOO_LONG=>'Max caratteri 60',
        )
    );
    protected $opcionesAlnum = array(
       'allowWhiteSpace'=>true,
        'messages'=>array(
            'notAlnum'=>'Il valore inserito non è alfanumerico',
        )  
    );
    
    public function  __construct(){
        
        $translator = new \Zend\I18n\Translator\Translator();
        $translator->addTranslationFile('phparray', './module/Posterlabs/language/es.php');

        $translatorMvc = new \Zend\Mvc\I18n\Translator($translator);
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translatorMvc);
        
        
        $nombre = new Input('titolo');
        $nombre->setRequired(true);
        $nombre->getFilterChain()
                ->attachByName('StripTags')
                ->attachByName('StringTrim');
        
        $nombre->getValidatorChain()
                  ->addValidator(new StringLength($this->opcionesStringLenght))
                  ->addValidator(new Alnum($this->opcionesAlnum));
        $this->add($nombre);
        
        
        
        
        
        
        
        
       
        
        
        
        
        
      
        
        
        
        
        $this->add(
            array(
                'name' => 'durata',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Int',
                    ),
                ),
            )
        );
        
        
        
        
        
    }
}
<?php
namespace Contenuti\Form;

use Zend\Validator\StringLength;

use Zend\I18n\Validator\Alnum;

use Zend\Filter\File\RenameUpload;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\Size;
use Zend\InputFilter\Input;




class ContentValidatorEdit extends InputFilter{
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
        $translator->addTranslationFile('phparray', './module/Contenuti/language/es.php');

        $translatorMvc = new \Zend\Mvc\I18n\Translator($translator);
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translatorMvc);
        
       
       $titolo = new Input('titolo');
        $titolo->setRequired(true);
        $titolo->getFilterChain()
                ->attachByName('StripTags')
                ->attachByName('StringTrim');
        
        $titolo->getValidatorChain()
                  ->addValidator(new StringLength($this->opcionesStringLenght))
                  ->addValidator(new Alnum($this->opcionesAlnum));
        $this->add($titolo);
       
        
        
        $this->add(
            array(
                'name' => 'posterlab',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Int',
                    ),
                ),
            )
        );
        
       
      
        
        $this->add(
            array(
                'name' => 'tipo',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Int',
                    ),
                ),
            )
        );
        
        
        $background = new FileInput('background');
        $background->setRequired(false);
        $background->getFilterChain()->attach(new RenameUpload(array(
            'target'=> './httpdocs/immagine/contenuto/contenuto_',
            'use_upload_extension'=>true,
            'randomize'=>true,
        )));
        
        
        $background->getValidatorChain()->attach(new Size(array(
            'max'=>substr(ini_get('upload_max_filesize'), 0, -1).'MB'
        )));
         
        $this->add($background);
     
      
        
        
    }
}
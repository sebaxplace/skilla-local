<?php
namespace Relatori\Form;

use Zend\Validator\StringLength;
use Zend\Validator\Identical;
use Zend\I18n\Validator\Alnum;

use Zend\Filter\File\RenameUpload;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\Size;
use Zend\InputFilter\Input;



class RelatoValidatorEdit extends InputFilter{
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
        $translator->addTranslationFile('phparray', './module/Relatori/language/es.php');

        $translatorMvc = new \Zend\Mvc\I18n\Translator($translator);
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translatorMvc);
        
        $nombre = new Input('nome');
        $nombre->setRequired(true);
        $nombre->getFilterChain()
                ->attachByName('StripTags')
                ->attachByName('StringTrim');
        
        $nombre->getValidatorChain()
                  ->addValidator(new StringLength($this->opcionesStringLenght))
                  ->addValidator(new Alnum($this->opcionesAlnum));
        $this->add($nombre);
        
        
        
        
        $cognome = new Input('cognome');
        $cognome->setRequired(true);
        $cognome->getFilterChain()
        ->attachByName('StripTags')
        ->attachByName('StringTrim');
        
        $nombre->getValidatorChain()
        ->addValidator(new StringLength($this->opcionesStringLenght))
        ->addValidator(new Alnum($this->opcionesAlnum));
        $this->add($cognome);
        
        
        
       /*
        
        $password = new Input('password');
        $password->setRequired(true);
        $password->getValidatorChain()
                 ->addValidator(new StringLength($this->opcionesStringLenght))
                 ->addValidator(new Alnum($this->opcionesAlnum));
       
        $this->add($password);
        
        
        
        
        
        
        
        
        
        $confirmarPassword = new Input('confirmarPassword');
        $confirmarPassword->setRequired(true);
        $confirmarPassword->getValidatorChain()
        ->addValidator(new StringLength($this->opcionesStringLenght))
        ->addValidator(new Alnum($this->opcionesAlnum))
        ->addValidator(new Identical(
            array(
                'token' => 'password',
                'messages'=> array(
                'notSame'=>'I dati sono errati, riprova.',           
                )
            )
        ));
        $this->add($confirmarPassword);
        */
        
        
        $titolo = new Input('titolo');
        $titolo->setRequired(true);
        $titolo->getFilterChain()
        ->attachByName('StripTags')
        ->attachByName('StringTrim');
        
        $nombre->getValidatorChain()
        ->addValidator(new StringLength($this->opcionesStringLenght))
        ->addValidator(new Alnum($this->opcionesAlnum));
        $this->add($titolo);
        
        /*
        $immagine = new Input('immagine');
        $immagine->setRequired(true);
        $this->add($immagine);
        */
        $imagen = new FileInput('immagine');
        $imagen->setRequired(false);
        $imagen->getFilterChain()->attach(new RenameUpload(array(
            'target'=> './public/immagine/relatori/relatori_',
            'use_upload_extension'=>true,
            'randomize'=>true,
        )));
        
        
        $imagen->getValidatorChain()->attach(new Size(array(
            'max'=>substr(ini_get('upload_max_filesize'), 0, -1).'MB'
        )));
        
        /*
        $imagen->getValidatorChain()->attach(new MimeType(array(
            'image/gif',
            'image/jpeg',
            'image/png',
            'enableHeaderCheck'=>true
        )));
        */
       
        $this->add($imagen);
        
        
        
    }
}
<?php
namespace Admin\Form;

use Zend\Validator\StringLength;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;

class LoginValidator extends InputFilter
{

    protected $opcionesStringLenght = array(
        'min'=>4,
        'max'=>60,
        'messages'=> array(
            StringLength::TOO_SHORT=>'Il campo deve avere almeno 4 caratteri',
            StringLength::TOO_LONG=>'Max 60 caratteri',
        )
    );
    protected $opcionesAlnum = array(
        'allowWhiteSpace'=>true,
        'messages'=>array(
            'notAlnum'=>'Il valore non è alfanumerico',
        )
    );
   
    public function __construct()
    {
        $translator = new \Zend\I18n\Translator\Translator();
        $translator->addTranslationFile('phparray', './module/Utenti/language/es.php');
        
        $translatorMvc = new \Zend\Mvc\I18n\Translator($translator);
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translatorMvc);
        
        
        $nombre = new Input('nome');
        $nombre->setRequired(true);
        $nombre->getFilterChain()
                ->attachByName('StripTags')
                ->attachByName('StringTrim');
        
        $nombre->getValidatorChain()
                  ->addValidator(new StringLength($this->opcionesStringLenght));
                  //->addValidator(new Alnum($this->opcionesAlnum));
        $this->add($nombre);
        
        
        
        
       
        
        $password = new Input('password');
        $password->setRequired(true);
        $password->getValidatorChain()
                 ->addValidator(new StringLength($this->opcionesStringLenght));
        $this->add($password);
        
       
        
        
        
        
        
    }
}
                        
<?php
namespace Interattivo\Form;

use Zend\Validator\StringLength;
use Zend\I18n\Validator\Alnum;

use Zend\InputFilter\InputFilter;
//use Zend\Validator\File\MimeType;
use Zend\InputFilter\Input;

class InteractValidator extends InputFilter{
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
$translator->addTranslationFile('phparray', './module/Utenti/language/es.php');

$translatorMvc = new \Zend\Mvc\I18n\Translator($translator);
\Zend\Validator\AbstractValidator::setDefaultTranslator($translatorMvc);
        
        $nome = new Input('nome');
        $nome->setRequired(true);
        $nome->getFilterChain()
                ->attachByName('StripTags')
                ->attachByName('StringTrim');
        
        $nome->getValidatorChain()
                  ->addValidator(new StringLength($this->opcionesStringLenght))
                  ->addValidator(new Alnum($this->opcionesAlnum));
        $this->add($nome);
        
        $messaggio = new Input('messaggio');
        $messaggio->setRequired(true);
        
        $this->add($messaggio);
        
       
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
        
        
        
        
        $this->add(
            array(
                'name' => 'sessione',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Int',
                    ),
                ),
            )
        );
       
        /*$this->add(
            array(
                'name' => 'categoria',
                'options' => array(
                    'disable_inarray_validator' => true,
                ),
            )
        );*/
        
        
        
    }
}
<?php
namespace Utenti\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Utenti\Model\Entity\Estudiante;
use Utenti\Form\Estudiante as EstudianteForm;
use Utenti\Form\EstudianteValidator;

class EstudianteController extends AbstractActionController{
    
    public function indexAction(){
        $form = $this->getForm();
        
        $estudiante = new Estudiante();
        $estudiante->setDireccion('Avenida Gamonal');
        $estudiante->setTemas(array(1,4));
        $estudiante->setGenero('H');
        $estudiante->setRecibeNewsletter(true);
        $estudiante->setExperienciaZend(array('Zend MVC'));
        $estudiante->setValorSecreto('Algun Valor Oculto');
        
        $form->bind($estudiante);
        
        return new ViewModel(array('titulo'=>'Ejemplo de elementos form de zend - Formulario', 'form'=>$form));
    }
    
    public function registrarAction(){
        if(!$this->getRequest()->isPost()){
            $this->redirect()->toRoute('usuarios', array('controller'=>'estudiante'));
        }
        
        $postParams = $this->request->getPost();
        $form = $this->getForm();
        $form->setInputFilter(new EstudianteValidator());
        
        $form->setData($postParams);
        
        if(!$form->isValid()){
            $modelView = new ViewModel(array('titulo'=>'Ejemplo de elementos form de zend - Formulario', 'form'=>$form));
            $modelView->setTemplate('utenti/estudiante/index');
            return $modelView;
        }
    
        $values = $form->getData();
        
        $estudiante = new Estudiante();
        $estudiante->exchangeArray($values);
        return new ViewModel(array('titulo'=>'Detalle del Estudiante', 'estudiante'=> $estudiante));
    }
    
    
    private function getForm(){
        $form = new EstudianteForm();
        $form->get('pais')->setValueOptions($this->llenarPaises());
        $form->get('experienciaZend')->setValueOptions($this->llenarexperienciaZend());
        $form->get('temas')->setValueOptions($this->llenarListaTemas());
        $form->get('genero')->setValueOptions(array('H'=>'Hombre', 'M'=>'Mujer'));
        $form->get('numeroFavorito')->setValueOptions($this->llenarListaNumeros());
        return $form;
    }
    
    private function llenarListaTemas(){
        $listaTemas = array();
        $listaTemas[1]='Matematicas';
        $listaTemas[2]='Ciencia';
        $listaTemas[3]='Arte';
        $listaTemas[4]='Musica';
        $listaTemas[5]='Deporte';
    
        return $listaTemas;
    }
    
    private function llenarListaNumeros(){
        $numeros = array();
        $numeros[1]='Numero 1';
        $numeros[2]='Numero 2';
        $numeros[3]='Numero 3';
        $numeros[4]='Numero 4';
        $numeros[5]='Numero 5';
        $numeros[6]='Numero 6';
        $numeros[7]='Numero 7';
        
        return $numeros;
    }
    
    
    private function llenarexperienciaZend(){
        $experienciaZend = array();
        $experienciaZend['Zend Db'] = 'Zend Db';
        $experienciaZend['Zend Form'] = 'Zend Form';
        $experienciaZend['Zend MVC'] = 'Zend MVC';
        $experienciaZend['Zend Auth'] = 'Zend Auth';
        
        return $experienciaZend;
    }
    
    private function llenarPaises(){
        $paises = array();
        $paises['CL'] = 'Chile';
        $paises['ES'] = 'Espana';
        $paises['MX'] = 'Mexico';
        $paises['US'] = 'United States';
        $paises['AR'] = 'Argentina';
        
        return $paises;
        
    }
    
    
    
}
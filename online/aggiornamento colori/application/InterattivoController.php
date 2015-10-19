<?php
/**
 * Zend Framework (http://framework.zend.com/)
*
* @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
* @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ArrayObject;
use Interattivo\Model\Entity\Interattivo;
use Interattivo\Model\Interact as InteractService;
use Interattivo\Form\Interact;
use Interattivo\Form\CercaInteract;
use Interattivo\Form\CercaInteractSession;
use Interattivo\Form\InteractValidator;
use DateTime;

class InterattivoController extends AbstractActionController
{
    
    private $InterattivoDao;
    private $config;
    private $registro;
    
    public function getConfig()
    {
        if (!$this->config) {
            $sm = $this->getServiceLocator();
            $this->config = $sm->get('ConfigInterattivo');
        }
    
    
        return $this->config;
         
    }
    
    public function setRegistro(InteractService $registro) {
        $this->registro = $registro;
    }
    
    public function getInterattivoDao() {
        if (!$this->InterattivoDao) {
            $sm = $this->getServiceLocator();
            $this->InterattivoDao = $sm->get('Interattivo\Model\InterattivoDao');
        }
        return $this->InterattivoDao;
    }
    
    public function getContenutiDao() {
        if (!$this->contenutiDao) {
            $sm = $this->getServiceLocator();
            $this->contenutiDao = $sm->get('Contenuti\Model\ContenutiDao');
        }
    
        return $this->contenutiDao;
    }
    public function getPosterlabsDao() {
        if (!$this->posterlabsDao) {
            $sm = $this->getServiceLocator();
            $this->posterlabsDao = $sm->get('Posterlabs\Model\PosterlabsDao');
        }
         
        return $this->posterlabsDao;
    }
    
    
    
    public function indexAction()
    {
        $form = new CercaInteract("CercaInteract");
        
        $form->get('posterlab')->setValueOptions($this->getContenutiDao()->obtenerPosterlabsSelect());
        $this->initAjaxContext();
        return new ViewModel(array('usuarios' => $this->getInterattivoDao()->tutti(),'titulo' => 'Interattivo','form'=>$form));
    }
   
    public function verAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        $id = (int) $this->params()->fromRoute("id", 0);
    
        $usuario = $this->getInterattivoDao()->todosPerId($id);
        if (null === $usuario) {
            return $this->redirect()->toRoute('interattivo', array('controller' => 'index', 'action' => 'listar'));
        }
    
        $volver = $this->config['parametros']['ver']['volver'];
        $this->layout()->volver = $volver;
    
        $detalle = $this->config['parametros']['ver']['detalle'];
        $this->layout()->detalle = $detalle;
       
        return new ViewModel(array('usuario' => $usuario,
            'titulo' => 'Dettaglio messaggio',
            'volver' => $volver,
            'sezione' =>'Interattivo'
        ));
    }
    
     
    
    public function crearAction()
    {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        $patron = 'Y-m-d H:i';
        $fecha = new DateTime();
        $xposition = rand(2, 1000);
        $yposition = rand(3, 850);
        $zposition = rand(1, 100);
        $form = $this->getForm();
        $form->get('posterlab')->setValueOptions($this->getInterattivoDao()->obtenerPosterlabsSelect());
        //print_r($this->llenarListaTipo());die;
        $form->get('tipo')->setValueOptions($this->llenarListaTipo());
        $form->get('data')->setValue($fecha->format($patron));
        $form->get('color')->setValueOptions($this->llenarColor());
        $form->get('xyz')->setValue($xposition.'x'.$yposition.'x'.$zposition);
        $form->get('sessione')->setValue(0);
        $form->get('stato')->setValue(1);
        $form->get('nome')->setValue('default');
        $form->setInputFilter(new InteractValidator());

        return array(
            'form' => $form, 'titulo'=>'Nuovo Messaggio', 'volver'=>'Indietro','sezione' =>'Interattivo',
        );
    
    
    }
    
    public function guardarAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
    
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('admin', array('controller' => 'interattivo', 'action'=>'index'));
        }
        
        $xposition = rand(2, 1000);
        $yposition = rand(3, 850);
        $zposition = rand(1, 100);
        
        //print_r($xposition.'x'.$yposition.'x'.$zposition);die;
        
        $patron = 'Y-m-d H:i';
        $fecha = new DateTime();
        $form =  new Interact("interact");
        $form->get('posterlab')->setValueOptions($this->getInterattivoDao()->obtenerPosterlabsSelect());
        //print_r($this->llenarListaTipo());die;
        $form->get('tipo')->setValueOptions($this->llenarListaTipo());
        $form->get('data')->setValue($fecha->format($patron));
        $form->get('sessione')->setValue(0);
        $form->get('color')->setValueOptions($this->llenarColor());
        $form->get('nome')->setValue('default');
        $form->get('xyz')->setValue($xposition.'x'.$yposition.'x'.$zposition);
    
        $form->setInputFilter(new InteractValidator());
         
        $data = $this->getRequest()->getPost()->toArray();
      
        $form->setData($data);
         
        // Validando el form
        if (!$form->isValid()) {
            $modelView = new ViewModel(array('title' => 'Aggiorno', 'form' => $form, 'volver'=>'Indietro','titulo' => 'Modifica contenuto',));
            $modelView->setTemplate('admin/interattivo/crear');
            return $modelView;
        }
        $dataForms = $form->getData();
        $dataForms['posterlab_id'] = $dataForms['posterlab'];
        $producto = new Interattivo();
        //print_r($dataForms);die;
        $producto->exchangeArray($dataForms);
        $this->getInterattivoDao()->salvare($producto);
        return $this->redirect()->toRoute('admin', array('controller' => 'interattivo', 'action' => 'index'));
        }
    
    
    public function editarAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $volver = $this->config['parametros']['ver']['volver'];
        $this->layout()->volver = $volver;
        $this->layout()->menu = $menu;
    
        $id = (int) $this->params()->fromRoute('id', 0);
    
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('controller' => 'interattivo', 'action' => 'index'));
        }
    
        $xposition = rand(2, 1000);
        $yposition = rand(3, 850);
        $zposition = rand(1, 100);
        $form = new Interact("interact");
        $form->get('posterlab')->setValueOptions($this->getInterattivoDao()->obtenerPosterlabsSelect());
         
        $form->get('color')->setValueOptions($this->llenarColor());
        $form->get('xyz')->setValue($xposition.'x'.$yposition.'x'.$zposition);
        $form->get('tipo')->setValueOptions($this->llenarListaTipo());
    
        $producto = $this->getInterattivoDao()->todosPerId($id);
        
        $form->bind($producto);
        $form->get('send')->setAttribute('value', 'Salva');
    
        $modelView = new ViewModel(array('sezione'=>'Interattivo','titulo' => 'Modifica Messaggio', 'form' => $form,  'volver'=>$volver,));
        $modelView->setTemplate('admin/interattivo/crear');
        return $modelView;
    }
    
    public function eliminarAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('controller' => 'interattivo', 'action' => 'index'));
        }
        $producto = new Interattivo();
        $producto->setId($id);
    
        $this->getInterattivoDao()->eliminare($producto);
        return $this->redirect()->toRoute('admin', array('controller' => 'interattivo', 'action' => 'index'));
    }
    
    public function cercaAction() {
        
        $idposterlab = $this->getRequest()->getPost("posterlab");
    
        $nombreactualposter = $this->getContenutiDao()->obtenerPosterlabActual($idposterlab);
        $nombresito = $nombreactualposter->getTitolo();
    
        
        $tablastato = $this->config['parametros']['tabla']['stato'];
        $this->layout()->tablastato = $tablastato;
    
        $form = new CercaInteractSession('CercaInteractSession');
        $form->get('posterlab')->setValueOptions($this->getContenutiDao()->obtenerPosterlabsSelect());
        $form->get('posterlab')->setValue($idposterlab);
        $form->get('sessioni')->setValueOptions($this->getInterattivoDao()->obtenerSessioniSelect($idposterlab));
         
        //print_r($this->getInterattivoDao()->obtenerSessioniSelect());die;
    
        $placenombre = $this->config['parametros']['placeholder']['nombre'];
        $placebuscar = $this->config['parametros']['placeholder']['buscar'];
        $placever = $this->config['parametros']['placeholder']['ver'];
        $encontrados = $this->config['parametros']['buscar']['encontrados'];
        $this->layout()->placenom = $placenombre;
        $this->layout()->busca = $placebuscar;
        $this->layout()->ver = $placever;
        $this->layout()->encontrados = $encontrados;
    
        if (null === $idposterlab || empty($idposterlab)) {
            return $this->redirect()->toRoute('admin', array('controller' => 'contenuti', 'action' => 'index'));
        }
    
        $listaUsuario = $this->getInterattivoDao()->cercaPerPosterlab($idposterlab);
        $viewModel = new ViewModel(array(
            'form'=> $form,
            'usuarios' => $listaUsuario,
            'titulo' => 'Interattivo',
            'titulo1' => ucfirst($nombresito),
            'titulos' => 'Trovati (' . $listaUsuario->count() . ') ',
            'title' => 'Lista Interattivo',
            
    
            'placenom' => $placenombre,
            'placebusca' => $placebuscar,
            'placever' => $placever,
            'posicion' => 'Posizione',
    
            'tablastato'=>$tablastato,
        ));
    
        //$viewModel->setTemplate("admin/interattivo/index");
    
        return $viewModel;
    }
    
    public function guardacatAction() {
    
        if (!$this->request->isPost()) {
            return"error";
        }
        $data = $this->getRequest()->getPost()->toArray();
    
        $id  = (int) $data['id'];
    
    
        $producto = new Interattivo();
        $producto->exchangeArray($data);
        $this->getInterattivoDao()->salvare3($producto);
        print_r($data);die;
        return $this;
    }
    
    public function cercasessioniAction() {
    
        $idposterlab = $this->getRequest()->getPost("posterlab");
        $idsession = $this->getRequest()->getPost("sessioni");
    
        $nombreactualposter = $this->getContenutiDao()->obtenerPosterlabActual($idposterlab);
        $nombresito = $nombreactualposter->getTitolo();
    
    
        $tablastato = $this->config['parametros']['tabla']['stato'];
        $this->layout()->tablastato = $tablastato;
    
        $form = new CercaInteractSession('CercaInteractSession');
        $form->get('posterlab')->setValueOptions($this->getContenutiDao()->obtenerPosterlabsSelect());
        $form->get('posterlab')->setValue($idposterlab);
        $form->get('sessioni')->setValueOptions($this->getInterattivoDao()->obtenerSessioniSelect($idposterlab));
        $form->get('sessioni')->setValue($idsession);
        //print_r($this->getInterattivoDao()->obtenerSessioniSelect());die;
    
        $placenombre = $this->config['parametros']['placeholder']['nombre'];
        $placebuscar = $this->config['parametros']['placeholder']['buscar'];
        $placever = $this->config['parametros']['placeholder']['ver'];
        $encontrados = $this->config['parametros']['buscar']['encontrados'];
        $this->layout()->placenom = $placenombre;
        $this->layout()->busca = $placebuscar;
        $this->layout()->ver = $placever;
        $this->layout()->encontrados = $encontrados;
    
        if (null === $idposterlab || empty($idposterlab)) {
            return $this->redirect()->toRoute('admin', array('controller' => 'contenuti', 'action' => 'index'));
        }
    
        $listaUsuario = $this->getInterattivoDao()->posterysession($idposterlab, $idsession);
        $viewModel = new ViewModel(array(
            'form'=> $form,
            'usuarios' => $listaUsuario,
            'titulo' => 'Interattivo',
            'titulo1' => ucfirst($nombresito),
            'titulos' => 'Trovati (' . $listaUsuario->count() . ') ',
            'title' => 'Lista Interattivo',
    
    
            'placenom' => $placenombre,
            'placebusca' => $placebuscar,
            'placever' => $placever,
            'posicion' => 'Posizione',
    
            'tablastato'=>$tablastato,
        ));
    
        $viewModel->setTemplate("admin/interattivo/cerca");
    
        return $viewModel;
    }
    
    
    
    private function llenarListaSteps() {
    
        // Data reference de números para radiobuttons
        $numeros = array();
        $numeros[1] = "1";
        $numeros[2] = "2";
        $numeros[3] = "3";
        $numeros[4] = "4";
        $numeros[5] = "5";
        $numeros[6] = "6";
        $numeros[7] = "7";
        $numeros[8] = "8";
        $numeros[9] = "9";
        $numeros[10] = "10";
    
        return $numeros;
    }
    private function llenarColor() {
    
        // Data reference de números para radiobuttons
        $color = array();
        $color['yellow'] = "yellow";
        $color['blue'] = "blue";
        
        return $color;
    }
    
    private function llenarListaTipo() {
    
        // Data reference de paises para list box
        $tipo = array();
        $tipo[1] = "Domanda";
        $tipo[2] = "Risposta";
    
        return $tipo;
    }
    private function datadehoy() {
    
        $patron = 'Y-m-d H:i';
        $fecha = new DateTime();
        return $fecha->format($patron);
    }
    
    
    
    private function getForm() {
        $form = new Interact("Interact");
         return $form;
    }
    
    public function initAjaxContext() {
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
    
        $script = $renderer->render('admin/interattivo/js/categoria');
         
        $renderer->headScript()->appendScript($script, 'text/javascript');
    
        return $renderer;
    }
}


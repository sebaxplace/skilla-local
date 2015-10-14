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
use Posterlabs\Form\Poster;
use Posterlabs\Model\Entity\Posterlabs;
use Posterlabs\Form\PosterValidator;
use Posterlabs\Form\PosterValidatorEdit;
use Posterlabs\Model\Poster as PosterService;
//use Zend\Crypt\Password\Bcrypt;

class PosterlabsController extends AbstractActionController
{
    
    private $usuarioDao;
    private $config;
    private $registro;
    
    public function getConfig()
    {
        if (!$this->config) {
            $sm = $this->getServiceLocator();
            $this->config = $sm->get('ConfigIniAdmin');
        }
    
    
        return $this->config;
         
    }
    public function setRegistro(PosterService $registro) {
        $this->registro = $registro;
    }
    
    public function getPosterlabsDao() {
        if (!$this->posterlabsDao) {
            $sm = $this->getServiceLocator();
            $this->posterlabsDao = $sm->get('Posterlabs\Model\PosterlabsDao');
        }
        return $this->posterlabsDao;
    }
    
    public function getRelatoriDao() {
        if (!$this->relatoriDao) {
            $sm = $this->getServiceLocator();
            $this->relatoriDao = $sm->get('Relatori\Model\RelatoriDao');
        }
        return $this->relatoriDao;
    }
    
    
    public function indexAction()
    {
        $this->getConfig();
        $titulo = 'Posterlabs';
        $titulohead = $this->config['parametros']['head']['titulo2'];
        
        $placenombre = $this->config['parametros']['placeholder']['nombre'];
        $placebuscar = $this->config['parametros']['placeholder']['buscar'];
        $placever = $this->config['parametros']['placeholder']['ver'];
        
        $tablaid = $this->config['parametros']['tabla']['id'];
        $tablanombre = $this->config['parametros']['tabla']['nombre'];
        $tablarelatori = $this->config['parametros']['tabla']['relatori'];
        $tabladurata = $this->config['parametros']['tabla']['durata'];
        $tabladetalle = $this->config['parametros']['tabla']['detalle'];
        $tablamodifica = $this->config['parametros']['tabla']['modifica'];
        $tablaelimina = $this->config['parametros']['tabla']['elimina'];
        $tablasteps = $this->config['parametros']['tabla']['steps'];
        
        $mensaje = $this->config['parametros']['mvc']['application']['index']['mensaje'];
        
        $menu1 = $this->config['parametros']['menu1'];
        
        $this->layout()->head = $titulohead;
        
        $this->layout()->placenom = $placenombre;
        $this->layout()->busca = $placebuscar;
        $this->layout()->ver = $placever;
        
        $this->layout()->tablaid = $tablaid;
        $this->layout()->tablanombre = $tablanombre;
        $this->layout()->tablarelatori = $tablarelatori;
        $this->layout()->tablaemail = $tabladurata;
        $this->layout()->tabladetalle = $tabladetalle;
        $this->layout()->tablamodifica = $tablamodifica;
        $this->layout()->tablaelimina = $tablaelimina;
        $this->layout()->tablasteps = $tablasteps;
        
        $this->layout()->menu = $menu1;
         
        $this->layout()->algunmensaje = $mensaje;
        $this->layout()->head2 = $titulohead;
        $tablastato = $this->config['parametros']['tabla']['stato'];
        $this->layout()->tablastato = $tablastato;
        return new ViewModel(array(
                    
                    'title' => 'Lista Posterlabs',
                    'usuarios' => $this->getPosterlabsDao()->tutti(),
                    'titulo' => $titulo,
            
            'placenom' => $placenombre,
            'placebusca' => $placebuscar,
            'placever' => $placever,
            
            'tablaid'=>$tablaid,
            'tablanombre'=>$tablanombre,
            'tablaapellido'=>$tablarelatori,
            'tablaemail'=>$tabladurata,
            'tablamodifica'=>$tablamodifica,
            'tablaelimina'=>$tablaelimina,
            'tabladetalle'=>$tabladetalle,
            'tablasteps'=>$tablasteps,
            'algunmensaje'=>$mensaje,
            'menu' => $menu1,
            'tablastato'=>$tablastato,
                ));
    }
    
    public function verAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        $id = (int) $this->params()->fromRoute("id", 0);
    
        $usuario = $this->getPosterlabsDao()->tuttiPerId($id);
      // print_r($usuario);
       // $usuario['relatori_id'] = $usuario['relatori'];
        if (null === $usuario) {
            return $this->redirect()->toRoute('posterlabs', array('controller' => 'index', 'action' => 'listar'));
        }
        
        $volver = $this->config['parametros']['ver']['volver'];
        $this->layout()->volver = $volver;
    
        $detalle = $this->config['parametros']['ver']['detalle'];
        $this->layout()->detalle = $detalle;
    
        return new ViewModel(array('usuario' => $usuario,
            'titulo' => $detalle,
            'volver' => $volver,
            'sezione' =>'Posterlabs'
        ));
    }
    
   
    
    public function crearAction()
    {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        $form = $this->getForm();
        $form->get('relatori')->setValueOptions($this->getRelatoriDao()->obtenerRelatoriSelect());
        $form->get('steps')->setValueOptions($this->llenarListaNumeros());
        $form->get('statosessione')->setValue(0);
        $form->get('stato')->setValue(1);
        return array(
            'form' => $form, 'titulo'=>'Nuovo Posterlab', 'volver'=>'Indietro','sezione' =>'Posterlabs',
        );
        
        
    }
    
    public function guardarAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('admin', array('controller' => 'posterlabs', 'action'=>'index'));
        }
        $data = $this->getRequest()->getPost();
        
        $form = $this->getForm();
        $form->get('relatori')->setValueOptions($this->getRelatoriDao()->obtenerRelatoriSelect());
        $form->get('steps')->setValueOptions($this->llenarListaNumeros());
        
        if(!$data['id']){
             
            $form->setInputFilter(new PosterValidator());
        }else{
            $form->setInputFilter(new PosterValidatorEdit());
        }
        
        // Obtenemos los datos desde el Formulario con POST data:
        
        
        
        $form->setData($data); 
        // Validando el form
        if (!$form->isValid()) {
            $modelView = new ViewModel(array('title' => 'Aggiorno', 'form' => $form, 'volver'=>'Indietro','titulo' => 'Modifica Posterlab'));
            $modelView->setTemplate('admin/posterlabs/crear');
            return $modelView;
        }
        /*
        $bcrypt = new Bcrypt(array(
            'salt' => 'aleatorio_salt_pruebas_victor',
            'cost' => 5));
        $securePass = $bcrypt->create($data['password']);
        
        
        $segura = array('password'=>$securePass);
        $segura2 = array('confirmarPassword'=>$securePass);
        */
        
        
        
        $dataForms = $form->getData();

        $dataForms['relatori_id'] = $dataForms['relatori'];
        //$dataForms['password2'] = $data['password'];
        //$dati = array_replace($dataForms, $segura, $segura2);
        
        
        
        $producto = new Posterlabs();
        $producto->exchangeArray($dataForms);
        //print_r($dati);die;
        $this->getPosterlabsDao()->salvare($producto);
        return $this->redirect()->toRoute('admin', array('controller' => 'posterlabs', 'action' => 'index'));
    }
    
    
    public function editarAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $volver = $this->config['parametros']['ver']['volver'];
        $this->layout()->volver = $volver;
        $this->layout()->menu = $menu;
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('controller' => 'posterlabs', 'action' => 'index'));
        }
    
        $form = new Poster("poster");
        $form->get('relatori')->setValueOptions($this->getRelatoriDao()->obtenerRelatoriSelect());
        $form->get('steps')->setValueOptions($this->llenarListaNumeros());
        
        $producto = $this->getPosterlabsDao()->tuttiPerId($id);
    
        $form->bind($producto);
        $form->get('send')->setAttribute('value', 'Salva');
    
        $modelView = new ViewModel(array('sezione'=>'Posterlabs','titulo' => 'Modifica Posterlab', 'form' => $form,  'volver'=>$volver,));
        $modelView->setTemplate('admin/posterlabs/crear');
        return $modelView;
    }
    
    public function eliminarAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('controller' => 'posterlabs', 'action' => 'index'));
        }
        $producto = new Posterlabs();
        $producto->setId($id);
    
        $this->getPosterlabsDao()->eliminare($producto);
        return $this->redirect()->toRoute('admin', array('controller' => 'posterlabs', 'action' => 'index'));
    }
    
    private function llenarListaNumeros() {
    
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
    
    private function getForm() {
        $form = new Poster("poster");
        $form->get('relatori')->setValueOptions($this->getPosterlabsDao()->obtenerRelatoriSelect());
        return $form;
    }
}


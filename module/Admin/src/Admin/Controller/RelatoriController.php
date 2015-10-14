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
use Relatori\Form\Relato;
use Relatori\Model\Entity\Relatori;
use Relatori\Model\Relato as RelatoriService;
use Relatori\Form\RelatoValidator;
use Relatori\Form\RelatoValidatorEdit;
use Zend\Crypt\Password\Bcrypt;

class RelatoriController extends AbstractActionController
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
    public function setRelato(RelatoriService $relatori) {
        $this->relato = $relatori;
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
        $titulo = 'Relatori';
        $titulohead = $this->config['parametros']['head']['titulo2'];
        
        $placenombre = $this->config['parametros']['placeholder']['nombre'];
        $placebuscar = $this->config['parametros']['placeholder']['buscar'];
        $placever = $this->config['parametros']['placeholder']['ver'];
        
        $tablaid = $this->config['parametros']['tabla']['id'];
        $tablanombre = $this->config['parametros']['tabla']['nombre'];
        $tablaapellido = $this->config['parametros']['tabla']['apellido'];
        $tablaemail = $this->config['parametros']['tabla']['email'];
        $tabladetalle = $this->config['parametros']['tabla']['detalle'];
        $tablamodifica = $this->config['parametros']['tabla']['modifica'];
        $tablaelimina = $this->config['parametros']['tabla']['elimina'];
        
        $mensaje = $this->config['parametros']['mvc']['application']['index']['mensaje'];
        
        $menu1 = $this->config['parametros']['menu1'];
        
        $this->layout()->head = $titulohead;
        
        $this->layout()->placenom = $placenombre;
        $this->layout()->busca = $placebuscar;
        $this->layout()->ver = $placever;
        
        $this->layout()->tablaid = $tablaid;
        $this->layout()->tablanombre = $tablanombre;
        $this->layout()->tablaapellido = $tablaapellido;
        $this->layout()->tablaemail = $tablaemail;
        $this->layout()->tabladetalle = $tabladetalle;
        $this->layout()->tablamodifica = $tablamodifica;
        $this->layout()->tablaelimina = $tablaelimina;
        
        $this->layout()->menu = $menu1;
         
        $this->layout()->algunmensaje = $mensaje;
        $this->layout()->head2 = $titulohead;
        $tablastato = $this->config['parametros']['tabla']['stato'];
        $this->layout()->tablastato = $tablastato;
        return new ViewModel(array(
                    
                    'title' => 'Lista relatori',
                    'usuarios' => $this->getRelatoriDao()->tutti(),
                    'titulo' => $titulo,
            
            'placenom' => $placenombre,
            'placebusca' => $placebuscar,
            'placever' => $placever,
            
            'tablaid'=>$tablaid,
            'tablanombre'=>$tablanombre,
            'tablaapellido'=>$tablaapellido,
            'tablaemail'=>$tablaemail,
            'tablamodifica'=>$tablamodifica,
            'tablaelimina'=>$tablaelimina,
            'tabladetalle'=>$tabladetalle,
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
    
        $usuario = $this->getRelatoriDao()->tuttiPerId($id);
    
        if (null === $usuario) {
            return $this->redirect()->toRoute('relatori', array('controller' => 'index', 'action' => 'listar'));
        }
    
        $volver = $this->config['parametros']['ver']['volver'];
        $this->layout()->volver = $volver;
    
        $detalle = $this->config['parametros']['ver']['detalle'];
        $this->layout()->detalle = $detalle;
    
        return new ViewModel(array('usuario' => $usuario,
            'titulo' => $detalle,
            'volver' => $volver,
            'sezione' =>'Relatori'
        ));
    }
    
    
    
    
    public function crearAction()
    {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        $form = new Relato('relato');
        $form->get('stato')->setValue(1);
        return array(
            'form' => $form, 'titulo'=>'Nuovo Relatore', 'volver'=>'Indietro','sezione'=>'Relatori',
        );
        
        
    }
    
    public function guardarAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('admin', array('controller' => 'relatori', 'action'=>'index'));
        }

        $form = new Relato('Relato');

        
       
        $data = array_merge_recursive($this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
        if(!$data['id']){
           
            $form->setInputFilter(new RelatoValidator());
        }else{
            $form->setInputFilter(new RelatoValidatorEdit());
        }
        $form->setData($data); 
       
        // Validando el form
        if (!$form->isValid()) {
           // $id = $data['id'];
            //$imagen = $this->getRelatoriDao()->tuttiPerId($id)->getImmagine();
            $modelView = new ViewModel(array('title' => 'Aggiorno', 'form' => $form, 'volver'=>'Indietro','titulo' => 'Modifica utente'));
            $modelView->setTemplate('admin/relatori/crear');
            return $modelView;
        }
        
       
        $bcrypt = new Bcrypt(array(
            'salt' => 'aleatorio_salt_pruebas_victor',
            'cost' => 5));
        $securePass = $bcrypt->create($data['password']);
        
        
        $segura = array('password'=>$securePass);
        $segura2 = array('confirmarPassword'=>$securePass);
        
        
        
        
        $dataForms = $form->getData();
        $datoimagen = $dataForms['immagine']['tmp_name'];
        $datospimagen = array('immagine'=>$datoimagen);
        
        $dati = array_replace($dataForms, $datospimagen, $segura, $segura2);
        
        
        $producto = new Relatori();
        $producto->exchangeArray($dati);
        $this->getRelatoriDao()->salvare($producto);
        return $this->redirect()->toRoute('admin', array('controller' => 'relatori', 'action' => 'index'));
       
    }
    
    
    public function editarAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $volver = $this->config['parametros']['ver']['volver'];
        $this->layout()->volver = $volver;
        $this->layout()->menu = $menu;
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('controller' => 'relatori', 'action' => 'index'));
        }
    
        $form = new Relato("relato");
    
        $producto = $this->getRelatoriDao()->tuttiPerId($id);
        $imagen = $this->getRelatoriDao()->tuttiPerId($id)->getImmagine();
        $form->bind($producto);
        $form->get('send')->setAttribute('value', 'Salva');
    
        $modelView = new ViewModel(array('sezione'=>'Relatori','titulo' => 'Modifica Relatore', 'form' => $form,  'volver'=>$volver, 'imagen'=>$imagen));
        $modelView->setTemplate('admin/relatori/crear');
        return $modelView;
    }
    
    public function eliminarAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('controller' => 'relatori', 'action' => 'index'));
        }
        $producto = new Relatori();
        $producto->setId($id);
    
        $this->getRelatoriDao()->eliminare($producto);
        return $this->redirect()->toRoute('admin', array('controller' => 'relatori', 'action' => 'index'));
    }
    
    

}


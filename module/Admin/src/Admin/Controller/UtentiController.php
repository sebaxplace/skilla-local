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
use Utenti\Form\Registro;
use Utenti\Model\Entity\Utenti;
use Utenti\Form\RegistroValidator;
//use Utenti\Form\Buscador;
use Utenti\Model\Registro as RegistroService;
use Zend\Crypt\Password\Bcrypt;

class UtentiController extends AbstractActionController
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
    public function setRegistro(RegistroService $registro) {
        $this->registro = $registro;
    }
    public function getUtentiDao() {
        if (!$this->utentiDao) {
            $sm = $this->getServiceLocator();
            $this->utentiDao = $sm->get('Utenti\Model\UtentiDao');
        }
        return $this->utentiDao;
    }
    
    
    public function indexAction()
    {
        $this->getConfig();
        $titulo = 'Utenti';
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
                    
                    'title' => 'Lista utenti',
                    'usuarios' => $this->getUtentiDao()->tutti(),
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
    
        $usuario = $this->getUtentiDao()->tuttiPerId($id);
    
        if (null === $usuario) {
            return $this->redirect()->toRoute('utenti', array('controller' => 'index', 'action' => 'listar'));
        }
    
        $volver = $this->config['parametros']['ver']['volver'];
        $this->layout()->volver = $volver;
    
        $detalle = $this->config['parametros']['ver']['detalle'];
        $this->layout()->detalle = $detalle;
    
        return new ViewModel(array('usuario' => $usuario,
            'titulo' => $detalle,
            'volver' => $volver,
            'sezione' =>'Utenti'
        ));
    }
    
   /* public function buscarAction() {
        $this->getConfig();
        $form = new Buscador("buscador");
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        $placenombre = $this->config['parametros']['placeholder']['nombre'];
        $placebuscar = $this->config['parametros']['placeholder']['buscar'];
        $placever = $this->config['parametros']['placeholder']['ver'];
        $encontrados = $this->config['parametros']['buscar']['encontrados'];
        
        $tablaid = $this->config['parametros']['tabla']['id'];
        $tablanombre = $this->config['parametros']['tabla']['nombre'];
        $tablaapellido = $this->config['parametros']['tabla']['apellido'];
        $tablaemail = $this->config['parametros']['tabla']['email'];
        $tabladetalle = $this->config['parametros']['tabla']['detalle'];
        $tablamodifica = $this->config['parametros']['tabla']['modifica'];
        $tablaelimina = $this->config['parametros']['tabla']['elimina'];
        $tablastato = $this->config['parametros']['tabla']['stato'];
        
        $this->layout()->placenom = $placenombre;
        $this->layout()->busca = $placebuscar;
        $this->layout()->ver = $placever;
        $this->layout()->encontrados = $encontrados;
        
        $this->layout()->tablaid = $tablaid;
        $this->layout()->tablanombre = $tablanombre;
        $this->layout()->tablaapellido = $tablaapellido;
        $this->layout()->tablaemail = $tablaemail;
        $this->layout()->tabladetalle = $tabladetalle;
        $this->layout()->tablamodifica = $tablamodifica;
        $this->layout()->tablaelimina = $tablaelimina;
        $this->layout()->tablastato = $tablastato;
        
        $nombre = $this->getRequest()->getPost("nome");
        if (null === $nombre || empty($nombre)) {
            return $this->redirect()->toRoute('admin', array('controller' => 'utenti', 'action' => 'index'));
        }
    
        $listaUsuario = $this->getUtentiDao()->cercaPerNome($nombre);
        $viewModel = new ViewModel(array(
            'form'=>$form,
            'usuarios' => $listaUsuario,
            'titulo' => $encontrados .'(' . $listaUsuario->count() . ')',
            'placenom' => $placenombre,
            'placebusca' => $placebuscar,
            'placever' => $placever,
            'encontrados' => $encontrados,
            'tablaid'=>$tablaid,
            'tablanombre'=>$tablanombre,
            'tablaapellido'=>$tablaapellido,
            'tablaemail'=>$tablaemail,
            'tablamodifica'=>$tablamodifica,
            'tablaelimina'=>$tablaelimina,
            'tabladetalle'=>$tabladetalle,
            'tablastato'=>$tablastato,
        ));
    
        $viewModel->setTemplate("admin/utenti/index");
    
        return $viewModel;
    }
    */
    
    public function crearAction()
    {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        $form = new Registro("registro");
        $form->get('stato')->setValue(1);
        return array(
            'form' => $form, 'titulo'=>'Nuovo Utente', 'volver'=>'Indietro',
        );
        
        
    }
    
    public function guardarAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('admin', array('controller' => 'utenti', 'action'=>'index'));
        }

        $form = new Registro("registro");
        $form->setInputFilter(new RegistroValidator());
        
       
        
        // Obtenemos los datos desde el Formulario con POST data:
        $data = array_merge_recursive($this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
        
        $form->setData($data); 
       
        // Validando el form
        if (!$form->isValid()) {
            $id = $data['id'];
            $imagen = $this->getUtentiDao()->tuttiPerId($id)->getImmagine();
            $modelView = new ViewModel(array('title' => 'Aggiorno', 'form' => $form, 'volver'=>'Indietro','titulo' => 'Modifica utente', 'imagen'=>$imagen));
            $modelView->setTemplate('admin/utenti/crear');
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
        
        $producto = new Utenti();
        $producto->exchangeArray($dati);

        $this->getUtentiDao()->salvare($producto);
        return $this->redirect()->toRoute('admin', array('controller' => 'utenti', 'action' => 'index'));
    }
    
    
    public function editarAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $volver = $this->config['parametros']['ver']['volver'];
        $this->layout()->volver = $volver;
        $this->layout()->menu = $menu;
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('controller' => 'utenti', 'action' => 'index'));
        }
    
        $form = new Registro("registro");
    
        $producto = $this->getUtentiDao()->tuttiPerId($id);
        
       
        
        $imagen = $this->getUtentiDao()->tuttiPerId($id)->getImmagine();
        
        $form->bind($producto);
        $form->get('send')->setAttribute('value', 'Salva');
    
        $modelView = new ViewModel(array('sezione'=>'Utenti','titulo' => 'Modifica Utente', 'form' => $form,  'volver'=>$volver, 'imagen'=>$imagen));
        $modelView->setTemplate('admin/utenti/crear');
        return $modelView;
    }
    
    public function eliminarAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('controller' => 'utenti', 'action' => 'index'));
        }
        $producto = new Utenti();
        $producto->setId($id);
    
        $this->getUtentiDao()->eliminare($producto);
        return $this->redirect()->toRoute('admin', array('controller' => 'utenti', 'action' => 'index'));
    }
    
    

}


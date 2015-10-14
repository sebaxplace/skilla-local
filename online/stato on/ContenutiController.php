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
use Contenuti\Form\CercaPoster;
use Contenuti\Form\Content;
use Contenuti\Model\Entity\Contenuti;
use Contenuti\Form\ContentValidator;
use Contenuti\Form\ContentValidatorEdit;
use Contenuti\Model\Content as ContentService;
use Zend\Form\Element;

class ContenutiController extends AbstractActionController
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
    public function setRegistro(ContentService $registro) {
        $this->registro = $registro;
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
        $this->getConfig();
        $titulo = 'Contenuti';
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
        $tablaposterlab = $this->config['parametros']['tabla']['posterlab'];
        
        $mensaje = $this->config['parametros']['mvc']['application']['index']['mensaje'];
        
        $menu1 = $this->config['parametros']['menu1'];
        
        $this->layout()->head = $titulohead;
        $this->layout()->tablaposterlab = $tablaposterlab;
        
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
        //print_r($this->getContenutiDao()->tutti());die;
        $form = new CercaPoster("CercaPoster");
        
        $form->get('posterlab')->setValueOptions($this->getContenutiDao()->obtenerPosterlabsSelect());
        
        return new ViewModel(array(
                    'form'=>$form,
                    'title' => 'Lista Contenuti',
                    'usuarios' => $this->getContenutiDao()->tutti(),
                    'titulo' => $titulo,
            
            'placenom' => $placenombre,
            'placebusca' => $placebuscar,
            'placever' => $placever,
            'posicion' => 'Posizione',
            
            'tablaid'=>$tablaid,
            'tablanombre'=>$tablanombre,
            'tablaapellido'=>$tablarelatori,
            'tablaemail'=>$tabladurata,
            'tablamodifica'=>$tablamodifica,
            'tablaelimina'=>$tablaelimina,
            'tabladetalle'=>$tabladetalle,
            'tablasteps'=>$tablasteps,
            'tablaposterlab'=>$tablaposterlab,
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
    
        $usuario = $this->getContenutiDao()->tuttiPerId($id);
      // print_r($usuario);
       // $usuario['relatori_id'] = $usuario['relatori'];
        if (null === $usuario) {
            return $this->redirect()->toRoute('contenuti', array('controller' => 'index', 'action' => 'listar'));
        }
        
        $volver = $this->config['parametros']['ver']['volver'];
        $this->layout()->volver = $volver;
    
        $detalle = $this->config['parametros']['ver']['detalle'];
        $this->layout()->detalle = $detalle;
    
        return new ViewModel(array('usuario' => $usuario,
            
            'titulo' => 'Dettaglio contenuto',
            'volver' => $volver,
            'sezione' =>'Contenuti'
        ));
    }
    
   
    
    public function crearAction()
    {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        $form = $this->getForm();
        $form->get('posterlab')->setValueOptions($this->getContenutiDao()->obtenerPosterlabsSelect());
       //print_r($this->llenarListaTipo());die;
        $form->get('contenuto')->setValue('<div class="flex-video"> //Inserisci qui il tuo iframe</div>');
        //$form->get('posizione')->setValueOptions($this->nuevaPosizione());
        $form->get('tipo')->setValueOptions($this->llenarListaTipo());
        $form->get('stato')->setValue(1);
        $this->initAjaxContext();
        
        return array(
            'form' => $form, 'titulo'=>'Nuovo Contenuto', 'volver'=>'Indietro','sezione' =>'Contenuti',
        );
        
        
    }
    
    public function guardarAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('admin', array('controller' => 'contenuti', 'action'=>'index'));
        }

        $form =  new Content("content");
        $form->get('posterlab')->setValueOptions($this->getContenutiDao()->obtenerPosterlabsSelect());
      
        
        
        $form->get('tipo')->setValueOptions($this->llenarListaTipo());
        
        
         
        $data = array_merge_recursive($this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );
       
        
        $form->setInputFilter(new ContentValidatorEdit());
        
        $this->initAjaxContext();
        $form->setData($data);
         
        // Validando el form
        if (!$form->isValid()) {
            $modelView = new ViewModel(array('title' => 'Aggiorno', 'form' => $form, 'volver'=>'Indietro','titulo' => 'Modifica contenuto',));
            $modelView->setTemplate('admin/contenuti/crear');
            return $modelView;
        }
        $dataForms = $form->getData();
        $datoimagen = $dataForms['background']['tmp_name'];
        $datospimagen = array('background'=>$datoimagen);
        $dataForms['posterlab_id'] = $dataForms['posterlab'];
        $dati = array_replace($dataForms, $datospimagen);
        
       
       
        
        
        $producto = new Contenuti();
       
        $producto->exchangeArray($dati);
       
        $this->getContenutiDao()->salvare($producto);
       // print_r($dati);die;
        return $this->redirect()->toRoute('admin', array('controller' => 'contenuti', 'action' => 'index'));
    }
    
    
    public function editarAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $volver = $this->config['parametros']['ver']['volver'];
        $this->layout()->volver = $volver;
        $this->layout()->menu = $menu;
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('controller' => 'contenuti', 'action' => 'index'));
        }
        
       
        $form = new Content("content");
        $form->get('posterlab')->setValueOptions($this->getContenutiDao()->obtenerPosterlabsSelect());
        $form->get('contenuto')->setValue('<div class="flex-video"> //Inserisci qui il tuo iframe</div>');
        
        $form->get('posizione')->setValueOptions($this->llenarPosizioneEdit());
        $form->get('tipo')->setValueOptions($this->llenarListaTipo());
        $this->initAjaxContext();
        $producto = $this->getContenutiDao()->tuttiPerId($id);
        $imagen = $producto->getBackground();
        $urlimagen = explode('/', $imagen);
        $nuewvaurlimg = $urlimagen[2].'/'.$urlimagen[3].'/'.$urlimagen[4];
       
        $form->bind($producto);
        $form->get('send')->setAttribute('value', 'Salva');
        $modelView = new ViewModel(array('sezione'=>'Contenuti','titulo' => 'Modifica Contenuto', 'form' => $form,  'volver'=>$volver, 'producto'=>$nuewvaurlimg));
        $modelView->setTemplate('admin/contenuti/crear');
        return $modelView;
    }
    
    public function eliminarAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('controller' => 'contenuti', 'action' => 'index'));
        }
        $producto = new Contenuti();
        $producto->setId($id);
    
        $this->getContenutiDao()->eliminare($producto);
        return $this->redirect()->toRoute('admin', array('controller' => 'contenuti', 'action' => 'index'));
    }
    
    
    
    public function cercaAction() {
        $this->getConfig();
        $idposterlab = $this->getRequest()->getPost("posterlab");
        
        $nombreactualposter = $this->getContenutiDao()->obtenerPosterlabActual($idposterlab);
        $nombresito = $nombreactualposter->getTitolo();
       
      
        $titulo = 'Contenuti';
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
        $tablaposterlab = $this->config['parametros']['tabla']['posterlab'];
        
        $mensaje = $this->config['parametros']['mvc']['application']['index']['mensaje'];
        
        $menu1 = $this->config['parametros']['menu1'];
        
        $this->layout()->head = $titulohead;
        $this->layout()->tablaposterlab = $tablaposterlab;
        
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
        
        $form = new CercaPoster("CercaPoster");
        $form->get('posterlab')->setValueOptions($this->getContenutiDao()->obtenerPosterlabsSelect());
        
        $form->get('posterlab')->setValue($idposterlab);
        
  
        
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
    
        $listaUsuario = $this->getContenutiDao()->cercaPerPosterlab($idposterlab);
        $viewModel = new ViewModel(array(
            'form'=>$form,
            'usuarios' => $listaUsuario,
            'titulo' => 'Contenuti',
            'titulo1' => ucfirst($nombresito),
            'titulos' => 'Trovati (' . $listaUsuario->count() . ') ',
            'title' => 'Lista Contenuti',
            'titulo' => $titulo,
            
            'placenom' => $placenombre,
            'placebusca' => $placebuscar,
            'placever' => $placever,
            'posicion' => 'Posizione',
            
            'tablaid'=>$tablaid,
            'tablanombre'=>$tablanombre,
            'tablaapellido'=>$tablarelatori,
            'tablaemail'=>$tabladurata,
            'tablamodifica'=>$tablamodifica,
            'tablaelimina'=>$tablaelimina,
            'tabladetalle'=>$tabladetalle,
            'tablasteps'=>$tablasteps,
            'tablaposterlab'=>$tablaposterlab,
            'algunmensaje'=>$mensaje,
            'menu' => $menu1,
            'tablastato'=>$tablastato,
        ));
    
        $viewModel->setTemplate("admin/contenuti/index");
    
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
    
    private function llenarListaTipo() {
    
        // Data reference de paises para list box
        $tipo = array();
        $tipo[1] = "Domanda utenti";
        $tipo[2] = "Risposta utenti";
        $tipo[3] = "Da visualizzare";
    
        return $tipo;
    }
    
    private function nuevaPosizione() {
    
       
         
        $listacompleta = $this->getContenutiDao()->tutti()->toArray();
    
    
        $numeros = array();
        foreach($listacompleta as $numero){
             
            $numeros[$numero['posizione']] = $numero['posizione'];
        }
        //print_r($idactual);die;
        $static_array = array();
        $static_array[1] = "1";
        $static_array[2] = "2";
        $static_array[3] = "3";
        $static_array[4] = "4";
        $static_array[5] = "5";
        $static_array[6] = "6";
        $static_array[7] = "7";
        $static_array[8] = "8";
        $static_array[9] = "9";
        $static_array[10] = "10";
    
    
        $result = array_diff($static_array, $numeros);
    
        $ordenado = array_unique($result);
    
    
    
        ksort($ordenado);
        // print_r($ordenado);die;
        return $result;
    
    
    }
    
    
    public function caricaAction(){
       $posId = (int) $this->getRequest()->getPost('posId', 0);
       
       $posizione = new Element\Select('posizione');
       $posizione->setLabel('Posizione');
       $posizione->setEmptyOption('Seleziona una posizione =>');
       $posizione->setValueOptions($this->getContenutiDao()->listaPosizioni($posId));
       
       $view = new ViewModel( array(
                'selectPosizione'=>$posizione,
            ));
       $view->setTerminal(true);
       return $view;
        
    }
    
    private function llenarPosizione() {
    
       $id = (int) $this->getRequest()->getPost('id');
        $actual = $this->getContenutiDao()->tuttiPerId($id)->getPosizione();
        $idactual[$actual]= $actual;
       
        $listacompleta = $this->getContenutiDao()->tutti()->toArray();
    
            
            $numeros = array();
            foreach($listacompleta as $numero){
               
                $numeros[$numero['posizione']] = $numero['posizione'];
            }
            //print_r($idactual);die;
            $static_array = array();
            $static_array[1] = "1";
            $static_array[2] = "2";
            $static_array[3] = "3";
            $static_array[4] = "4";
            $static_array[5] = "5";
            $static_array[6] = "6";
            $static_array[7] = "7";
            $static_array[8] = "8";
            $static_array[9] = "9";
            $static_array[10] = "10";
            
            
            $result = array_diff($static_array, $numeros);
            
           $result[$actual]=$actual;
           $ordenado = array_unique($result);
          
            
            
            ksort($ordenado);
           // print_r($ordenado);die;
        return $result;
    
        
    }
    
    
    
    private function llenarPosizioneEdit() {
        //print_r($id);
        $id = (int) $this->params()->fromRoute('id');
        $actual = $this->getContenutiDao()->tuttiPerId($id)->getPosizione();
    
        $idactual[$actual]= $actual;
        $posteractul = $this->getContenutiDao()->tuttiPerId($id)->getPosterlab()->getId();
        
        $listacompleta = $this->getContenutiDao()->editaposicionPerId($posteractul)->toArray();
        
            $numeros = array();
            foreach($listacompleta as $numero){
                $numeros[$numero['posizione']] = $numero['posizione'];
            }
            
            $posterposicionlimit = $this->getPosterlabsDao()->tuttiPerId($posteractul);
            
            $static_array = array();
            for($i=1;$i<=$posterposicionlimit->getSteps();$i++){
                $static_array[$i] = $i;
            }
           
            //print_r($static_array);die;
            
            $result = array_diff($static_array, $numeros);
            
            //print_r($listacompleta);die;
            
            $result[$actual]=$actual;
            $ordenado = array_unique($result);
          
           
            
            ksort($ordenado);
           // print_r($ordenado);die;
        return $result;
    
    }
    
    private function getForm() {
        $form = new Content("content");
        $form->get('posterlab')->setValueOptions($this->getContenutiDao()->obtenerPosterlabsSelect());
        return $form;
    }
    
    public function initAjaxContext(){
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        //$renderer->headScript()->appendFile('//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js');
        
        //$rendererJs = clone $renderer;
        $script = $renderer->render('admin/contenuti/js/ajax');
        $renderer->headScript()->appendScript($script, 'text/javascript');
        return $renderer;
    }
    
    
}


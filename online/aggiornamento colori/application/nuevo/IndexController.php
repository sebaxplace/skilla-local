<?php
/**
 * Zend Framework (http://framework.zend.com/)
*
* @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
* @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ArrayObject;

use Contenuti\Model\Entity\Contenuti;
use Interattivo\Model\Entity\Interattivo;
use Utenti\Model\Entity\Utenti;
use Posterlabs\Model\Entity\Posterlabs;
use Interattivo\Form\Nascosto;
use Interattivo\Form\NascostoValidator;
use DateTime;
use Sessioni\Model\Entity\Sessioni;
use Zend\Form\Element;
use Zend\Form\Form;

class IndexController extends AbstractActionController
{
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
    
    public function getRelatoriDao() {
        if (!$this->relatoriDao) {
            $sm = $this->getServiceLocator();
            $this->relatoriDao = $sm->get('Relatori\Model\RelatoriDao');
        }
        return $this->relatoriDao;
    }
    
    public function getInterattivoDao() {
        if (!$this->interattivoDao) {
            $sm = $this->getServiceLocator();
            $this->interattivoDao = $sm->get('Interattivo\Model\InterattivoDao');
        }
        return $this->interattivoDao;
    }
    
    public function getSessioniDao() {
        if (!$this->sessioniDao) {
            $sm = $this->getServiceLocator();
            $this->sessioniDao = $sm->get('Sessioni\Model\SessioniDao');
        }
        return $this->sessioniDao;
    }
    
    public function indexAction()
    {
       
        
        return new ViewModel(array('usuarios' => $this->getPosterlabsDao()->tuttiAttivi()));
        
       
    }
    
    public function sessionposterlabAction()
    {
        $id = (int) $this->params()->fromRoute("id", 0);
        $poster = $this->getPosterlabsDao()->tuttiPerId($id)->getId();
        $relator = $this->getPosterlabsDao()->tuttiPerId($id)->getRelatori()->getId();
       
       
        //insert posterlab
        $datos = array('id'=>$id, 'statosessione'=>1);
        
        $producto = new Posterlabs();
        //print_r($producto);die;
        $producto->exchangeArray($datos);
        //print_r($datos);die;
        $this->getPosterlabsDao()->iniziaSession($id);
        
        //insert sessioni
        $patron = 'Y-m-d H:i';
        $fecha = new DateTime();
        $inizio = $fecha->format($patron);
        
        $datosession = array(
            //'id'=>$id,
            'posterlab_id'=>$poster,
            'relatori_id'=>$relator,
            'data_inizio'=>$inizio,
            'data_fine'=>$inizio,
            'stato'=>1,
        
        );
        
        $registro = new Sessioni();
        
        $registro->exchangeArray($datosession);
        
        $this->getSessioniDao()->salvare($registro);
        
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/index/posterlab/'.$id);
    
    }
    
    
    
    
    public function chiudisessioneAction()
    {
        $id = (int) $this->params()->fromRoute("id", 0);
        $datos = array('id'=>$id, 'statosessione'=>0);
        $datos2 = array('posterlab_id'=>$id, 'stato'=>0);
    
        $producto = new Posterlabs();
        $producto->exchangeArray($datos);
    
        $this->getPosterlabsDao()->chiudiSession($id);

        $registro = new Sessioni();
        
        $registro->exchangeArray($datos2);
        
        $this->getSessioniDao()->chiudisession($registro);
    
        return $this->redirect()->toRoute('application', array('controller' => 'index', 'action' => 'index'));
    
    }
   
    public function posterlabAction() {
        $id = (int) $this->params()->fromRoute("id", 0);
        $menu = $this->getContenutiDao()->relatorePerId($id);
        $menuArray = $this->getContenutiDao()->defaultPerId($id);
        $ids = (int) $this->params()->fromQuery("ids");
        if(!$ids){
            $ids = $menuArray->getId();
        }
        
        $inizio = $this->getSessioniDao()->BuscoId($id)->getInizio();
        
        $posicionactual = $this->getPosterlabsDao()->posicionActual($id, $ids);
        
        $timer = $this->getPosterlabsDao()->tuttiPerId($id)->getDurata();
        $generaloposter = $this->getPosterlabsDao()->tuttiPerId($id);
        $timerdate = new DateTime($inizio);
        $timerdate->modify('+'.$timer.' minutes');
        $tiempo = $timerdate->format('Y-m-d H:i:s');
        //print_r($tiempo);die;
        $nombre = $generaloposter->getRelatori()->getNome();
        $apellido = $generaloposter->getRelatori()->getCognome();
        $relator = $nombre.' '.$apellido;
        $verifica = $this->getContenutiDao()->buscaId($ids);
        $passwordPosterlab = $generaloposter->getPassword2();
        
        $layout = $this->layout();
        // asignamos algunas variables al layout
       
        $layout->timeraggio = $tiempo;
        $layout->password = $passwordPosterlab;
        $seccionactiva = $this->getSessioniDao()->BuscoId($id)->getStato();
        $seccionactual = $this->getSessioniDao()->BuscoId($id)->getId();
        
        //controllo sessione attiva
        if($seccionactiva == 0){
            
            return $this->redirect()->toRoute('application', array('controller' => 'index', 'action' => 'index'));
        }    
        //id seccion activa
        $idseccion = $this->getSessioniDao()->BuscoId($id)->getId();
            //verifico domanda
            if($verifica->getTipo() == 1){
                
                $this->initAjaxContext2();
                
                $postit = $this->getInterattivoDao()->posiciones($id, $seccionactual);
               
                $busco_ultimo = $this->getInterattivoDao()->posiciones($id, $seccionactual)->toArray();
                
                $contador_postit= count($busco_ultimo);
                if($contador_postit>0){
                    $todo_id = array();
                    foreach($busco_ultimo as $key){
                       
                        $todo_id[] = $key['id'];
                    }
                    $ultimo = max($todo_id);
                }
               
                $color = 'yellow';     
                $patron = 'Y-m-d H:i:s';
                $fecha = new DateTime();
                $form = new Nascosto("Nascosto");
                $form->get('id')->setValue($ids);
                $form->get('posterlab')->setValue($id);
                $form->get('tipo')->setValue(1);
                $form->get('data')->setValue($fecha->format($patron));
                $form->get('color')->setValue('yellow');
                $form->get('xyz')->setValue('0x101x100');
                $form->get('nome')->setValue($relator);
                $form->get('sessione')->setValue($idseccion);
                $form->get('stato')->setValue(1);
                $hidden = new Element\Hidden('lasted');
                
                $hidden->setAttributes(array('class'=>'lasted'));
                
                if($contador_postit>0){
                    $hidden->setValue($ultimo);
                }else{
                    $hidden->setValue(0);
                } 
                $form->add($hidden);
                
                
                if($ids !== 0){
                    $contenuto = $this->getContenutiDao()->buscaId($ids);
                    if($contenuto->getBackground() != null){
                        $imagen = $contenuto->getBackground();
                        $urlimagen = explode('/', $imagen);
                        $nuewvaurlimg = $urlimagen[2].'/'.$urlimagen[3].'/'.$urlimagen[4];
                    }else{
                        $imagen = null;
                    }
                    
                }else{
                    $contenuto = $this->getContenutiDao()->defaultPerId($id);
                    if($contenuto->getBackground() != null){
                        $imagen = $contenuto->getBackground();
                        $urlimagen = explode('/', $imagen);
                        $nuewvaurlimg = $urlimagen[2].'/'.$urlimagen[3].'/'.$urlimagen[4];
                    }else{
                        $imagen = null;
                    }
                }
                //print_r($contenuto);die;
                
                if (null === $menu) {
                    return $this->redirect()->toRoute('application', array('controller' => 'index', 'action' => 'index'));
                }
                return new ViewModel(array(
                    'form'=> $form, 
                    'posterlab' => $menu, 
                    'contenuto'=>$contenuto,
                    'imagen'=>$nuewvaurlimg, 
                    'ids'=>$ids, 
                    'postit'=>$postit, 
                    'timeraggio'=>$tiempo, 
                    'cantidad'=>$contador_postit, 
                    'color'=>$color, 
                    'password'=>$passwordPosterlab,
                    
                ));
            }
            
            //verifico risposta
            if($verifica->getTipo() == 2){
                
                $this->initAjaxContext();
                
                $postit = $this->getInterattivoDao()->posiciones2($id, $seccionactual);
                
                $busco_ultimo = $this->getInterattivoDao()->posiciones2($id, $seccionactual)->toArray();
                //print_r($busco_ultimo);die;
                $contador_postit= count($busco_ultimo);
                if($contador_postit>0){
                    $todo_id = array();
                    foreach($busco_ultimo as $key){
                         
                        $todo_id[] = $key['id'];
                    }
                    $ultimo = max($todo_id);
                }
                
                $color = 'blue';
                $patron = 'Y-m-d H:i';
                $fecha = new DateTime();
                $form = new Nascosto("Nascosto");
                $form->get('id')->setValue($ids);
                $form->get('posterlab')->setValue($id);
                $form->get('tipo')->setValue(2);
                $form->get('data')->setValue($fecha->format($patron));
                $form->get('color')->setValue('blue');
                $form->get('xyz')->setValue('0x101x100');
                $form->get('nome')->setValue($relator);
                $form->get('sessione')->setValue($idseccion);
                $form->get('stato')->setValue(1);
                $hidden = new Element\Hidden('lasted');
                $hidden->setValue(1);
                $form->add($hidden);
                $hidden = new Element\Hidden('lasted');
                
                $hidden->setAttributes(array('class'=>'lasted'));
                
                if($contador_postit>0){
                    $hidden->setValue($ultimo);
                }else{
                    $hidden->setValue(0);
                }
                $form->add($hidden);
                if($ids !== 0){
                    $contenuto = $this->getContenutiDao()->buscaId($ids);
                    if($contenuto->getBackground() != null){
                        $imagen = $contenuto->getBackground();
                        $urlimagen = explode('/', $imagen);
                        $nuewvaurlimg = $urlimagen[2].'/'.$urlimagen[3].'/'.$urlimagen[4];
                    }else{
                        $imagen = null;
                    }
                }else{
                    $contenuto = $this->getContenutiDao()->defaultPerId($id);
                    if($contenuto->getBackground() != null){
                        $imagen = $contenuto->getBackground();
                        $urlimagen = explode('/', $imagen);
                        $nuewvaurlimg = $urlimagen[2].'/'.$urlimagen[3].'/'.$urlimagen[4];
                    }else{
                        $imagen = null;
                    }
                }
                
                
                if (null === $menu) {
                    return $this->redirect()->toRoute('application', array('controller' => 'index', 'action' => 'index'));
                }
                return new ViewModel(array(
                    'form'=> $form, 
                    'posterlab' => $menu, 
                    'contenuto'=>$contenuto,
                    'imagen'=>$nuewvaurlimg, 
                    'ids'=>$ids, 
                    'postit'=>$postit, 
                    'timeraggio'=>$tiempo, 
                    'cantidad'=>$contador_postit, 
                    'color'=>$color,
                    'password'=>$passwordPosterlab,
                ));
            }
            
            //verifico contenuto
            if($verifica->getTipo() == 3){
    
                
                
                if($ids !== 0){
                    $contenuto = $this->getContenutiDao()->buscaId($ids);
                    if($contenuto->getBackground() != null){
                        $imagen = $contenuto->getBackground();
                        $urlimagen = explode('/', $imagen);
                        $nuewvaurlimg = $urlimagen[2].'/'.$urlimagen[3].'/'.$urlimagen[4];
                    }else{
                        $imagen = null;
                    }
                }else{
                    $contenuto = $this->getContenutiDao()->defaultPerId($id);
                    if($contenuto->getBackground() != null){
                        $imagen = $contenuto->getBackground();
                        $urlimagen = explode('/', $imagen);
                        $nuewvaurlimg = $urlimagen[2].'/'.$urlimagen[3].'/'.$urlimagen[4];
                    }else{
                        $imagen = null;
                    }
                }
                
                
                if (null === $menu) {
                    return $this->redirect()->toRoute('application', array('controller' => 'index', 'action' => 'index'));
                }
                return new ViewModel(array(
                    'posterlab' => $menu, 
                    'contenuto'=>$contenuto,
                    'imagen'=>$nuewvaurlimg, 
                    'ids'=>$ids,
                    'timeraggio'=>$tiempo,
                    'password'=>$passwordPosterlab,
                ));
            }
       
       
    }
    
    public function guardarAction() {
      
    
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('application', array('controller' => 'index', 'action'=>'posterlab'));
        }
        $data = $this->getRequest()->getPost()->toArray();
        
        $id  = (int) $data['posterlab'];
        
        $seccionactiva = $this->getSessioniDao()->BuscoId($id)->getStato();
        
        
        //controllo sessione attiva
        if($seccionactiva == 0){
        
            return $this->redirect()->toRoute('application', array('controller' => 'index', 'action' => 'index'));
        }
        //id seccion activa
        $idseccion = $this->getSessioniDao()->BuscoId($id)->getId();
        
        $menu = $this->getContenutiDao()->relatorePerId($id);
        $postit = $this->getInterattivoDao()->posiciones($id);
        $timer = $this->getPosterlabsDao()->tuttiPerId($id);
        $nombre = $timer->getRelatori()->getNome();
        $apellido = $timer->getRelatori()->getCognome();
        $relator = $nombre.' '.$apellido;
        
        
        $patron = 'Y-m-d H:i';
        $fecha = new DateTime();
        $form = new Nascosto("Nascosto");
        $form->get('id')->setValue($id);
        $form->get('posterlab')->setValue($id);
        $form->get('tipo')->setValue(1);
        $form->get('data')->setValue($fecha->format($patron));
        $form->get('color')->setValue('yellow');
        $form->get('xyz')->setValue('0x101x100');
        $form->get('nome')->setValue($relator);
        $form->get('sessione')->setValue($idseccion);
        $form->get('stato')->setValue(1);
        $form->setInputFilter(new NascostoValidator());
        $datos = $this->getRequest()->getPost()->toArray();
        
        $form->setData($datos);
       
        if (!$form->isValid()) {
            $modelView = new ViewModel(array('title' => 'Aggiorno', 'form' => $form, 'volver'=>'Indietro','titulo' => 'Modifica contenuto',));
            $modelView->setTemplate('admin/interattivo/crear');
            return $modelView;
        } 
       
        $dataForms = $form->getData();
        $dataForms['posterlab_id'] = $dataForms['posterlab'];
        
        $producto = new Interattivo();
        
        $producto->exchangeArray($dataForms);
       
        $nuevoid = $this->getInterattivoDao()->salvare($producto);
        print_r($nuevoid);die;
        
        $view = new ViewModel(array('msg'=>$nuevoid));
        $view->setTerminal(true);
        return $view;
        
    }
    
    
    public function moverAction() {
        
        if (!$this->request->isPost()) {
            return"error";
        }
        $data = $this->getRequest()->getPost()->toArray();
        
        $id  = (int) $data['posterlab'];
        
        
        $producto = new Interattivo();
        $producto->exchangeArray($data);
        $this->getInterattivoDao()->salvare2($producto);
         
        return $this;
    }
    
    public function eliminaAction() {
    
        if (!$this->request->isPost()) {
            return"error";
        }
        $data = $this->getRequest()->getPost()->toArray();
    
        $id  = (int) $data['posterlab'];
    
    
        $producto = new Interattivo();
        $producto->exchangeArray($data);
        $this->getInterattivoDao()->elimina2($producto);
         
        return $this;
    }
    
    //domande & risposte
    public function initAjaxContext() {
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer(); 
        
        $script = $renderer->render('application/index/js/live'); 
        $myscript = $renderer->render('application/index/js/myscript');
       
        $renderer->headScript()->appendScript($script, 'text/javascript');
        $renderer->headScript()->appendScript($myscript, 'text/javascript');
        $publicDir = getcwd() . '/public';
        $this->getViewHelper('HeadScript')->appendFile('/js/script.js');
        return $renderer;
    }
    
    //domande
    public function initAjaxContext2() {
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
    
        $script = $renderer->render('application/index/js/livedomanda');
        $myscript = $renderer->render('application/index/js/myscript');
         
        $renderer->headScript()->appendScript($script, 'text/javascript');
        $renderer->headScript()->appendScript($myscript, 'text/javascript');
        $publicDir = getcwd() . '/public';
        $this->getViewHelper('HeadScript')->appendFile('/js/script.js');
        return $renderer;
    }
    
    
    
    //domande & risposte
    public function cargarAction() {
        $catId = (int) $this->getRequest()->getPost("Id", 0);
        $ultimo_id = (int) $this->getRequest()->getPost("Last", 0);
        if($ultimo_id !== 0){
            
            $producto = $this->getinterattivoDao()->cercaPerPosterlabAttivo($catId, $ultimo_id);
           /* foreach($producto as $key){
                $idposter =  $key->getId();
            }
           */
            //echo $idposter;die;
        }
        
       
        $view = new ViewModel(array(
            'postit' => $producto));
        $view->setTerminal(true);
        return $view; 
    }
    
    //domande
    public function cargardomandaAction() {
        $catId = (int) $this->getRequest()->getPost("Id", 0);
        $ultimo_id = (int) $this->getRequest()->getPost("Last", 0);
        if($ultimo_id !== 0){
    
            $producto = $this->getinterattivoDao()->cercaPerPosterlabAttivo2($catId, $ultimo_id);
           
        }
    
         
        $view = new ViewModel(array(
            'postit' => $producto));
        $view->setTerminal(true);
        return $view;
    }
    
    protected function getViewHelper($helperName)
    {
    
        return $this->getServiceLocator()->get('viewhelpermanager')->get($helperName);
    }
    
}

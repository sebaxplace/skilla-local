<?php
/**
 * Zend Framework (http://framework.zend.com/)
*
* @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
* @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/
namespace Posterlabs\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Posterlabs\Model\Entity\Posterlabs;
use Sessioni\Model\Entity\Sessioni;
use DateTime;
use Interattivo\Model\Entity\Interattivo;

class IndexController extends AbstractActionController
{
    
    public function getPosterlabsDao() {
        if (!$this->posterlabsDao) {
            $sm = $this->getServiceLocator();
            $this->posterlabsDao = $sm->get('Posterlabs\Model\PosterlabsDao');
        }
        return $this->posterlabsDao;
    }
    
    public function getSessioniDao() {
        if (!$this->sessioniDao) {
            $sm = $this->getServiceLocator();
            $this->sessioniDao = $sm->get('Sessioni\Model\SessioniDao');
        }
        return $this->sessioniDao;
    }
    
    public function getInterattivoDao() {
        if (!$this->InterattivoDao) {
            $sm = $this->getServiceLocator();
            $this->InterattivoDao = $sm->get('Interattivo\Model\InterattivoDao');
        }
        return $this->InterattivoDao;
    }
    
    public function indexAction()
    {
         return $this->redirect()->toRoute('posterlabs', array('controller' => 'index', 'action' => 'login'));
    }
    
    public function loginAction()
    {
        $values = \Zend\Json\Json::decode($this->getRequest()->getContent());
        
        $content = array();
        foreach($values as $key){
            $content[] = $key;
        }
        
        $data = $content;
        $password = $data['1'];
        $loggato = $this->getPosterlabsDao()->autenticare($password);
        
        if (!$loggato){
            $json = new JsonModel(array('data'=>'error'));
            return $json;
        }
        
        $nuevapass = $loggato->getPassword();
        $arraysAreEqual = ($password === $nuevapass);
        
        $posterlabid = $loggato->getId(); 
       // var_dump($posterlabid);die;
        $session_id = $this->getSessioniDao()->BuscoIdActiva($posterlabid)->getId();
        
        if($arraysAreEqual == 1){
            $json = new JsonModel(array(
                'data'=>'success', 
                'session'=>$session_id,
                
            ));
        }
        
        return $json;
       
        
        
    }
    
    public function guardarAction() {
       
        $values = \Zend\Json\Json::decode($this->getRequest()->getContent());
        
        $content = array();
        foreach($values as $key){
            $content[] = $key;
        }
        
        $nombre = $content[0];
        $session = $content[1];
        $messaggio = $content[2];
        $color = 'yellow';
        
        
        $xposition = rand(2, 1000);
        $yposition = rand(3, 650);
        $zposition = rand(1, 100);
        
        $posiciones = $xposition.'x'.$yposition.'x'.$zposition;
        
        $posterlab = $this->getSessioniDao()->tuttiPerId($session)->getPosterlab();
        $tipo = 1;
        
        $patron = 'Y-m-d H:i';
        $fecha = new DateTime();
        $fechactual = $fecha->format($patron);
        $stato = 1;
        
        $productos = array(
            'nome'=>$nombre,
            'messaggio'=>$messaggio,
            'color'=>$color,
            'xyz'=>$posiciones,
            'posterlab_id'=>$posterlab,
            'tipo'=>$tipo,
            'sessione'=>$session,
            'data'=>$fechactual,
            'stato'=>$stato,
        );
        
        $producto = new Interattivo();
        
        $producto->exchangeArray($productos);
         
        $nuevoid = $this->getInterattivoDao()->salvare($producto);
        
        //$salvado = $this->getInterattivoDao()->salvare($producto);
        
        if (!$nuevoid){
            $json = new JsonModel(array('data'=>'error'));
            return $json;
        }else{
            $json = new JsonModel(array('data'=>'success', 'messaggio'=>$messaggio));
            return $json;
        }
    }
    
    
    
    

}
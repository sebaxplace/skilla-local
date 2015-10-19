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
use Contenuti\Model\Entity\Contenuti;
use DateTime;
use Interattivo\Model\Entity\Interattivo;
use Zend\Http\Headers;
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
    
    public function getContenutiDao() {
        if (!$this->contenutiDao) {
            $sm = $this->getServiceLocator();
            $this->contenutiDao = $sm->get('Contenuti\Model\ContenutiDao');
        }
        return $this->contenutiDao;
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
        $headers = new Headers();

        $headers->addHeaderLine('Access-Control-Allow-Origin', '*');
        $values = \Zend\Json\Json::decode($this->getRequest()->getContent());
        
        $content = array();
        foreach($values as $key){
            $content[] = $key;
        }
        
        $data = $content;
        $password = $data['1'];
        $loggato = $this->getPosterlabsDao()->autenticare($password);
        //print_r($data);die;
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
        $headers = new Headers();
        
        $headers->addHeaderLine('Access-Control-Allow-Origin', '*');
       
        $values = \Zend\Json\Json::decode($this->getRequest()->getContent());
        
        $content = array();
        foreach($values as $key){
            $content[] = $key;
        }
        
        $nombre = $content[0];
        $session = $content[1];
        $messaggio = $content[2];
       
        
        
        
        
        $xposition = rand(2, 1000);
        $yposition = rand(3, 650);
        $zposition = rand(1, 100);
        
        $posiciones = $xposition.'x'.$yposition.'x'.$zposition;
        
        $posterlab = $this->getSessioniDao()->tuttiPerId($session)->getPosterlab();
        
        $actualposition = $this->getPosterlabsDao()->tuttiPerId($posterlab)->getActual();
        
        $risposta = $this->getContenutiDao()->cercaRisposta($posterlab)->getPosizione();
        
        //print_r($risposta);die;
        
        if($actualposition == $risposta){
            //risposte
            $color = 'blue';
            $tipo = 2;
            $coloretipo = 2;
        }else{
            //domande
            $color = 'yellow';
            $tipo = 1;
            $coloretipo = 1;
        }
        
        
        
        
        
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
        
        $controllosession = $this->getSessioniDao()->tuttiPerIdAttivo($session)->getStato();
        ///tuttiPerIdAttivo
        
        if(!$controllosession == 1){
            $json = new JsonModel(array('data'=>'error'));
            return $json;
        }else{
            $producto = new Interattivo();
            
            $producto->exchangeArray($productos);
             
            $nuevoid = $this->getInterattivoDao()->salvare($producto);
            
            //$salvado = $this->getInterattivoDao()->salvare($producto);
            
            if (!$nuevoid){
                $json = new JsonModel(array('data'=>'error'));
                return $json;
            }else{
                $json = new JsonModel(array('data'=>'success', 'messaggio'=>$messaggio, 'colore'=>$coloretipo));
                return $json;
            }

        }
    }
    
    
    public function mysessionAction()
    {
        $headers = new Headers();
        
        $headers->addHeaderLine('Access-Control-Allow-Origin', '*');
        $values = \Zend\Json\Json::decode($this->getRequest()->getContent());
       
       
        $content = array();
        foreach($values as $key){
            $content[] = $key;
        }
        
        $secciones = $content[0];
        $todas = explode(',',$secciones);
        $unico = array_unique($todas);
        $contentjson = array();
        foreach($unico as $valores){
            $posters_id = $this->getSessioniDao()->tuttiPerId($valores)->getPosterlab();
            
            
            $data_inizio = $this->getSessioniDao()->tuttiPerId($valores)->getInizio();
            $datas_i = $data_inizio;
            $date_i = new DateTime($datas_i);
            $fechas_i = $date_i->format('d-m-Y H:i');
            
            
            $data_fine = $this->getSessioniDao()->tuttiPerId($valores)->getFine();
            $datas_f = $data_fine;
            $date_f = new DateTime($datas_f);
            $fechas_f = $date_f->format('d-m-Y H:i');
            
            $nomeposter = $this->getPosterlabsDao()->tuttiPerId($posters_id)->getTitolo();
            
            $interventi = $this->getInterattivoDao()->tuttiReport($posters_id, $valores)->toArray();
            
            $dati = array();
            foreach($interventi as $key){
                $datas = $key['data'];
                $date = new DateTime($datas);
                $fechas = $date->format('d-m-Y H:i');
                //print_r($fecha);
                $dati[] = array(
                    'id'=>$key['id'],
                    'nick'=>$key['nome'],
                    'messaggio'=>$key['messaggio'],
                    'tipo'=>$key['tipo'],
                    'data'=>$fechas,
                    'nomeposterlab'=>$nomeposter,
                );
            }
            //print_r($dati);die;
            $contentjson[] = array(
                'titolo'=>$nomeposter, 
                'livello'=>'base', 
                'data'=>$fechas_i,
                'data_estesa'=>$data_fine,
                'ora'=>$fechas_f,
                'id'=>$valores,
                'interattivo'=>$dati,
            );
            
        }
        $jsons = new JsonModel($contentjson);
        return $jsons;
         
    
    
    }
    

}
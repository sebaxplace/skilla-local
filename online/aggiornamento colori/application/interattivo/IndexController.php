<?php
/**
 * Zend Framework (http://framework.zend.com/)
*
* @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
* @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/
namespace Interattivo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use DateTime;
use Interattivo\Model\Entity\Interattivo;
use Posterlabs\Model\Entity\Posterlabs;
class IndexController extends AbstractActionController
{
    
    public function getInterattivoDao() {
        if (!$this->InterattivoDao) {
            $sm = $this->getServiceLocator();
            $this->InterattivoDao = $sm->get('Interattivo\Model\InterattivoDao');
        }
        return $this->InterattivoDao;
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
        return $this->redirect()->toRoute('interattivo', array('controller' => 'index', 'action' => 'cat1'));
    }
    
    public function categoriaunoAction()
    {
        
        $loggato = $this->getInterattivoDao()->categoria(1);
        if(count($loggato)>=1){
            $dati = array();
            foreach($loggato as $valor){
                $pos_id = $valor->getPosterlab()->getId();
                $nombreposterlab = $this->getPosterlabsDao()->tiempoId($pos_id)->getTitolo();
                //print_r($nombreposterlab);die;
                $dati[] = array(
            
                    'titolo'=>$nombreposterlab,
                    'data'=>$valor->getData(),
                    'testo'=>$valor->getMessaggio(),
                    'nickname'=>$valor->getNome(),
                );
            }
            
            $jsons = new JsonModel($dati);
            return $jsons;
        }else{
            $json = new JsonModel(array(array('data'=>'error')));
            return $json;
        }
         
    }
    
    public function categoriadueAction()
    {
    
        $loggato = $this->getInterattivoDao()->categoria(2);
        if(count($loggato)>=1){
            $dati = array();
            foreach($loggato as $valor){
                $pos_id = $valor->getPosterlab()->getId();
                $nombreposterlab = $this->getPosterlabsDao()->tiempoId($pos_id)->getTitolo();
                //print_r($nombreposterlab);die;
                $dati[] = array(
    
                    'titolo'=>$nombreposterlab,
                    'data'=>$valor->getData(),
                    'testo'=>$valor->getMessaggio(),
                    'nickname'=>$valor->getNome(),
                );
            }
    
            $jsons = new JsonModel($dati);
            return $jsons;
        }else{
            $json = new JsonModel(array(array('data'=>'error')));
            return $json;
        }
         
    }
    
    public function categoriatreAction()
    {
    
        $loggato = $this->getInterattivoDao()->categoria(3);
        if(count($loggato)>=1){
            $dati = array();
            foreach($loggato as $valor){
                $pos_id = $valor->getPosterlab()->getId();
                $nombreposterlab = $this->getPosterlabsDao()->tiempoId($pos_id)->getTitolo();
                //print_r($nombreposterlab);die;
                $dati[] = array(
    
                    'titolo'=>$nombreposterlab,
                    'data'=>$valor->getData(),
                    'testo'=>$valor->getMessaggio(),
                    'nickname'=>$valor->getNome(),
                );
            }
    
            $jsons = new JsonModel($dati);
            return $jsons;
        }else{
            $json = new JsonModel(array(array('data'=>'error')));
            return $json;
        }
         
    }
    
    
    

}
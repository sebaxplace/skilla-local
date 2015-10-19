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

class IndexController extends AbstractActionController
{
    
    public function getInterattivoDao() {
        if (!$this->InterattivoDao) {
            $sm = $this->getServiceLocator();
            $this->InterattivoDao = $sm->get('Interattivo\Model\InterattivoDao');
        }
        return $this->InterattivoDao;
    }
    
    public function indexAction()
    {
        return $this->redirect()->toRoute('interattivo', array('controller' => 'index', 'action' => 'categoriauno'));
    }
    
    public function categoriaunoAction()
    {
        
        $loggato = $this->getInterattivoDao()->categoria(1);
        print_r($loggato);die;
        if (!$loggato){
            $json = new JsonModel(array('data'=>'error'));
            return $json;
        }
        
        
        
        $posterlabid = $loggato->getId(); 
       // var_dump($posterlabid);die;
        $session_id = $this->getSessioniDao()->BuscoIdActiva($posterlabid)->getId();
        
        
            $json = new JsonModel(array(
                'data'=>'success', 
                'session'=>$session_id,
                
            ));
        
        
        return $json;
       
        
        
    }
    
   
    

}
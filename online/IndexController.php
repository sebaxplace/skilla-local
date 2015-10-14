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

class IndexController extends AbstractActionController
{
    
    
  
    public function getPosterlabsDao() {
        if (!$this->posterlabsDao) {
            $sm = $this->getServiceLocator();
            $this->posterlabsDao = $sm->get('Posterlabs\Model\PosterlabsDao');
        }
        return $this->posterlabsDao;
    }
    
    
    public function indexAction()
    {
         return $this->redirect()->toRoute('posterlabs', array('controller' => 'index', 'action' => 'login'));
    }
    
    public function loginAction()
    {
        if (!$this->request->isPost()) {
            $json = new JsonModel(array('data'=>'errorpost'));
            return $json;
        }
        $data = $this->getRequest()->getPost()->toArray();
        $password = $data['password'];
        $statosessione = $data['statosessione'];
        $loggato = $this->getPosterlabsDao()->autenticare($password);
        
        if (!$loggato){
            $json = new JsonModel(array('data'=>'error'));
            return $json;
        }
        
        $nuevapass = $loggato->getPassword();
        $arraysAreEqual = ($password === $nuevapass);
        
        if($arraysAreEqual == 1){
            $json = new JsonModel(array('data'=>'success'));
        }
        
        return $json;
        
        
    }
    
    public function loginsAction()
    {
         
        return new ViewModel(array('data'=>'success'));
    
    }
    
    
    
    

}
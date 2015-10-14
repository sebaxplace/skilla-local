<?php
/**
 * Zend Framework (http://framework.zend.com/)
*
* @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
* @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/
namespace Relatori\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;


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
        
        return $this->redirect()->toRoute('relatori', array('controller' => 'index', 'action' => 'login'));
    }
    
    public function loginAction()
    {
        $hola = array('hola');
        //print_r($hola);die;
        if (!$this->request->isPost()) {
            
            return new ViewModel(array('data'=>'error'));
        }
        $data = $this->getRequest()->getPost()->toArray();
        $password = $data['password'];
        $usuario = $this->getPosterlabsDao()->autenticare($password);
    
        return new ViewModel(array('data'=>'success'));
    }
    
}
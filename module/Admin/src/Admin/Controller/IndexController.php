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
use ArrayObject;
use Zend\View\Model\ViewModel;

use Admin\Form\Login;
use Admin\Model\Login as LoginService;

class IndexController extends AbstractActionController
{
    
   
    private $login;
    
    private function getForm(){
        $form = new Login();
        return $form;
    }
    
    public function setLogin(LoginService $login) {
        $this->login = $login;
    }
    
    public function indexAction()
    {
        
        $form = $this->getForm();
        return $this->forward()->dispatch('Admin\Controller\Index', array('action' => 'menu', 'mensaje'=>$form));
    }
    
    public function menuAction()
    {
        $form = $this->getForm();
        $loggedIn = $this->login->isLoggedIn();
        $viewParams = array('form'=>$form, 'loggedIn'=>$loggedIn);
        if($loggedIn){
            $viewParams['utenti'] = $this->login->getIdentity();
        }
        
        return $viewParams;
    }
    
    

}

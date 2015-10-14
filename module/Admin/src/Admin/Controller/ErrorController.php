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


class ErrorController extends AbstractActionController
{
    
   
   
    
    public function indexAction()
    {
       
         
        return $this->forward()->dispatch('Admin\Controller\Error', array('action' => 'denied'));
    }
    
    public function deniedAction()
    {
        
        
        return new ViewModel(array('mensaje'=>'Acceso denegado'));
    }
    
    

}

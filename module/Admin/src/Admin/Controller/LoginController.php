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
use Admin\Form\Login;
use Admin\Form\LoginValidator;
use Admin\Model\Login as LoginService;
use Zend\Crypt\Password\Bcrypt;

class LoginController extends AbstractActionController
{
    private $usuarioDao;
    private $config;
    private $login;
    
    public function setLogin(LoginService $login) {
        $this->login = $login;
    }
    public function getConfig()
    {
        if (!$this->config) {
            $sm = $this->getServiceLocator();
            $this->config = $sm->get('ConfigIniAdmin');
        }
    
    
        return $this->config;
         
    }
    
    
    public function getUsuarioDao() {
        if (null === $this->usuarioDao) {
            $sm = $this->getServiceLocator();
            $this->usuarioDao = $sm->get('Model\Dao\UtentiDao');
        }
        return $this->usuarioDao;
    }
    
    public function indexAction()
    {
        
         $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
        $this->layout('content/nuevolayout');
        $form = $this->getForm();
        $loggedIn = $this->login->isLoggedIn();
        $viewParams = array('form'=>$form, 'loggedIn'=>$loggedIn);
        
        if($loggedIn){
            //return $this->forward()->dispatch('Admin\Controller\Posterlabs', array('action' => 'index', 'mensaje'=>$form));
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() .'/admin/posterlabs');
        }
        
        return $viewParams;
      
    }
    
    public function autenticarAction()
    {
       
        if (!$this->request->isPost()) {
            $this->redirect()->toRoute('admin', array('controller' => 'login'));
        }
        
        $form = new Login("login");
        
        $form->setInputFilter(new LoginValidator());
        
        // Obtenemos los datos desde el Formulario con POST data:
        $datos = $this->request->getPost();
        
        $bcrypt = new Bcrypt(array(
            'salt' => 'aleatorio_salt_pruebas_victor',
            'cost' => 5));
        $securePass = $bcrypt->create($datos['password']);
        $password=$securePass;
        
        $nome = $datos['nome'];
        
        $data = array('nome'=>$nome, 'password'=>$password);
       
        $form->setData($data);
        
        // Validando el form
        if (!$form->isValid()) {
            $this->layout('content/nuevolayout');
            $modelView = new ViewModel(array('form' => $form));
            $modelView->setTemplate('admin/login/index');
            return $modelView;
        }
        
        $values = $form->getData();
        
        $email = $values['nome'];
        $clave = $values['password'];
        
        try {
            $this->login->setMessage('Il nome utente e password non coincidono.', LoginService::NOT_IDENTITY);
            $this->login->setMessage('La password inserita è errata, riprova ', LoginService::INVALID_CREDENTIAL);
            $this->login->setMessage('I campi nome e password non possono essere lasciati vuoti.', LoginService::INVALID_LOGIN);
            $this->layout('content/nuevolayout');
            
            $this->login->login($email, $clave);
            
            $this->layout('content/layout');
            
            //return $this->forward()->dispatch('Admin\Controller\Posterlabs', array('action' => 'index'));
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() .'/admin/relatori');
        } catch (\Exception $e) {
            $this->layout()->mensaje = $e->getMessage();
            $mensaje = $this->layout()->mensaje;
            $viewModel = new ViewModel(array(
                'form'=>$form,
                'mensaje'=> $this->layout()->mensaje,
            ));
            $viewModel->setTemplate("admin/login/index");
            return $viewModel;
        }
        
        
    }
    
   
    public function logoutAction() {
        $this->login->logout();
        $this->layout()->mensaje = 'Ha cerrado sesión conéxito!';
        return $this->forward()->dispatch('Admin\Controller\Login', array('action' => 'index'));
    }
    
    public function successAction() {
        return array('titulo' => 'Página de exito');
    }
    private function getForm(){
        $form = new Login();
        return $form;
    }
    
    
    
}

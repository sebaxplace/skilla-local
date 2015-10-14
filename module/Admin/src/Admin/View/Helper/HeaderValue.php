<?php
namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class HeaderValue extends AbstractHelper
{
    private $login;
    
   
    function __construct($login) {
        $this->login = $login;
    }
    
    public function __invoke()
    {
        return $this;
        
        
    }
    
    public function nome($catId)
    {
        $loggedIn = $this->login->isLoggedIn();
        $viewParams = $this->login->getIdentity()->nome;
        return  $this->view->escapeHtml($viewParams);
    }
    
    public function id($catId)
    {
        $loggedIn = $this->login->isLoggedIn();
        $viewParams = $this->login->getIdentity()->id;
        return  $this->view->escapeHtml($viewParams);
    }
    
    public function immagine($catId)
    {
        $loggedIn = $this->login->isLoggedIn();
        $viewParams = $this->login->getIdentity()->immagine;
        return  $viewParams;
    }
    
}
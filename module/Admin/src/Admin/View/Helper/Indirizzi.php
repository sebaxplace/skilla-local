<?php
namespace Admin\View\Helper;

use Zend\Http\Request;
use Zend\View\Helper\AbstractHelper;

class Indirizzi extends AbstractHelper
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
   
    
    public function __invoke()
    {
        return $this;
    
    
    }
    
    public function accion()
    {
        $direccion =  $this->request->getUri()->normalize();
        $desglose = explode('/', $direccion);
        return $desglose[6];
    }
    
    public function controlador($catId)
    {
        $direccion =  $this->request->getUri()->normalize();
        $desglose = explode('/', $direccion);
        return $desglose[5];
    }
}


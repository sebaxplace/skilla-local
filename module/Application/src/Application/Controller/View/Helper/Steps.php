<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Steps extends AbstractHelper
{

    private $contenutiDao;

    function __construct($contenutiDao)
    {
        $this->contenutiDao = $contenutiDao;
    }
    
    public function __invoke($catId)
    {
        $step = $this->contenutiDao()->defaultPerId($catId)->getTitolo();
        
        return $this->view->escapeHtml($step);
    }
    
}
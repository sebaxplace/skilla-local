<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Mensaje extends AbstractHelper
{

    public function __invoke()
    {
        return $this;
    }

    public function error($msg)
    {
        return "<div class='error'>{$this->view->escapeHtml($msg)}</div>";
    }

    public function success($msg)
    {
        return "<div class='success'>{$this->view->escapeHtml($msg)}</div>";
    }
}


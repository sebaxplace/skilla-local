<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Tabular extends AbstractHelper
{
    
    private $config;
    
    function __construct($config)
    {
        $this->config = $config;
    }
    
    public function __invoke($config)
    {
    return "<table>
                <tr>
                    <th>".$config->data."</th>
                    <th></th>        
                </tr>
                
                <tr>
                    <td></td>  
                    <td></td>        
                </tr>
            </table>";
    
    }
}
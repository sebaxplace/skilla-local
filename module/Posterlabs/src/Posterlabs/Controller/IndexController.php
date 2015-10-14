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
    
    public function getSessioniDao() {
        if (!$this->sessioniDao) {
            $sm = $this->getServiceLocator();
            $this->sessioniDao = $sm->get('Sessioni\Model\SessioniDao');
        }
        return $this->sessioniDao;
    }
    
    
    public function indexAction()
    {
         return $this->redirect()->toRoute('posterlabs', array('controller' => 'index', 'action' => 'login'));
    }
    
    public function loginAction()
    {
        
        $request = $this->getRequest();
        $result = new JsonModel($request->getPost()->toArray());
        $decodificado = json_decode($result);
        print_r($decodificado);die;
        if (!$this->getRequest()->getPost()) {
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
        $this->setTerminal(true);
        return $json;
        
        
    }
    
public function guardarAction() {
        $this->getConfig();
        $menu = $this->config['parametros']['menu1'];
        $this->layout()->menu = $menu;
    
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('admin', array('controller' => 'interattivo', 'action'=>'index'));
        }
        
        $xposition = rand(2, 1000);
        $yposition = rand(3, 850);
        $zposition = rand(1, 100);
        
        //print_r($xposition.'x'.$yposition.'x'.$zposition);die;
        
        $patron = 'Y-m-d H:i';
        $fecha = new DateTime();
        $form =  new Interact("interact");
        $form->get('nome')->setValue('default');
        $form->get('posterlab')->setValueOptions($this->getInterattivoDao()->obtenerPosterlabsSelect());
        //print_r($this->llenarListaTipo());die;
        $form->get('tipo')->setValueOptions($this->llenarListaTipo());
        $form->get('data')->setValue($fecha->format($patron));
        $form->get('sessione')->setValue(0);
        $form->get('color')->setValueOptions($this->llenarColor());
        
        $form->get('xyz')->setValue($xposition.'x'.$yposition.'x'.$zposition);
    
        $form->setInputFilter(new InteractValidator());
         
        $data = $this->getRequest()->getPost()->toArray();
      
        $form->setData($data);
         
        // Validando el form
        if (!$form->isValid()) {
            $modelView = new ViewModel(array('title' => 'Aggiorno', 'form' => $form, 'volver'=>'Indietro','titulo' => 'Modifica contenuto',));
            $modelView->setTemplate('admin/interattivo/crear');
            return $modelView;
        }
        $dataForms = $form->getData();
        $dataForms['posterlab_id'] = $dataForms['posterlab'];
        $producto = new Interattivo();
        //print_r($dataForms);die;
        $producto->exchangeArray($dataForms);
        $this->getInterattivoDao()->salvare($producto);
        return $this->redirect()->toRoute('admin', array('controller' => 'interattivo', 'action' => 'index'));
        }
    
    
    
    

}
<?php

namespace Relatori\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Description of EjemploServidorRestfulController
 *
 * @author Andres
 */
class EjemploServidorRestfulController extends AbstractRestfulController {
    
    public function getRelatoriDao() {
        if (!$this->relatoriDao) {
            $sm = $this->getServiceLocator();
            $this->relatoriDao = $sm->get('Relatori\Model\RelatoriDao');
        }
        return $this->relatoriDao;
    }
    
    public function listadoCursos($id = null) {
        $cursos = $this->getRelatoriDao()->tutti()->toArray();
            $contenido = array();
            foreach($cursos as $key){
                $imagen = $key['immagine'];
                $urlimagen = explode('/', $imagen);
                $nuewvaurlimg = $urlimagen[2].'/'.$urlimagen[3].'/'.$urlimagen[4];
                $key['immagine'] = $nuewvaurlimg;
                $contenido[] = $key;
            }
            
            
           //print_r($contenido);die;
        
        return $contenido;
    }

    public function getList() {
        
        $json = new JsonModel($this->listadoCursos());
        return $json;
    }

    public function get($id) {

        $json = new JsonModel(array(
                    'data' => $this->listadoCursos($id)));
        return $json;
    }

    public function create($data) {
        $json = new JsonModel(array(
                    'success' => true, 'data' => $data));
        return $json;
    }

    public function update($id, $data) {
        $json = new JsonModel(array(
                    'success' => true, 'id' => $id, 'data' => $data));
        return $json;
    }

    public function delete($id) {
        $json = new JsonModel(array(
                    'success' => true, 'eliminado' => $this->listadoCursos($id),));
        return $json;
    }
}


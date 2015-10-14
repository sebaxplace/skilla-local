<?php

namespace Sessioni\Model\Dao;


use Sessioni\Model\Entity\Sessioni;

use Zend\Db\TableGateway\TableGateway;
//use Zend\Db\Sql\Select;
use DateTime;
/**
 * Description of UtentiDao
 *
 * @author Andres
 */
class SessioniDao {

    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    

    public function tutti() {
        $select = $this->tableGateway->getSql()->select();
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }
    

    public function tuttiPerId($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Non ho trovato nessun id =  $id");
        }
        return $row;
    }
    
    public function tuttiPerIdAttivo($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Non ho trovato nessun id =  $id");
        }
        return $row;
    }
    
    
   
    
   
    
    
    public function eliminare(Sessioni $sessioni) {
        $this->tableGateway->delete(array('id' => $sessioni->getId()));
    }
    
    
    public function chiudisession(Sessioni $sessioni) {
        $patron = 'Y-m-d H:i';
        $fecha = new DateTime();
        $fine = $fecha->format($patron);
        $data = array(
            'data_fine'=>$fine,
            'posterlab_id' => $sessioni->getPosterlab(),
            'stato'=> 0,
        );
    
    
        $posterlab = (int) $sessioni->getPosterlab();
        $this->tableGateway->update($data, array('posterlab_id' => $posterlab, 'stato'=>1));
             
    }
    
    
    
    public function salvare(Sessioni $sessioni) {
       
        $data = array(
            'id' => $sessioni->getId(),
            'posterlab_id' => $sessioni->getPosterlab(),
            'relatori_id' => $sessioni->getRelatore(),
            'data_inizio' => $sessioni->getInizio(),
            'data_fine' => $sessioni->getFine(),
            'stato'=> $sessioni->getStato(),
        );
    
        
    
        $id = (int) $sessioni->getId();
    
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->tuttiPerId($id)) {
                
                    $this->tableGateway->update($data, array('id' => $id));
                
                
            } else {
                throw new \Exception('ID form non esistente');
            }
        }
    }
//para finalizar
  
    
    public function BuscoId($id) {
       $id = (int) $id;
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('posterlab_id' => $id));
        $select->order('data_inizio DESC');
        $select->limit(1, 0);
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        $producto = $row;
        
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $producto;
    }
    
    
    public function BuscoIdActiva($id) {
        $id = (int) $id;
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('posterlab_id' => $id, 'stato'=>1));
        $select->order('data_inizio DESC');
        $select->limit(1, 0);
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        $producto = $row;
    
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $producto;
    }
    

    
    public function autenticare($nome, $password) {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('email' => $nome, 'password' => $password,));
        
        return $this->tableGateway->selectWith($select)->current();
    }
    
    

}

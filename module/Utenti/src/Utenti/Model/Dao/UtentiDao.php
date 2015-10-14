<?php

namespace Utenti\Model\Dao;


use Utenti\Model\Entity\Utenti;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
/**
 * Description of UtentiDao
 *
 * @author Andres
 */
class UtentiDao {

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
    
   
    
    
    public function eliminare(Utenti $utenti) {
        $this->tableGateway->delete(array('id' => $utenti->getId()));
    }
    
    public function salvare(Utenti $utenti) {
        
        $data = array(
            'nome' => $utenti->getNome(),
            'email' => $utenti->getEmail(),
            'password' => $utenti->getPassword(),
            'immagine' => $utenti->getImmagine(),
            'stato'=> $utenti->getStato(),
        );
    
        
    
        $id = (int) $utenti->getId();
    
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->tuttiPerId($id)) {
                if($data['immagine'] == null){
                    $this->tableGateway->update(array('id' => $id), array('nome' =>$data['nome']), array('email' =>$data['email']), array('stato' =>$data['stato']), array('password' =>$data['password'])); 
                }else{
                    $this->tableGateway->update($data, array('id' => $id));
                }
                
            } else {
                throw new \Exception('ID form non esistente');
            }
        }
    }
//para finalizar
    public function cercaPerNome($nome) {
        $rowset = $this->tableGateway->select(function(Select $select) use ($nome){
            $select->where->like('nome', '%'. $nome. '%');
            $select->order('nome ASC');
        });

       

        if (!$rowset) {
            throw new \Exception("Non ho trovato nessuno nome =  $nome");
        }
        return $rowset;
        
            
    }
    
    public function immaginePerId($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Non ho trovato nessun id =  $id");
        }
        return $row;
    }
    

    
    public function autenticare($nome, $password) {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('email' => $nome, 'password' => $password,));
        
        return $this->tableGateway->selectWith($select)->current();
    }
    
    

}

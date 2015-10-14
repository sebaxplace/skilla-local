<?php

namespace Relatori\Model\Dao;


use Relatori\Model\Entity\Relatori;
use Relatori\Model\Entity\Abilitato;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
/**
 * Description of RelatoriDao
 *
 * @author Andres
 */
class RelatoriDao {

    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    

    public function tutti() {
        $select = $this->tableGateway->getSql()->select();
        $resultSet = $this->tableGateway->selectWith($select);
        //print_r($resultSet);die;
        
        
        return $resultSet;
    }
    
    public function tuttiApp() {
        $select = $this->tableGateway->getSql()->select();
        $resultSet = $this->tableGateway->selectWith($select);
         return $resultSet;
    
    }
    
    public function obtenerRelatori() {
        
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->where(array('stato' => 1));
        $select->from('relatori');


        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $relatores = new \ArrayObject();
        foreach ($results as $row) {
            $relator = new Abilitato();
            $relator->exchangeArray($row);
            $relatores->append($relator);
        }

        return $relatores;
    }

    public function obtenerRelatoriSelect() {
        $relatores = $this->obtenerRelatori();
        $result = array();
        foreach ($relatores as $cat) {
            $result[$cat->getId()] = $cat->getNome().' '.$cat->getCognome();
        }
        return $result;
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
    public function eliminare(Relatori $relatori) {
        $this->tableGateway->delete(array('id' => $relatori->getId()));
    }
    
    public function salvare(Relatori $relatori) {
        
        $data = array(
            'nome' => $relatori->getNome(),
            'cognome' => $relatori->getCognome(),
            'email' => $relatori->getEmail(),
           // 'password' => $relatori->getPassword(),
            'link' => $relatori->getLink(),
            'immagine' => $relatori->getImmagine(),
            'titolo' => $relatori->getTitolo(),
            'testo' => $relatori->getTesto(),
            'stato'=> $relatori->getStato(),
        );
    
        
    
        $id = (int) $relatori->getId();
    
        if ($id == 0) {
            $data['immagine'] = $relatori->getImmagine();
            $this->tableGateway->insert($data);
        } else {
            if ($this->tuttiPerId($id)) {
                if($data['immagine'] != null){
                    
                    $data['immagine'] =$relatori->getImmagine();
                }else{
                    $data['immagine']= $this->tuttiPerId($id)->getImmagine();
                }
                $this->tableGateway->update($data, array('id' => $id));
                
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
    
    
    

    
    public function autenticare($nome, $password) {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('email' => $nome, 'password' => $password,));
        
        return $this->tableGateway->selectWith($select)->current();
    }
    
    

}

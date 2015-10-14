<?php

namespace Posterlabs\Model\Dao;


use Posterlabs\Model\Entity\Posterlabs;
use Relatori\Model\Entity\Relatori;
use Relatori\Model\Entity\Abilitato;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
/**
 * Description of PosterlabsDao
 *
 * @author Andres
 */
class PosterlabsDao {

    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    

    public function tutti() {
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('rel' => 'relatori'), 'rel.id = posterlabs.relatori_id', array('relatoriId' => 'id', 'relatoriNome' => 'nome', 'relatoriCognome' => 'cognome'));
        $select->order("id");

        //echo $select->getSqlString();
        //die;

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }
    
    public function tuttiAttivi() {
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('rel' => 'relatori'), 'rel.id = posterlabs.relatori_id', array('relatoriId' => 'id', 'relatoriNome' => 'nome', 'relatoriCognome' => 'cognome'));
        $select->where(array('posterlabs.stato'=>1));
        $select->order("id");
    
        //echo $select->getSqlString();
        //die;
    
        $resultSet = $this->tableGateway->selectWith($select);
    
        return $resultSet;
    }

    public function tuttiPerId($id) {
        $id = (int) $id;
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('rel' => 'relatori'), 'rel.id = posterlabs.relatori_id', array('relatoriId' => 'id', 'relatoriNome' => 'nome', 'relatoriCognome' => 'cognome'))->where(array('posterlabs.id' => $id));
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
        $producto = $resultSet->current();
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $producto;
       
    }
    
    public function tiempoId($id) {
        $id = (int) $id;
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('id' => $id));
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
        $producto = $resultSet->current();
       
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $producto;
         
    }
    
    
    public function eliminare(Posterlabs $posterlabs) {
        $this->tableGateway->delete(array('id' => $posterlabs->getId()));
    }
    
    public function iniziaSession($id) {
        $id = (int) $id;
        $generate = $this->randCode();
        $data['statosessione'] = 1;
        $data['password']=$generate;
        $data['password2']=$generate;
        $this->tableGateway->update($data, array('id' => $id));
        
       
    }
    
    public function chiudiSession($id) {
        $id = (int) $id;
        $data['statosessione'] = 0;
         
        $this->tableGateway->update($data, array('id' => $id));
    
         
    }
    
    
    public function posicionActual($id, $posicion) {
        $id = (int) $id;
        $posicion = (int) $posicion;
        $data['actual'] = $posicion;
        $this->tableGateway->update($data, array('id' => $id));
    
         
    }
    
    public function salvare(Posterlabs $posterlabs) {
        $generate = $this->randCode();
        $data = array(
            'titolo' => $posterlabs->getTitolo(),
            'password' => $generate,
            'password2' => $generate,
            'steps' => $posterlabs->getSteps(),
            'nota' => $posterlabs->getNota(),
            'durata' => $posterlabs->getDurata(),
            'link' => $posterlabs->getLink(),
            'statosessione'=> $posterlabs->getStatosessione(),
            'stato'=> $posterlabs->getStato(),
        );
    
        if ($posterlabs->getRelatori() !== null) {
            $data['relatori_id'] = $posterlabs->getRelatori()->getId();
        }
    
        $id = (int) $posterlabs->getId();
    
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->tuttiPerId($id)) {
                if($data['password'] != null){
                    $this->tableGateway->update($data, array('id' => $id));
                    }else{
                        $datos = array(
                            'titolo' => $posterlabs->getTitolo(),
                           
                            'steps' => $posterlabs->getSteps(),
                            'nota' => $posterlabs->getNota(),
                            'durata' => $posterlabs->getDurata(),
                            'link' => $posterlabs->getLink(),
                            'statosessione'=> $posterlabs->getStatosessione(),
                            'stato'=> $posterlabs->getStato(),
                        );
                        $this->tableGateway->update($datos, array('id' => $id));
                }
                
            } else {
                throw new \Exception('ID form non esistente');
            }
        }
    }
//para finalizar
    public function cercaPerNome($nome) {
        $rowset = $this->tableGateway->select(function(Select $select) use ($nome){
            $select->where->like('titolo', '%'. $nome. '%');
            $select->order('titolo ASC');
        });

       

        if (!$rowset) {
            throw new \Exception("Non ho trovato nessuno nome =  $nome");
        }
        return $rowset;
        
            
    }
    
    
    
    public function obtenerRelatori() {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from('relatori');
    
    
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $categorias = new \ArrayObject();
        foreach ($results as $row) {
            $categoria = new Abilitato();
            $categoria->exchangeArray($row);
            $categorias->append($categoria);
        }
    
        return $categorias;
    }
    
    public function obtenerRelatoriSelect() {
        $categorias = $this->obtenerRelatori();
        $result = array();
        foreach ($categorias as $cat) {
            $result[$cat->getId()] = $cat->getNome();
        }
        return $result;
    } 
    

    
    public function autenticare($password) {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('password' => $password,'statosessione'=>1));
        
        return $this->tableGateway->selectWith($select)->current();
    }
    
    private function randCode($length = 5){
        $ranges = array(range('a', 'z'), range('A', 'Z'), range(1, 9));
        $code = '';
        for($i = 0; $i < $length; $i++){
            $rkey = array_rand($ranges);
            $vkey = array_rand($ranges[$rkey]);
            $code .= $ranges[$rkey][$vkey];
        }
        return $code;
    }

}

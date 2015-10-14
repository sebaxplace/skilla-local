<?php

namespace Contenuti\Model\Dao;


use Contenuti\Model\Entity\Contenuti;
use Posterlabs\Model\Entity\Posterlabs;
use Posterlabs\Model\Entity\PosterlabsAbilitato;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
/**
 * Description of PosterlabsDao
 *
 * @author Andres
 */
class ContenutiDao {

    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    

    public function tutti() {
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('pos' => 'posterlabs'), 'pos.id = contenuti.posterlab_id', array('posterlabId' => 'id', 'posterlabTitolo' => 'titolo'));
        $select->order("id");

        //echo $select->getSqlString();
        //die;

        $resultSet = $this->tableGateway->selectWith($select);
       // print_r(count($resultSet));die;
        return $resultSet;
    }

    public function tuttiPerId($id) {
        $id = (int) $id;
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('pos' => 'posterlabs'), 'pos.id = contenuti.posterlab_id', array('posterlabId' => 'id', 'posterlabTitolo' => 'titolo'))->where(array('contenuti.id' => $id));
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
        $producto = $resultSet->current();
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $producto;
    }
    
    public function editaposicionPerId($id) {
        $id = (int) $id;
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('posterlab_id' => $id));
        //print_r($select);die;
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
       
        if (!$resultSet) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $resultSet;
    }
    
    
    
    public function cercaPerPosterlab($id) {
        $id = (int) $id;
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('posterlab_id' => $id));
        $select->order("posizione ASC");
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
         
        if (!$resultSet) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $resultSet;
    }
    
    public function buscaId($ids) {
        $ids = (int) $ids;
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('id' => $ids));
        
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
        $producto = $resultSet->current();
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $ids);
        }
        return $producto;
    }
    
    
    
    public function relatorePerId($id) {
        $id = (int) $id;
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('pos' => 'posterlabs'), 'pos.id = contenuti.posterlab_id', array('posterlabId' => 'id', 'posterlabTitolo' => 'titolo'))->where(array('contenuti.posterlab_id' => $id, 'contenuti.stato' => 1));
        //echo $select->getSqlString();die;
        $select->order("contenuti.posizione ASC");
        
        $resultSet = $this->tableGateway->selectWith($select);
        $producto = $resultSet;
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $producto;
    }
    
    
    
    public function defaultPerId($id) {
        $id = (int) $id;
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('pos' => 'posterlabs'), 'pos.id = contenuti.posterlab_id', array('posterlabId' => 'id', 'posterlabTitolo' => 'titolo'))->where(array('contenuti.posterlab_id' => $id, 'contenuti.stato' => 1));
        //echo $select->getSqlString();die;
        $select->order("contenuti.posizione ASC");
        $select->limit(1, 0);
        $resultSet = $this->tableGateway->selectWith($select);
         $producto = $resultSet->current();
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $producto;
    }
    
    
    
    public function eliminare(Contenuti $contenuti) {
        $this->tableGateway->delete(array('id' => $contenuti->getId()));
    }
    
    public function salvare(Contenuti $contenuti) {
        
        $data = array(
            'titolo' => $contenuti->getTitolo(),
            'contenuto' => $contenuti->getContenuto(),
            'background' => $contenuti->getBackground(),
            'posizione' => $contenuti->getPosizione(),
            'tipo' => $contenuti->getTipo(),
            'stato'=> $contenuti->getStato(),
        );
    
        if ($contenuti->getPosterlab() !== null) {
            $data['posterlab_id'] = $contenuti->getPosterlab()->getId();
        }
    
        $id = (int) $contenuti->getId();
    
        if ($id == 0) {
           $data['background'] = $contenuti->getBackground();
            $this->tableGateway->insert($data);
        } else {
            if ($this->tuttiPerId($id)) {
                //$imagenalmacen= ;
                
                if($data['background'] != null){
                    
                    $data['background'] =$contenuti->getBackground();
                }else{
                    $data['background']= $this->tuttiPerId($id)->getBackground();
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
            $select->where->like('titolo', '%'. $nome. '%');
            $select->order('titolo ASC');
        });

       

        if (!$rowset) {
            throw new \Exception("Non ho trovato nessuno nome =  $nome");
        }
        return $rowset;
        
            
    }
    
    public function obtenerPosterlabActual($id) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from('posterlabs');
        $select->where(array('id'=> $id));
        $resultSet = $this->tableGateway->selectWith($select);
        $producto = $resultSet->current();
       
        return $producto;
    }
    
    public function obtenerPosterlabs() {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from('posterlabs');
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $categorias = new \ArrayObject();
        foreach ($results as $row) {
            $categoria = new PosterlabsAbilitato();
            $categoria->exchangeArray($row);
            $categorias->append($categoria);
        }
       // var_dump($categorias);die;
        return $categorias;
    }
    
    public function obtenerPosterlabsSelect() {
        $categorias = $this->obtenerPosterlabs();
        
        $result = array();
        foreach ($categorias as $cat) {
            $result[$cat->getId()] = $cat->getTitolo();
        }
        
        return $result;
    }

    
    public function listaPosizioni($id) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from('posterlabs');
        $select->where(array('id'=>$id));
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        
        $categorias = new \ArrayObject();
        foreach ($results as $row) {
            $categoria = new PosterlabsAbilitato();
            $categoria->exchangeArray($row);
            $categorias->append($categoria);
        }
        
        foreach($categorias as $linea){
            $limite = $linea->getSteps();
        }
        
        
        $endb = $this->cercaPerPosterlab($id);
        
        $presentes = array();
        foreach($endb as $sqls){
            $presentes[$sqls->getPosizione()] = $sqls->getPosizione();
        }
        
       
        
        $lista = array();
        for($i=1;$i<=$limite;$i++){
                $lista[$i] = $i;
            }
            
       $result = array_diff($lista, $presentes);
        
       // print_r($lista);die;//var_dump($categorias);die;
        return $result;
    }
    

    
    public function autenticare($nome, $password) {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('email' => $nome, 'password' => $password,));
        
        return $this->tableGateway->selectWith($select)->current();
    }
    
    

}

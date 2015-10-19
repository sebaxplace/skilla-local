<?php

namespace Interattivo\Model\Dao;


use Interattivo\Model\Entity\Interattivo;
use Posterlabs\Model\Entity\Posterlabs;
use Posterlabs\Model\Entity\PosterlabsAbilitato;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
/**
 * Description of UtentiDao
 *
 * @author Andres
 */
class InterattivoDao {

    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    

    public function tutti() {
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('pos' => 'posterlabs'), 'pos.id = interattivo.posterlab_id', array('posterlabId' => 'id', 'posterlabTitolo' => 'titolo'));
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
        $select->where(array('posterlab_id' => $id, 'stato'=>1));;
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
        $producto = $resultSet;
        
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $producto;
    }
    
    public function todosPerId($id) {
        $id = (int) $id;
       
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('id' => $id));;
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
        $producto = $resultSet->current();
        
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $producto;
    }
    
    
    public function posiciones($id, $sessione) {
        $id = (int) $id;
        $sessione = (int) $sessione;
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('posterlab_id' => $id, 'sessione'=>array(0,$sessione), 'tipo'=>1, 'stato'=>1));
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
        $producto = $resultSet;
        
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $producto;
    }
    
    public function posiciones2($id, $sessione) {
        $id = (int) $id;
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('posterlab_id' => $id, 'sessione'=>array(0, $sessione), 'stato'=>1));
        //echo $select->getSqlString();die;
        //print_r($id);die;
        $resultSet = $this->tableGateway->selectWith($select);
        $producto = $resultSet;
    
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $producto;
    }
    
    public function categoria($id) {
        $id = (int) $id;
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('categoria' => $id, 'stato'=>1));
        $resultSet = $this->tableGateway->selectWith($select);
         
        if (!$resultSet) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $resultSet;
    }
    
    
    public function eliminare(Interattivo $interattivo) {
        $this->tableGateway->delete(array('id' => $interattivo->getId()));
    }
    
    public function salvare(Interattivo $interattivo) {
        
        $data = array(
            'nome'=> $interattivo->getNome(),
            'messaggio' => $interattivo->getMessaggio(),
            'color' => $interattivo->getColor(),
            'xyz' => $interattivo->getXyz(),
            
            'tipo' => $interattivo->getTipo(),
            'sessione'=> $interattivo->getSessione(),
            'data' => $interattivo->getData(),
            'stato'=> $interattivo->getStato(),
            'categoria'=> $interattivo->getCategoria(),
           
        );
        
       
       
        if ($interattivo->getPosterlab() !== null) {
            $data['posterlab_id'] = $interattivo->getPosterlab()->getId();
        }
       
        
        $id = (int) $interattivo->getId();
    
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->tuttiPerId($id)) {
                    $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('ID form non esistente');
            }
        }
        
        $ids = $this->tableGateway->lastInsertValue;
        
        return  $ids;
    }
    
    
    public function salvare2(Interattivo $interattivo) {
        
        $data = array('xyz' => $interattivo->getXyz(),);
        
        $id = (int) $interattivo->getId();
        $xyz = $interattivo->getXyz();
    
        
            if ($this->tuttiPerId($id)) {
                    $this->tableGateway->update(array ('xyz' => $xyz), array('id' => $id));
            }
    }
    
    public function salvare3(Interattivo $interattivo) {
    
        $data = array('categoria' => $interattivo->getCategoria(),);
    
        $id = (int) $interattivo->getId();
        $categoria = $interattivo->getCategoria();
    
    
        if ($this->tuttiPerId($id)) {
            $this->tableGateway->update(array ('categoria' => $categoria), array('id' => $id));
        }
    }
    
    
    public function elimina2(Interattivo $interattivo) {
    
        $data = array('stato' => $interattivo->getStato(),);
    
        $id = (int) $interattivo->getId();
        $stato = $interattivo->getStato();
    
    
        if ($this->tuttiPerId($id)) {
            $this->tableGateway->update(array ('stato' => $stato), array('id' => $id));
        }
    }    
    
    
    public function cercaPerPosterlab($id, $idsession, $categoria, $stato) {
        $id = (int) $id;
        $idsession = (int) $idsession;
        $categoria = (int) $categoria;
        $stato = (int) $stato;
        
        $data = array($id, $idsession, $categoria, $stato);
       
        
        
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('posterlab_id' => $id));
        $select->where(array('sessione'=>$idsession));
        if($categoria != 0){
            $select->where(array('categoria'=>$categoria));
        }
        if($stato != 0){
            if($stato == 2){$stato = 0;}
        $select->where(array('stato'=>$stato));
        }
       // $select->greaterThan('');
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
         
        if (!$resultSet) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $resultSet;
    }
    
    public function cercaPerPosterlabAttivo($id, $last) {
        $id = (int) $id;
        $last = (int) $last;
        
        //print_r($last);die;
        $select = $this->tableGateway->getSql()->select();
        //$select->where(array('posterlab_id' => $id, 'stato'=>1 ));
        $select->where(new \Zend\Db\Sql\Predicate\PredicateSet(
        array(
            new \Zend\Db\Sql\Predicate\Operator('id', '>', $last),
            new \Zend\Db\Sql\Predicate\Operator('posterlab_id', '=', $id),
            new \Zend\Db\Sql\Predicate\Operator('stato', '=', 1),
        ),
        // optional; OP_AND is default
        \Zend\Db\Sql\Predicate\PredicateSet::OP_AND
    ),
    // optional; OP_AND is default
    \Zend\Db\Sql\Predicate\PredicateSet::OP_OR);
        
        $resultSet = $this->tableGateway->selectWith($select);
        //echo $select->getSqlString();die;
       
        //print_r($resultSet);die;
        if (!$resultSet) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
        return $resultSet;
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
    
    
    public function posterysession($id, $sessione) {
        $id = (int) $id;
        $sessione = (int) $sessione;
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('posterlab_id' => $id, 'sessione'=>$sessione));
        //echo $select->getSqlString();die;
        $resultSet = $this->tableGateway->selectWith($select);
        $producto = $resultSet;
    
        if (!$producto) {
            throw new \Exception('Non ho trovato nessun id ' . $id);
        }
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
    
    
    
    public function obtenerSessioni($id) {
        $id = (int) $id;
        $select = $this->tableGateway->getSql()->select()->quantifier(\Zend\Db\Sql\Select::QUANTIFIER_DISTINCT);
        $select->columns(array('sessione'));
        $select->where(array('posterlab_id'=>$id));
        $resultSet = $this->tableGateway->selectWith($select);
       
        if (!$resultSet) {
            throw new \Exception('Non ho trovato nessun id ');
        }
        return $resultSet;
    }
    
    public function obtenerSessioniSelect($id) {
        $id = (int) $id;
        $categorias = $this->obtenerSessioni($id);
        //print_r($categorias['sessione']);die;
        $result = array();
        foreach ($categorias as $cat) {
            $result[$cat->getSessione()] = $cat->getSessione();
        }
    
        return $result;
    }
    
    

}

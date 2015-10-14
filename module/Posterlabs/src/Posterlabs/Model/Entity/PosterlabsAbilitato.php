<?php

namespace Posterlabs\Model\Entity;

class PosterlabsAbilitato {

    private $id;
    private $nome;

    public function __construct($id = null, $nome = null) {
        $this->id = $id;
        $this->nome = $nome;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitolo() {
        return $this->titolo;
    }

    public function setTitolo($titolo) {
        $this->titolo = $titolo;
    }
    
    public function getSteps() {
        return $this->steps;
    }
    
    public function setSteps($steps) {
        $this->steps = $steps;
    }
    

    
  

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->titolo = (isset($data['titolo'])) ? $data['titolo'] : null;
        $this->steps = (isset($data['steps'])) ? $data['steps'] : null;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

}
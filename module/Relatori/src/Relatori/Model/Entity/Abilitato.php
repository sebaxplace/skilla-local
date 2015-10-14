<?php

namespace Relatori\Model\Entity;

class Abilitato {

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

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getCognome() {
        return $this->cognome;
    }
    
    public function setCognome($cognome) {
        $this->cognome = $cognome;
    }

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->nome = (isset($data['nome'])) ? $data['nome'] : null;
        $this->cognome = (isset($data['cognome'])) ? $data['cognome'] : null;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

}
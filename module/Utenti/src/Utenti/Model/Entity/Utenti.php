<?php

namespace Utenti\Model\Entity;

class Utenti 
{
	private $id;
	
	private $nome;
	
	private $email;
	
	private $password;
	
	private $immagine;
	
	private $stato = false;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id) {
	    $this->id = $id;
	}
	
	
	public function getNome()
	{
		return $this->nome;
	}
	
	public function setNome($nome) {
	    $this->nome = $nome;
	}
	
	public function getEmail()
	{
	    return $this->email;
	}
	
	public function setEmail($email) {
	    $this->email = $email;
	}
	
	public function getPassword()
	{
	    return $this->password;
	}
	
	public function setPassword($password) {
	    $this->password = $password;
	}
	
	public function getImmagine()
	{
	    return $this->immagine;
	}
	
	public function setImmagine($immagine) {
	    $this->immagine = $immagine;
	}
	
    public function getStato(){
	    return $this->stato;
	}
	
	public function setStato($stato){
	    $this->stato = $stato;
	}
	
	public function exchangeArray($data) {
	    $this->id = (isset($data['id'])) ? $data['id'] : null;
	    $this->nome = (isset($data['nome'])) ? $data['nome'] : null;
	    $this->email = (isset($data['email'])) ? $data['email'] : null;
	    $this->password = (isset($data['password'])) ? $data['password'] : null;
	    $this->immagine = (isset($data['immagine'])) ? $data['immagine'] : null;
	    $this->stato = (isset($data['stato'])) ? $data['stato'] : null;
	   
	}
	
	public function getArrayCopy() {
	    return get_object_vars($this);
	}
}


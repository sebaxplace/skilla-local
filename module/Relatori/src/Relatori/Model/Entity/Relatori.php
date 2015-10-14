<?php

namespace Relatori\Model\Entity;

class Relatori 
{
	private $id;
	
	private $nome;
	
	private $cognome;
	
	private $email;
	
	private $password;
	
	private $link;
	
	private $immagine;
	
	private $titolo;
	
	private $testo;
	
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
	
	public function getCognome()
	{
	    return $this->cognome;
	}
	
	public function setCognome($cognome) {
	    $this->cognome = $cognome;
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
	
	
	public function getLink()
	{
	    return $this->link;
	}
	
	public function setLink($link) {
	    $this->link = $link;
	}
	
	public function getImmagine()
	{
	    return $this->immagine;
	}
	
	public function setImmagine($immagine) {
	    $this->immagine = $immagine;
	}
	
	public function getTitolo()
	{
	    return $this->titolo;
	}
	
	public function setTitolo($titolo) {
	    $this->titolo = $titolo;
	}
	
	public function getTesto()
	{
	    return $this->testo;
	}
	
	public function setTesto($testo) {
	    $this->testo = $testo;
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
	    $this->cognome = (isset($data['cognome'])) ? $data['cognome'] : null;
	    $this->email = (isset($data['email'])) ? $data['email'] : null;
	    $this->password = (isset($data['password'])) ? $data['password'] : null;
	    $this->link = (isset($data['link'])) ? $data['link'] : null;
	    $this->immagine = (isset($data['immagine'])) ? $data['immagine'] : null;
	    $this->titolo = (isset($data['titolo'])) ? $data['titolo'] : null;
	    $this->testo = (isset($data['testo'])) ? $data['testo'] : null;
	    $this->stato = (isset($data['stato'])) ? $data['stato'] : null;
	   
	}
	
	public function getArrayCopy() {
	    return get_object_vars($this);
	}
}


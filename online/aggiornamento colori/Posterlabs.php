<?php

namespace Posterlabs\Model\Entity;
use Relatori\Model\Entity\Relatori;


class Posterlabs 
{
	private $id;
	
	private $titolo;
	
	private $password;
	
	private $password2;
	
	private $relatori;
	
	private $steps;
	
	private $nota;
	
	private $durata;
	
	private $link;
	
	private $statosessione;
	
	private $stato = false;
	
	private $actual = false;
	
	
	
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id) {
	    $this->id = $id;
	}
	
	
	public function getTitolo()
	{
		return $this->titolo;
	}
	
	public function setTitolo($titolo) {
	    $this->titolo = $titolo;
	}
	
	
	public function getPassword()
	{
	    return $this->password;
	}
	
	public function setPassword($password) {
	    $this->password = $password;
	}
	
	public function getPassword2()
	{
	    return $this->password2;
	}
	
	public function setPassword2($password2) {
	    $this->password2 = $password2;
	}
	
	public function getRelatori()
	{
	    return $this->relatori;
	}
	
	public function setRelatori(Relatori $relatori) {
	    $this->relatori = $relatori;
	}
	
	
	
	public function getSteps()
	{
	    return $this->steps;
	}
	
	public function setSteps($steps) {
	    $this->steps = $steps;
	}
	
	public function getNota()
	{
	    return $this->nota;
	}
	
	public function setNota($nota) {
	    $this->nota = $nota;
	}
	
	public function getDurata()
	{
	    return $this->durata;
	}
	
	public function setDurata($durata) {
	    $this->durata = $durata;
	}
	
	public function getLink()
	{
	    return $this->link;
	}
	
	public function setLink($link) {
	    $this->link = $link;
	}
	
	public function getStatosessione(){
	    return $this->statosessione;
	}
	
	public function setStatosessione($statosessione){
	    $this->statosessione = $statosessione;
	}
	
	public function getStato(){
	    return $this->stato;
	}
	
	public function setStato($stato){
	    $this->stato = $stato;
	}
	
	public function getActual(){
	    return $this->actual;
	}
	
	public function setActual($actual){
	    $this->actual = $actual;
	}
	
	public function exchangeArray($data) {
	    $this->id = (isset($data['id'])) ? $data['id'] : null;
	    $this->titolo = (isset($data['titolo'])) ? $data['titolo'] : null;
	    $this->password = (isset($data['password'])) ? $data['password'] : null;
	    $this->password2 = (isset($data['password2'])) ? $data['password2'] : null;
	    $this->steps = (isset($data['steps'])) ? $data['steps'] : null;
	    $this->nota = (isset($data['nota'])) ? $data['nota'] : null;
	    $this->durata = (isset($data['durata'])) ? $data['durata'] : null;
	    $this->link = (isset($data['link'])) ? $data['link'] : null;
	    $this->statosessione = (isset($data['statosessione'])) ? $data['statosessione'] : null;
	    $this->stato = (isset($data['stato'])) ? $data['stato'] : null;
	    $this->actual = (isset($data['actual'])) ? $data['actual'] : null;
	    
	    $this->relatori = new Relatori();
	    $this->relatori->setId((isset($data['relatori_id'])) ? $data['relatori_id'] : null);
	    $this->relatori->setNome((isset($data['relatoriNome'])) ? $data['relatoriNome'] : null);
	    $this->relatori->setCognome((isset($data['relatoriCognome'])) ? $data['relatoriCognome'] : null);

	   
	}
	
	public function getArrayCopy() {
	    return get_object_vars($this);
	}
}


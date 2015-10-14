<?php

namespace Sessioni\Model\Entity;

class Sessioni 
{
	private $id;
	
	private $posterlab;
	
	private $relatore;
	
	private $inizio;
	
	private $fine;
	
	private $stato = false;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id) {
	    $this->id = $id;
	}
	
	
	public function getPosterlab()
	{
		return $this->posterlab;
	}
	
	public function setPosterlab($posterlab) {
	    $this->posterlab = $posterlab;
	}
	
	public function getRelatore()
	{
	    return $this->relatore;
	}
	
	public function setRelatore($relatore) {
	    $this->relatore = $relatore;
	}
	
	public function getInizio()
	{
	    return $this->inizio;
	}
	
	public function setInizio($inizio) {
	    $this->inizio = $inizio;
	}
	
	public function getFine()
	{
	    return $this->fine;
	}
	
	public function setFine($fine) {
	    $this->fine = $fine;
	}
	
    public function getStato(){
	    return $this->stato;
	}
	
	public function setStato($stato){
	    $this->stato = $stato;
	}
	
	public function exchangeArray($data) {
	    $this->id = (isset($data['id'])) ? $data['id'] : null;
	    $this->posterlab = (isset($data['posterlab_id'])) ? $data['posterlab_id'] : null;
	    $this->relatore = (isset($data['relatori_id'])) ? $data['relatori_id'] : null;
	    $this->inizio = (isset($data['data_inizio'])) ? $data['data_inizio'] : null;
	    $this->fine = (isset($data['data_fine'])) ? $data['data_fine'] : null;
	    $this->stato = (isset($data['stato'])) ? $data['stato'] : null;
	   
	}
	
	public function getArrayCopy() {
	    return get_object_vars($this);
	}
}


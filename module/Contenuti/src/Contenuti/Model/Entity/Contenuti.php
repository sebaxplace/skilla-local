<?php

namespace Contenuti\Model\Entity;

use Posterlabs\Model\Entity\Posterlabs;


class Contenuti 
{
	private $id;
	
	private $titolo;
	
	private $contenuto;
	
	private $background;
	
	private $posterlab;
	
	private $posizione;
	
	private $tipo;
	
	private $stato = false;
	
	
	
	
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
	
	
	public function getContenuto()
	{
	    return $this->contenuto;
	}
	
	public function setContenuto($contenuto) {
	    $this->contenuto = $contenuto;
	}
	
	public function getBackground()
	{
	    return $this->background;
	}
	
	public function setBackground($background) {
	    $this->background = $background;
	}
	
	public function getPosterlab()
	{
	    return $this->posterlab;
	}
	
	public function setPosterlab(Posterlabs $posterlab) {
	    $this->posterlab = $posterlab;
	}
	
	public function getPosizione()
	{
	    return $this->posizione;
	}
	
	public function setPosizione(Posterlabs $posizione) {
	    $this->posizione = $posizione;
	}
	
	public function getTipo()
	{
	    return $this->tipo;
	}
	
	public function setTipo($tipo) {
	    $this->tipo = $tipo;
	}
	
	public function getStato(){
	    return $this->stato;
	}
	
	public function setStato($stato){
	    $this->stato = $stato;
	}
	
	public function exchangeArray($data) {
	    $this->id = (isset($data['id'])) ? $data['id'] : null;
	    $this->titolo = (isset($data['titolo'])) ? $data['titolo'] : null;
	    $this->contenuto = (isset($data['contenuto'])) ? $data['contenuto'] : null;
	    $this->background = (isset($data['background'])) ? $data['background'] : null;
	    $this->posizione = (isset($data['posizione'])) ? $data['posizione'] : null;
	    $this->tipo = (isset($data['tipo'])) ? $data['tipo'] : null;
	    $this->stato = (isset($data['stato'])) ? $data['stato'] : null;
	    
	    $this->posterlab = new Posterlabs();
	    $this->posterlab->setId((isset($data['posterlab_id'])) ? $data['posterlab_id'] : null);
	    $this->posterlab->setTitolo((isset($data['posterlabTitolo'])) ? $data['posterlabTitolo'] : null);
	   
	   
	}
	
	public function getArrayCopy() {
	    return get_object_vars($this);
	}
}


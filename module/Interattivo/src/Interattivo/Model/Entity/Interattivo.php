<?php

namespace Interattivo\Model\Entity;
use Posterlabs\Model\Entity\Posterlabs;

class Interattivo 
{
	private $id;
	
	private $nome;
	
	private $messaggio;
	
	private $color;
	
	private $xyz;
	
	private $posterlab;
	
	private $tipo;
	
	private $sessione;
	
	private $data;
	
	private $stato = false;
	
	private $categoria;
	
	
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
	
	public function getMessaggio()
	{
		return $this->messaggio;
	}
	
	public function setMessaggio($messaggio) {
	    $this->messaggio = $messaggio;
	}
	
	public function getColor()
	{
	    return $this->color;
	}
	
	public function setColor($color) {
	    $this->color = $color;
	}
	
	public function getXyz()
	{
	    return $this->xyz;
	}
	
	public function setXyz($xyz) {
	    $this->xyz = $xyz;
	}
	
	public function getPosterlab()
	{
	    return $this->posterlab;
	}
	
	public function setPosterlab($posterlab) {
	    $this->posterlab = $posterlab;
	}
	
	public function getTipo()
	{
	    return $this->tipo;
	}
	
	public function setTipo($tipo) {
	    $this->tipo = $tipo;
	}
	
	public function getSessione()
	{
	    return $this->sessione;
	}
	
	public function setSessione($sessione) {
	    $this->sessione = $sessione;
	}
	
	public function getData()
	{
	    return $this->data;
	}
	
	public function setData($data) {
	    $this->data = $data;
	}
	
    public function getStato(){
	    return $this->stato;
	}
	
	public function setStato($stato){
	    $this->stato = $stato;
	}
	
	public function getCategoria(){
	    return $this->categoria;
	}
	
	public function setCategoria($categoria){
	    $this->categoria = $categoria;
	}
	
	
	
	public function exchangeArray($data) {
	    $this->id = (isset($data['id'])) ? $data['id'] : null;
	    $this->nome = (isset($data['nome'])) ? $data['nome'] : null;
	    $this->messaggio = (isset($data['messaggio'])) ? $data['messaggio'] : null;
	    $this->color = (isset($data['color'])) ? $data['color'] : null;
	    $this->xyz = (isset($data['xyz'])) ? $data['xyz'] : null;
	    
	    $this->tipo = (isset($data['tipo'])) ? $data['tipo'] : null;
	    $this->sessione = (isset($data['sessione'])) ? $data['sessione'] : null;
	    $this->data = (isset($data['data'])) ? $data['data'] : null;
	    $this->stato = (isset($data['stato'])) ? $data['stato'] : null;
	    $this->categoria = (isset($data['categoria'])) ? $data['categoria'] : null;
	   
	    
	    $this->posterlab = new Posterlabs();
	    $this->posterlab->setId((isset($data['posterlab_id'])) ? $data['posterlab_id'] : null);
	    $this->posterlab->setTitolo((isset($data['posterlabTitolo'])) ? $data['posterlabTitolo'] : null);
	    
	}
	
	public function getArrayCopy() {
	    return get_object_vars($this);
	}
}


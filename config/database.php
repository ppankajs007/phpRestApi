<?php 

class Database 
{
	private $h;
	private $u;
	private $p;
	private $d;
	private $c;

	function conn(){
		$this->h = "localhost";
		$this->u = "root";
		$this->p = "";
		$this->d = "rest_php_api";

		$this->c = new mysqli($this->h,$this->u,$this->p,$this->d);
		if ( $this->c->connect_errno ) {
			print_r( $this->c->connect_errno );
			exit;
		}else{
			return $this->c;
		}
	}
	
}
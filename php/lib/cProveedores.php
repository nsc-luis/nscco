<?php
	include_once ("cConectaBD.php");
	
	class Proveedores {
		# Declaracion de variables
		private $nombreProveedor;
		private $rfcProveedor;
		private $direccionProveedor;
		private $saldoProveedor;
		public $bd;
		private $resultado;
		private $msj;
		
		# Metodos para proveedores
		public function __construct($db){
			$this->bd = new ConectaBD($db);
			return $this->bd;
		}
		public function proveedores(){
			$sql = "SELECT * FROM proveedores ORDER BY nombreProveedor";
			return $this->bd->consulta($sql);
		}
		public function getInfoProveedor($str){
			$sql = "SELECT * FROM proveedores WHERE rfcProveedor = '$str'";
			//$datos = mysql_fetch_assoc($this->bd->consulta($sql));
			$datos = $this->bd->consulta($sql)->fetch(PDO::FETCH_ASSOC);
			$this->nombreProveedor = $datos['nombreProveedor'];
			$this->rfcProveedor = $datos['rfcProveedor'];
			$this->direccionProveedor = $datos['direccionProveedor'];
		}
		public function editaInfoProveedor($str){
			/* $sql = "UPDATE proveedores SET 
				nombreProveedor = '$this->nombreProveedor'
				,direccionProveedor = '$this->direccionProveedor'
				WHERE rfcProveedor = '$str'"; */
			$sql = "UPDATE proveedores SET 
				nombreProveedor = '$this->nombreProveedor'
				WHERE rfcProveedor = '$str'";
			return $this->bd->consulta($sql);
		}
		public function nuevoProveedor(){
			$sql = "INSERT INTO proveedores (rfcProveedor,nombreProveedor,direccionProveedor,saldoProveedor) 
				VALUES ('$this->rfcProveedor'
				,'$this->nombreProveedor'
				,'$this->direccionProveedor'
				,0.00)
			";
			return $this->bd->consulta($sql);
		}
		public function limpiaDatosCte(){
			$str = "";
			$this->nombreProveedor = $str;
			$this->direccionProveedor = $str;
		}
		
		# GET´s
		public function getRFCProveedor(){ return $this->rfcProveedor; }
		public function getNombreProveedor(){ return $this->nombreProveedor; }
		public function getDireccionProveedor(){ return $this->direccionProveedor; }
		public function getSaldoProveedor(){ return $this->saldoProveedor; }
		public function getMsj(){ return $this->msj; }
		public function getResultado(){ return $this->resultado; }
		
		# SET´s
		public function setRFCProveedor($str){ $this->rfcProveedor = $str; }
		public function setNombreProveedor($str){ $this->nombreProveedor = $str; }
		public function setDireccionProveedor($str){ $this->direccionProveedor = $str; }
		public function setSaldoProveedor($str){ $this->saldoProveedor = $str; }
	}
?>
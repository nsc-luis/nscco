<?php
	include_once ("cConectaBD.php");
	
	class MovtosPartidas {
		# Declaracion de variables
		private $idTipoDocto;
		private $folioDocto;
		private $usuario;
		private $cveProd;
		private $detalle;
		private $cantidad;
		private $precioUnitario;
		private $importePart;
		private $subTotal;
		private $idUnidad;
		public $bd;
		private $nombreBD;
		private $resultado;
		private $msj;
		
		#Metodos
		public function __construct($db){
			$this->bd = new ConectaBD($db);
			return $this->bd;
		}
		public function consulta($str){
			$data = $this->bd->consulta($sql);
			if ($data->rowCount() == 1){
				$this->msj = "Registro guardado existosamente.";
			} else {
				$this->msj = "Error al guardar registro.";
			}
			return $data;
		}
		
		public function movtosPartidasOrdServ($tDocto,$fDocto,$usr){
			$sql = "SELECT * FROM movimientos m
				JOIN productos p ON m.cveProd = p.clave
				WHERE m.idTipoDocto = '$tDocto' AND m.folioDocto = '$fDocto' AND m.usuario = '$usr'
				ORDER BY m.idUnidad";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		public function getInfoPartidaOrdServ($idMovto){
			$sql = "SELECT * FROM movimientos 
				INNER JOIN productos ON cveProd = clave
				WHERE idMovto = $idMovto";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		public function getPartidasPorOrdServ ($ord){
			$sql = "SELECT * FROM movimientos 
				INNER JOIN productos ON cveProd = clave
				WHERE folioDocto = '$ord'";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		public function edDetallePartOrdServ($idMovto){
			$importePart = $this->cantidad * $this->precioUnitario;
			$sql = "UPDATE movimientos SET detalle = '$this->detalle'
				,precioUnitario = $this->precioUnitario
				,cantidad = $this->cantidad
				,importePart = $importePart
				WHERE idMovto = $idMovto";
			$this->bd->consulta($sql);
		}

		public function borrarPartOrdServ($idMovto){
			$sql = "DELETE FROM movimientos	
				WHERE idMovto = $idMovto";
			$this->bd->consulta($sql);
		}
		
		public function nvaPartidaOrdServ(){
			$importePart = $this->cantidad * $this->precioUnitario;
			$sql = "INSERT INTO movimientos (idTipoDocto,folioDocto,usuario
				,cveProd,detalle,cantidad,precioUnitario,importePart,idUnidad) 
				VALUES ('$this->idTipoDocto'
				,'$this->folioDocto'
				,'$this->usuario'
				,'$this->cveProd'
				,'$this->detalle'
				,'$this->cantidad'
				,'$this->precioUnitario'
				,'$importePart'
				,'$this->idUnidad')";
			$this->bd->consulta($sql);
		}
		
		# GET´s
		public function getIdTipoDocto(){ return $this->idTipoDocto; }
		public function getFolioDocto(){ return $this->folioDocto; }
		public function getUsuario(){ return $this->usuario; }
		public function getCveProd(){ return $this->cveProd; }
		public function getDetalle(){ return $this->detalle; }
		public function getCantidad(){ return $this->cantidad; }
		public function getPrecioUnitario(){ return $this->precioUnitario; }
		public function getImportePart(){ return $this->importePart; }
		public function getMsj(){ return $this->msj; }
		public function getResultado(){ return $this->resultado; }
		
		# SET´s
		public function setIdTipoDocto($str){ $this->idTipoDocto = $str; }
		public function setFolioDocto($str){ $this->folioDocto = $str; }
		public function setIdUnidad($str){ $this->idUnidad = $str; }
		public function setUsuario($str){ $this->usuario = $str; }
		public function setCveProd($str){ $this->cveProd = $str; }
		public function setDetalle($str){ $this->detalle = $str; }
		public function setCantidad($str){ $this->cantidad = $str; }
		public function setPrecioUnitario($str){ return $this->precioUnitario = $str; }
		public function setImportePart($str){ return $this->importePart = $str; }
	}
?>
<?php
	include_once ("cConectaBD.php");
	error_reporting(E_ALL);
	ini_set("display_errors", 0);
	
	class Dispositivos {
		# Declaracion de variables
		private $idCliente;
		private $idSucursalCte;
		private $idTipoDisp;
		private $nombre;
		private $marca;
		private $modelo;
		private $serie;
		private $parte;
		private $observaciones;
		private $fechaRegistro;
		public $bd;
		private $resultado;
		private $msj;
		
		#Metodos
		public function __construct($db){
			$this->bd = new ConectaBD($db);
			return $this->bd;
		}
		public function listaPerifericos($idCte){
			$sql = "SELECT d.idDispositivo, d.idCliente, d.idSucursalCte, d.idTipoDisp, d.nombre, d.marca, d.modelo
				,d.serie, d.parte, d.observaciones, d.fechaRegistro, td.nombre nombreTipoDisp, s.nombre nombreOfna
				FROM dispositivos d
				JOIN tipodedispositivo td ON td.idTipoDisp = d.idTipoDisp
				JOIN sucursalCte s ON s.idSucursalCte = d.idSucursalCte
				WHERE d.idCliente = $idCte
				ORDER BY d.idSucursalCte, d.idTipoDisp DESC";
			//echo $sql;
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		public function guardarNvoPeriferico($idCte){
			$sql = "SELECT * FROM dispositivos WHERE serie = '$this->serie' AND idCliente = $idCte";
			$response = $this->bd->consulta($sql);
			if ($response->rowCount() > 0) {
				$this->msj = "El nombre de dispositivo o la serie ya existe, revise los datos ingresados.";
			} else {
				$sql = "INSERT INTO dispositivos (idCliente,idSucursalCte,idTipoDisp,nombre,
				marca,modelo,serie,parte,observaciones,fechaRegistro) 
				VALUES ('$idCte','$this->idSucursalCte','$this->idTipoDisp','$this->nombre','$this->marca','$this->modelo'
				,'$this->serie','$this->parte','$this->observaciones','$this->fechaRegistro')";
				echo $sql;
				$this->bd->consulta($sql);
			}
		}
		
		public function guardarPeriferico($idDisp){
			$sql = "UPDATE dispositivos SET idSucursalCte = '$this->idSucursalCte', idTipoDisp = '$this->idTipoDisp'
				,nombre = '$this->nombre', marca = '$this->marca', modelo = '$this->modelo', serie = '$this->serie'
				,parte = '$this->parte', observaciones = '$this->observaciones', fechaRegistro = '$this->fechaRegistro'
				WHERE idDispositivo = $idDisp";
			$this->bd->consulta($sql);
		}
		
		public function borrarPeriferico($idDisp) {
			$sql = "DELETE FROM dispositivos WHERE idDispositivo = $idDisp";
			$this->bd->consulta($sql);
		}
		
		# GET´s
		public function getMsj(){ return $this->msj; }
		
		# SET´s
		public function setIdCliente($str){ $this->idCliente = $str; }
		public function setIdSucursalCte($str){ $this->idSucursalCte = $str; }
		public function setIdTipoDisp($str){ $this->idTipoDisp = $str; }
		public function setNombre($str){ $this->nombre = $str; }
		public function setMarca($str){ $this->marca = $str; }
		public function setModelo($str){ $this->modelo = $str; }
		public function setSerie($str){ $this->serie = $str; }
		public function setParte($str){ $this->parte = $str; }
		public function setObservaciones($str){ $this->observaciones = $str; }
		public function setFechaRegistro($str){ $this->fechaRegistro = $str; }
		public function setMsj($str){ $this->msj = $str; }
	}
?>
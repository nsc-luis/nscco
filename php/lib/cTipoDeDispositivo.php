<?php	
	include_once ("cConectaBD.php");
	
	class TipoDeDispositivo
	{
		#Declaracion de variables
		private $idTipoDisp;
		private $nombre;
		public $bd;
		private $resultado;
		private $msj;
		
		#Metodos de la clase
		public function __construct($bd){
			$this->bd = new ConectaBD($bd);
			return $this->bd;
		}
		function listaTipoDispositivo() //Obtener lista de los tipos de dispositivo
		{
		$sql = "SELECT * FROM tipodedispositivo WHERE idTipoDisp != 1";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		public function guardarNvoTipoDisp() // Consulta existencia y agrega un nuevo tipo de dispositivo
		{
			$sql = "SELECT * FROM tipodedispositivo WHERE nombre = '$this->nombre'";
			$contador = $this->bd->consulta($sql)->rowCount();
			
			if( $contador > 0 ) {
				$this->msj = "Ya existe un tipo de dispositivo con este nombre";
				return false;
			} else {
				$sql = "INSERT INTO tipodedispositivo (nombre) VALUES ('$this->nombre')";
				$this->bd->consulta($sql);
			}
		}
		
		public function guardarTipoDisp($id) // Consulta existencia y actualiza un tipo de dispositivo
		{
			$sql = "SELECT * FROM tipodedispositivo WHERE nombre = '$this->nombre'";
			$contador = $this->bd->consulta($sql)->rowCount();
			
			if( $contador > 0 ) {
				$this->msj = "Ya existe un tipo de dispositivo con este nombre";
				return false;
			} else {
				$sql = "UPDATE tipodedispositivo SET nombre = '$this->nombre' WHERE idTipoDisp = $id";
				$this->bd->consulta($sql);
			}
		}
		
		public function borrarTipoDisp($id) // Borra un tipo de dispositivo si no tiene movimientos
		{
			$sql = "SELECT * FROM dispositivos WHERE idTipoDisp = $id";
			$contador = $this->bd->consulta($sql)->rowCount();
			
			if( $contador > 0 ) {
				$this->msj = "Solo se pueden borrar tipos de dispositivos que NO tengan movimientos.";
				return false;
			} else {
				$sql = "DELETE FROM tipodedispositivo WHERE idTipoDisp = $id";
				$this->bd->consulta($sql);
			}
		}
		
		#Metodos GETs
		public function getMsj(){ return $this->msj; }
		
		#Metodos SETs
		public function setNombre($str){ $this->nombre = $str; }
	}
?>

<?php	
	include_once ("cConectaBD.php");
	
	class TipoDeLicencia
	{
		#Declaracion de variables
		private $idtipolicencia;
		private $nombre;
		public $bd;
		private $resultado;
		private $msj;
		
		#Metodos de la clase
		public function __construct($bd){
			$this->bd = new ConectaBD($bd);
			return $this->bd;
		}
		function listaTipoLicencia() //Obtener lista de los tipos de licencias
		{
			$sql = "SELECT * FROM tipodelicencia";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		public function guardarNvoTipoLic() //Muesta todos los tipos de servicio
		{
			$sql = "SELECT * FROM tipodelicencia WHERE nombre = '$this->nombre'";
			$contador = $this->bd->consulta($sql)->rowCount();
			
			if( $contador > 0 ) {
				$this->msj = "Ya existe un tipo de licencia con este nombre";
				return false;
			} else {
				$sql = "INSERT INTO tipodelicencia (nombre) VALUES ('$this->nombre')";
				$this->bd->consulta($sql);
			}
		}
		
		public function guardarTipoLic($id) //Muesta todos los tipos de servicio
		{
			$sql = "SELECT * FROM tipodelicencia WHERE nombre = '$this->nombre'";
			$contador = $this->bd->consulta($sql)->rowCount();
			
			if( $contador > 0 ) {
				$this->msj = "Ya existe un tipo de licencia con este nombre";
				return false;
			} else {
				$sql = "UPDATE tipodelicencia SET nombre = '$this->nombre' WHERE idtipolicencia = $id";
				$this->bd->consulta($sql);
			}
		}
		
		public function borrarTipoLic($id) //Muesta todos los tipos de servicio
		{
			$sql = "SELECT * FROM licenciasdesoftware WHERE idtipolicencia = $id";
			$contador = $this->bd->consulta($sql)->rowCount();
			
			if( $contador > 0 ) {
				$this->msj = "Solo se pueden borrar tipos de licencia que NO tengan movimientos.";
				return false;
			} else {
				$sql = "DELETE FROM tipodelicencia WHERE idtipolicencia = $id";
				$this->bd->consulta($sql);
			}
		}
		
		/*#Metodos GETs
		public function getIdTipoDeServ(){ return $this->idTipoDeServ; }
		public function getTipoDeServ(){ return $this->tipoDeServ; }
		public function getResultado(){ return $this->resultado; } */
		public function getMsj(){ return $this->msj; }
		
		#Metodos SETs
		public function setNombre($str){ $this->nombre = $str; }
	}
?>

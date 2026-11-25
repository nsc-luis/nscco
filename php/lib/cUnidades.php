<?php	
	include_once ("cConectaBD.php");
	
	class Unidades
	{
		#Declaracion de variables
		private $idUnidad;
		private $medida;
		private $resultado;
		private $msj = "";
		
		#Metodos de la clase Unidades
		public function __construct(){
			$this->bd = new ConectaBD();
			return $this->bd;
		}
		function getInfoUni($str) //Obtener datos de la medida segun ID
		{
			$sql = "SELECT * FROM unidades 
				WHERE idUnidad = '$str'";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		public function unidades() //Muesta todos las unidades
		{
			$sql = "SELECT * FROM unidades ORDER BY idUnidad";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		function nuevaUni() //Inserta un nuevo registro en tabla unidades
		{
			$sql = "SELECT * FROM unidades WHERE medida = '$this->medida'";
			$contador = mysql_num_rows($this->bd->consulta($sql));
			if ($contador > 0) {
				$this->msj = "Ya existe una unidad de medida con este nombre, intenta otro.";
			} else {
				$sql = "INSERT INTO unidades (medida) 
					VALUES ('$this->medida')";
				echo $sql;
				//$this->bd->consulta($sql);
			}
		}
		function editarUni($str) //Actualiza un registro en tabla unidades
		{
			$sql = "SELECT * FROM unidades WHERE medida = '$this->medida'";
			$contador = mysql_num_rows($this->bd->consulta($sql));
			if ($contador > 0) {
				$this->msj = "Ya existe una unidad de medida con este nombre, intenta otro.";
			} else {
				$sql = "UPDATE unidades SET medida = '$this->medida'
					WHERE idUnidad = '$str'";
				$this->bd->consulta($sql);
			}
		}
		function borraUnidad($str) //Borra un registro en tabla unidades
		{
			$sql = "SELECT * FROM movimientos WHERE idUnidad = '$str'";
			$contador = mysql_num_rows($this->bd->consulta($sql));
			if ($contador > 0 ) {
				$this->msj = "La unidad tiene movimientos y no puede ser eliminada.";
			} else {
				$sql = "DELETE FROM unidades WHERE idUnidad = '$str'";
				$this->bd->consulta($sql);
			}
		}
		
		#Metodos GETs
		public function getIdUnidad(){ return $this->idUnidad; }
		public function getMedida(){ return $this->medida; }
		public function getResultado(){ return $this->resultado; }
		public function getMsj(){ return $this->msj; }
		
		#Metodos SETs
		public function setIdUnidad($str){ $this->idUnidad = $str; }
		public function setMedida($str){ $this->medida = $str; }
	}
?>

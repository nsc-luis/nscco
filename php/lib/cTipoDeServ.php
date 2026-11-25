<?php	
	include_once ("cConectaBD.php");
	
	class TipoDeServicio
	{
		#Declaracion de variables
		private $idTipoDeServ;
		private $tipoDeServ;
		public $bd;
		private $resultado;
		private $msj;
		
		#Metodos de la clase
		public function __construct($bd){
			$this->bd = new ConectaBD($bd);
			return $this->bd;
		}
		function getInfoTipoDeServ($str) //Obtener datos del servicio segun ID
		{
			$sql = "SELECT * FROM tipodeservicio
				WHERE idTipoDeServ = '$str'";
			$data = $this->bd->consulta($sql)->fetch(PDO::FETCH_ARRAY);
			$this->idTipoDeServ = $data['idTipoDeServ'];
			$this->tipoDeServ = $data['tipoDeServ'];
		}
		public function tiposDeServ() //Muesta todos los tipos de servicio
		{
			$sql = "SELECT * FROM tipodeservicio";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		#Metodos GETs
		public function getIdTipoDeServ(){ return $this->idTipoDeServ; }
		public function getTipoDeServ(){ return $this->tipoDeServ; }
		public function getResultado(){ return $this->resultado; }
		public function getMsj(){ return $this->msj; }
		
		#Metodos SETs
		public function setIdTipoDeServ($str){ $this->idTipoDeServ = $str; }
		public function setTipoDeServ($str){ $this->tipoDeServ = $str; }
	}
?>

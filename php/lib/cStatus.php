<?php	
	include_once ("cConectaBD.php");
	
	class Status
	{
		#Declaracion de variables
		private $idStatus;
		private $status;
		public $bd;
		private $resultado;
		private $msj;
		
		#Metodos de la clase
		public function __construct($bd){
			$this->bd = new ConectaBD($bd);
			return $this->bd;
		}
		function getInfoStatus($str)
		{
			$sql = "SELECT * FROM status
				WHERE idStatus = '$str'";
			$data = $this->bd->consulta($sql)->fetch();
			$this->idStatus = $data['idStatus'];
			$this->status = $data['status'];
		}
		public function tiposStatus()
		{
			$sql = "SELECT * FROM status";
			$data = $this->bd->consulta($sql);
			return $data;
		}

		#Metodos GETs
		public function getIdStatus(){ return $this->idStatus; }
		public function getStatus(){ return $this->status; }
		public function getResultado(){ return $this->resultado; }
		public function getMsj(){ return $this->msj; }
		
		#Metodos SETs
		public function setIdStatus($str){ $this->idStatus = $str; }
		public function setStatus($str){ $this->status = $str; }
	}
?>

<?php
	include_once ("cConectaBD.php");
	
	class RespaldoCtes {
		# Declaracion de variables
		private $nombreCte;
		public $bd;
		private $nombreBD;
		private $resultado;
		private $msj;
		
		#Metodos
		public function __construct($bd){
			$this->bd = new ConectaBD($bd);
			return $this->bd;
		}
		public function listaCtesRespaldos(){
			$sql = "SELECT DISTINCT(nombreCte) FROM bitacoraderespaldos ORDER BY nombreCte ASC";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		public function bitRespCtes($nomCte) {
			$sql = "SELECT idResp,tipoDeResp,nombreCte,nombreResp,origen
				,destino,destinoRemoto,fechaInicialResp,fechaFinalResp,detalleResp,
				(select timediff(fechaFinalResp,fechaInicialResp)) tiempoTranscurrido 
				FROM bitacoraderespaldos 
				WHERE nombreCte = '$nomCte' ORDER BY fechaInicialResp DESC";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		// Metodo para obtener los ultimos 3 respaldos por cliente
		public function ultimos3Respaldos() {
			//$fechaActual = date('Y-m-d');
			//$fechaInicial = $fechaActual->modify('-90 days');
			$sql = "SELECT distinct(nombreCte) FROM bitacoraderespaldos 
				where fechaInicialResp between '2019-01-23' and '2019-07-23'
				ORDER BY idResp desc";
			$query = $this->bd->consulta($sql);
			$data = array();
			echo $sql;
			
			if ($query->rowCount() !=0) {
				while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					$data['empresas'][] = $row;
				}
			}
			
			for ($i = 0; $i < count($data['empresas']); $i++) {
				$sql = "select distinct(nombreResp) from bitacoraderespaldos
					where nombreCte = '" . $data['empresas'][$i]['nombreCte'] . "'
					order by idResp desc";
				$query = $this->bd->consulta($sql);
				$data['empresas'][$i]['respaldos'] = array();
				while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					$data['empresas'][$i]['respaldos'][] = $row;
				}
				for ($j = 0; $j < count($data['empresas'][$i]['respaldos']); $j++) {
					$data['empresas'][$i]['respaldos'][$j]['registros'] = array();
					$sql2 = "select idResp,tipoDeResp,nombreCte,nombreResp,origen,destino,destinoRemoto,fechaInicialResp,fechaFinalResp,detalleResp
						from bitacoraderespaldos
						where nombreResp = '" . $data['empresas'][$i]['respaldos'][$j]['nombreResp'] . "'
						order by idResp desc
						limit 1,3";
					$query2 = $this->bd->consulta($sql2);
					while ($row2 = $query2->fetch(PDO::FETCH_ASSOC)) {
						$data['empresas'][$i]['respaldos'][$j]['registros'][] = $row2;
					}
				}
			}
			
			return $data;
		}
		
		# GET´s
		public function getNombreCte(){ return $this->nombreCte; }
		public function getMsj(){ return $this->msj; }
		public function getResultado(){ return $this->resultado; }
		
		# SET´s
		public function setNombreCte($str){ $this->nombreCte = $str; }
		public function setMsj($str){ $this->msj = $str; }
	}
?>
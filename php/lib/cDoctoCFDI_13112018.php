<?php
	include_once ("cConectaBD.php");
	
	class DoctoCFDI {
		# Declaracion de variables
		private $idXML;
		private $rfcXML;
		private $razonSocialXML;
		private $folioXML;
		private $fechaXML;
		private $uuid;
		private $subTotalXML;
		private $impuestoXML;
		private $totalXML;
		private $idTipoDocto;
		private $archivoXML;
		private $sizeArchivoXML;
		private $typeArchivoXML;
		public $bd;
		/* private $resultado; */
		private $msj;
		
		# Metodos para la clase DoctoCFDI
		public function __construct(){
			$this->bd = new ConectaBD();
			return $this->bd;
		}
		public function yearsList(){
			$sql = "SELECT DISTINCT(YEAR(STR_TO_DATE(fechaXML, '%Y-%m-%d'))) year FROM xmls ORDER BY idXML DESC";
			return $this->bd->consulta($sql);
		}
		public function cfdis($rfcProvFiltro,$fecha,/*$fUUID,*/$fTipoDocto) {
			$condicion = "WHERE fechaXML LIKE '$fecha%'";
			if ($rfcProvFiltro != "0") { $condicion .= " AND rfcXML = '$rfcProvFiltro'"; }
			if ($fTipoDocto != "0") { $condicion .= " AND xmls.idTipoDocto = '$fTipoDocto'"; }
			// if ($fUUID != "") { $condicion = "WHERE uuid = '$fUUID'"; }
			$sql = "SELECT idXML,folioXML,razonSocialXML,fechaXML,totalXML,uuid,subTotalXML,impuestoXML,nombre FROM xmls 
				INNER JOIN tipodocto ON xmls.idTipoDocto = tipodocto.idTipoDocto 
				$condicion 
				ORDER BY fechaXML DESC";
			return $this->bd->consulta($sql);
		}
		public function cfdiTotalGeneral($rfcProvFiltro,$fecha,$fTipoDocto) {
			$condicion = "WHERE fechaXML LIKE '$fecha%'";
			if ($rfcProvFiltro != "0") { $condicion .= " AND rfcXML = '$rfcProvFiltro'"; }
			if ($fTipoDocto != "0") { $condicion .= " AND xmls.idTipoDocto = '$fTipoDocto'"; }
			$sql = "SELECT SUM(subTotalXML) as subTotalGral, SUM(impuestoXML) as impuestoGral, SUM(totalXML) as totalGral 
				FROM xmls INNER JOIN tipodocto ON xmls.idTipoDocto = tipodocto.idTipoDocto 
				$condicion 
				ORDER BY fechaXML DESC";
			return $this->bd->consulta($sql);
		}
		public function cfdiTotalMensual($rfcProvFiltro,$mes,$fTipoDocto) {
			$condicion = "";
			if ($rfcProvFiltro != "0") { $condicion .= "AND rfcXML = '$rfcProvFiltro'"; }
			if ($fTipoDocto != "0") { $condicion .= "AND xmls.idTipoDocto = '$fTipoDocto'"; }
			$sql = "SELECT SUM(subTotalXML) as subTotalMes, SUM(impuestoXML) as impuestoMes, SUM(totalXML) as totalMes 
				FROM xmls INNER JOIN tipodocto ON xmls.idTipoDocto = tipodocto.idTipoDocto 
				WHERE fechaXML LIKE '$mes%' $condicion 
				ORDER BY fechaXML DESC";
			return $this->bd->consulta($sql);
		}
		public function getInfoXML($uuid) {
			$sql = "SELECT * FROM xmls WHERE uuid = '$uuid'";
			return $this->bd->consulta($sql);
		}
		public function nuevoXML($nvoUUID){
			$sql = "SELECT * FROM xmls WHERE uuid = '$nvoUUID'";
			$query = $this->bd->consulta($sql);
			$contador = mysql_num_rows($query);
			if ( $contador == 0 ) {
				$sql = "INSERT INTO xmls (rfcXML
				,razonSocialXML
				,folioXML
				,fechaXML
				,uuid
				,subTotalXML
				,impuestoXML
				,totalXML
				,idTipoDocto
				,archivoXML
				,sizeArchivoXML
				,typeArchivoXML) 
				VALUES (
				'$this->rfcXML'
				,'$this->razonSocialXML'
				,'$this->folioXML'
				,'$this->fechaXML'
				,'$this->uuid'
				,'$this->subTotalXML'
				,'$this->impuestoXML'
				,'$this->totalXML'
				,'$this->idTipoDocto'
				,'$this->archivoXML'
				,'$this->sizeArchivoXML'
				,'$this->typeArchivoXML'
				)";
				return $this->bd->consulta($sql);
			}
		}
		public function borrarXML($uuid) {
			$sql = "DELETE FROM xmls WHERE uuid = '$uuid'";
			return $this->bd->consulta($sql);
		}
		
		# GET's
		public function getIdXML(){ return $this->idXML; }
		public function getRfcXML(){ return $this->rfcXML; }
		public function getRazonSocialXML(){ return $this->razonSocialXML; }
		public function getFolioXML(){ return $this->folioXML; }
		public function getFechaXML(){ return $this->fechaXML; }
		public function getUuid(){ return $this->uuid; }
		public function getSubTotalXML(){ return $this->subTotalXML; }
		public function getImpuestoXML(){ return $this->impuestoXML; }
		public function getTotalXML(){ return $this->totalXML; }
		public function getIdTipoDocto(){ return $this->idTipoDocto; }
		public function getArchivoXML(){ return $this->archivoXML; }
		public function getSizeArchivoXML(){ return $this->sizeArchivoXML; }
		public function getTypeArchivoXML(){ return $this->typeArchivoXML; }
		public function getMsj(){ return $this->msj; }
		/* public function getResultado(){ return $this->resultado; } */
		
		# SET´s
		public function setIdXML($str){ $this->idXML = $str; }
		public function setRfcXML($str){ $this->rfcXML = $str; }
		public function setRazonSocialXML($str){ $this->razonSocialXML = $str; }
		public function setFolioXML($str){ $this->folioXML = $str; }
		public function setFechaXML($str){ $this->fechaXML = $str; }
		public function setUuid($str){ $this->uuid = $str; }
		public function setSubTotalXML($str){ $this->subTotalXML = $str; }
		public function setImpuestoXML($str){ $this->impuestoXML = $str; }
		public function setTotalXML($str){ $this->totalXML = $str; }
		public function setIdTipoDocto($str){ $this->idTipoDocto = $str; }
		public function setArchivoXML($str){ $this->archivoXML = $str; }
		public function setSizeArchivoXML($str){ $this->sizeArchivoXML = $str; }
		public function setTypeArChivoXML($str){ $this->typeArchivoXML = $str; }
	}
?>
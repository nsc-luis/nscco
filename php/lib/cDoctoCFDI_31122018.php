<?php
	include_once ("cConectaBD.php");
	
	class DoctoCFDI {
		# Declaracion de variables
		private $idXML;
		private $rfcEmisor;
		private $nombreEmisor;
		private $rfcReceptor;
		private $nombreReceptor;
		private $folioXML;
		private $fechaXML;
		private $uuid;
		private $subTotalXML;
		private $impuestoXML;
		private $totalXML;
		private $idTipoDocto;
		private $tipoDeDoctoXML;
		private $archivoXML;
		private $sizeArchivoXML;
		private $typeArchivoXML;
		private $versionXML;
		private $EmitidoRecibido;
		private $c_RegimenFiscal = array(
			"601"=>"General de Ley Personas Morales",
			"603"=>"Personas Morales con Fines no Lucrativos",
			"605"=>"Sueldos y Salarios e Ingresos Asimilados a Salarios",
			"606"=>"Arrendamiento",
			"608"=>"Demás ingresos",
			"609"=>"Consolidación",
			"610"=>"Residentes en el Extranjero sin Establecimiento Permanente en México",
			"611"=>"Ingresos por Dividendos (socios y accionistas)",
			"612"=>"Personas Físicas con Actividades Empresariales y Profesionales",
			"614"=>"Ingresos por intereses",
			"616"=>"Sin obligaciones fiscales",
			"620"=>"Sociedades Cooperativas de Producción que optan por diferir sus ingresos",
			"621"=>"Incorporación Fiscal",
			"622"=>"Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras",
			"623"=>"Opcional para Grupos de Sociedades",
			"624"=>"Coordinados",
			"628"=>"Hidrocarburos",
			"607"=>"Régimen de Enajenación o Adquisición de Bienes",
			"629"=>"De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales",
			"630"=>"Enajenación de acciones en bolsa de valores",
			"615"=>"Régimen de los ingresos por obtención de premios"
		);
		private $c_Impuesto = array(
			"001"=>"ISR",
			"002"=>"IVA",
			"003"=>"IEPS"
		);
		private $c_FormaPago = array(
			"01"=>"Efectivo",
			"02"=>"Cheque nominativo",
			"03"=>"Transferencia electrónica de fondos",
			"04"=>"Tarjeta de crédito",
			"05"=>"Monedero electrónico",
			"06"=>"Dinero electrónico",
			"08"=>"Vales de despensa",
			"12"=>"Dación en pago",
			"13"=>"Pago por subrogación",
			"14"=>"Pago por consignación",
			"15"=>"Condonación",
			"17"=>"Compensación",
			"23"=>"Novación",
			"24"=>"Confusión",
			"25"=>"Remisión de deuda",
			"26"=>"Prescripción o caducidad",
			"27"=>"A satisfacción del acreedor",
			"28"=>"Tarjeta de débito",
			"29"=>"Tarjeta de servicios",
			"30"=>"Aplicación de anticipos",
			"31"=>"Intermediario pagos",
			"99"=>"Por definir"
		);
		private $c_MetodoPago = array(
			"PUE"=>"Pago en una sola exhibición",
			"PPD"=>"Pago en parcialidades o diferido"
		);

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
			if ($rfcProvFiltro != "0") { $condicion .= " AND rfcEmisor = '$rfcProvFiltro'"; }
			if ($fTipoDocto != "0") { $condicion .= " AND xmls.idTipoDocto = '$fTipoDocto'"; }
			// if ($fUUID != "") { $condicion = "WHERE uuid = '$fUUID'"; }
			/* $sql = "SELECT idXML,folioXML,rfcEmisor,nombreEmisor,rfcReceptor,nombreReceptor,fechaXML,totalXML,tipoDeDoctoXML,uuid,versionXML FROM xmls 
				INNER JOIN tipodocto ON xmls.idTipoDocto = tipodocto.idTipoDocto 
				$condicion 
				ORDER BY fechaXML DESC"; */
			$sql = "SELECT idXML,folioXML,rfcEmisor,nombreEmisor,rfcReceptor,nombreReceptor,fechaXML,totalXML,tipoDeDoctoXML,uuid,versionXML FROM xmls 
				$condicion 
				ORDER BY fechaXML DESC";
			return $this->bd->consulta($sql);
		}
		public function cfdiTotalGeneral($rfcProvFiltro,$fecha,$fTipoDocto) {
			$condicion = "WHERE fechaXML LIKE '$fecha%'";
			if ($rfcProvFiltro != "0") { $condicion .= " AND rfcEmisor = '$rfcProvFiltro'"; }
			if ($fTipoDocto != "0") { $condicion .= " AND xmls.idTipoDocto = '$fTipoDocto'"; }
			$sql = "SELECT SUM(subTotalXML) as subTotalGral, SUM(impuestoXML) as impuestoGral, SUM(totalXML) as totalGral 
				FROM xmls INNER JOIN tipodocto ON xmls.idTipoDocto = tipodocto.idTipoDocto 
				$condicion 
				ORDER BY fechaXML DESC";
			return $this->bd->consulta($sql);
		}
		public function cfdiTotalMensual($rfcProvFiltro,$mes,$fTipoDocto) {
			$condicion = "";
			if ($rfcProvFiltro != "0") { $condicion .= "AND rfcEmisor = '$rfcProvFiltro'"; }
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
				$sql = "INSERT INTO xmls (
				rfcEmisor
				,nombreEmisor
				,rfcReceptor
				,nombreReceptor
				,folioXML
				,fechaXML
				,uuid
				,subTotalXML
				,impuestoXML
				,totalXML
				,idTipoDocto
				,tipoDeDoctoXML
				,archivoXML
				,sizeArchivoXML
				,typeArchivoXML
				,versionXML
				,EmitidoRecibido) 
				VALUES ('$this->rfcEmisor'
				,'$this->nombreEmisor'
				,'$this->rfcReceptor'
				,'$this->nombreReceptor'
				,'$this->folioXML'
				,'$this->fechaXML'
				,'$this->uuid'
				,'$this->subTotalXML'
				,'$this->impuestoXML'
				,'$this->totalXML'
				,'$this->idTipoDocto'
				,'$this->tipoDeDoctoXML'
				,'$this->archivoXML'
				,'$this->sizeArchivoXML'
				,'$this->typeArchivoXML'
				,'$this->versionXML'
				,'$this->EmitidoRecibido'
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
		public function getRfcEmisor(){ return $this->rfcEmisor; }
		public function getNombreEmisor(){ return $this->nombreEmisor; }
		public function getRfcReceptor(){ return $this->rfcReceptor; }
		public function getNombreReceptor(){ return $this->nombreReceptor; }
		public function getFolioXML(){ return $this->folioXML; }
		public function getFechaXML(){ return $this->fechaXML; }
		public function getUuid(){ return $this->uuid; }
		public function getSubTotalXML(){ return $this->subTotalXML; }
		public function getImpuestoXML(){ return $this->impuestoXML; }
		public function getTotalXML(){ return $this->totalXML; }
		public function getIdTipoDocto(){ return $this->idTipoDocto; }
		public function getTipoDeDoctoXML(){ return $this->tipoDeDoctoXML; }
		public function getArchivoXML(){ return $this->archivoXML; }
		public function getSizeArchivoXML(){ return $this->sizeArchivoXML; }
		public function getTypeArchivoXML(){ return $this->typeArchivoXML; }
		public function getVersionXML(){ return $this->versionXML; }
		public function getEmitidoRecibido(){ return $this->EmitidoRecibido; }
		public function getC_RegimenFiscal($str){ return $this->c_RegimenFiscal[$str]; }
		public function getC_MetodoPago($str){ return $this->c_MetodoPago[$str]; }
		public function getC_FormaPago($str){ return $this->c_FormaPago[$str]; }
		public function getC_Impuesto($str){ return $this->c_Impuesto[$str]; }
		public function getMsj(){ return $this->msj; }
		/* public function getResultado(){ return $this->resultado; } */
		
		# SET´s
		public function setIdXML($str){ $this->idXML = $str; }
		public function setRfcEmisor($str){ $this->rfcEmisor = $str; }
		public function setNombreEmisor($str){ $this->nombreEmisor = $str; }
		public function setRfcReceptor($str){ $this->rfcReceptor = $str; }
		public function setNombreReceptor($str){ $this->nombreReceptor = $str; }
		public function setFolioXML($str){ $this->folioXML = $str; }
		public function setFechaXML($str){ $this->fechaXML = $str; }
		public function setUuid($str){ $this->uuid = $str; }
		public function setSubTotalXML($str){ $this->subTotalXML = $str; }
		public function setImpuestoXML($str){ $this->impuestoXML = $str; }
		public function setTotalXML($str){ $this->totalXML = $str; }
		public function setIdTipoDocto($str){ $this->idTipoDocto = $str; }
		public function setTipoDeDoctoXML($str){ $this->tipoDeDoctoXML = $str; }
		public function setArchivoXML($str){ $this->archivoXML = $str; }
		public function setSizeArchivoXML($str){ $this->sizeArchivoXML = $str; }
		public function setTypeArChivoXML($str){ $this->typeArchivoXML = $str; }
		public function setVersionXML($str){ $this->versionXML = $str; }
		public function setEmitidoRecibido($str){ $this->EmitidoRecibido = $str; }
	}
?>
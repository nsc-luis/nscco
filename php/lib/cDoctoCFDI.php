<?php
	include_once ("cConectaBD.php");
	//session_start();
	class DoctoCFDI {
		# Declaracion de variables
		private $idXML;
		private $rfcEmisor;
		private $nombreEmisor;
		private $emisorRegimenFiscal;
		private $rfcReceptor;
		private $nombreReceptor;
		private $regimenFiscalReceptor;
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
		private $idUsr;
		private $idOficina;
		private $poliza;
		private $ordenDeCompra;
		private $archivoPDF;
		private $mimeArchivoPDF;
		private $sizeArchivoPDF;
		private $codigoValidaCFDI;
		private $estadoValidaCFDI;
		private $fechaPago;

		public $bd;
		private $msj;
		
		# Metodos para la clase DoctoCFDI
		public function __construct($db){
			$this->bd = new ConectaBD($db);
			return $this->bd;
		}
		public function yearsList(){
			//$sql = "SELECT DISTINCT(YEAR(STR_TO_DATE(fechaXML, '%Y-%m-%d'))) year FROM xmls ORDER BY idXML DESC";
			$sql = "SELECT DISTINCT(YEAR(fechaXML)) year FROM xmls ORDER BY year DESC";
			// return $this->bd->consulta($sql);
			return $this->bd->consulta($sql);
		}
		//public function cfdis($rfcProvFiltro,$fecha,/*$fUUID,*/$fTipoDocto) {
		public function cfdis($params) {
			// $esAdmin = $params['nivelUsr'] != 1 ? "AND x.idUsr = $params[idUsr]" : "";
			switch ($params['filtro']) {
				case "default":
					$condicion = "WHERE fechaXML LIKE '$params[fecha]%'";
				break;
				case "f":
					$condicion = "WHERE fechaXML BETWEEN '$params[fechaInicial]' AND '$params[fechaFinal]'";
				break;
				case "pf":
				if ($params[rfcEmisor] == '0') {
					$condicion = "WHERE fechaXML BETWEEN '$params[fechaInicial]' AND '$params[fechaFinal]'
						AND EmitidoRecibido = 'recibido'
					";
				} else {
					$condicion = "WHERE rfcEmisor = '$params[rfcEmisor]'
						AND fechaXML BETWEEN '$params[fechaInicial]'
						AND '$params[fechaFinal]'
						AND EmitidoRecibido = 'recibido'
					";
				}
				break;
				case "oc":
					$condicion = "WHERE ordenDeCompra = '$params[ordenDeCompra]'";
				break;
				case "pol":
					$condicion = "WHERE poliza = '$params[poliza]'";
				break;
				case "fp":
					$condicion = "WHERE fechaPago BETWEEN '$params[fechaInicial]' AND '$params[fechaFinal]'";
				break;
				case "uuid":
					$condicion = "WHERE uuid = '$params[uuid]'";
				break;
				case "I":
					$condicion = "WHERE tipoDeDoctoXML = 'I' AND fechaXML BETWEEN '$params[fechaInicial]' AND '$params[fechaFinal]'";
				break;
				case "E":
					$condicion = "WHERE tipoDeDoctoXML = 'E' AND fechaXML BETWEEN '$params[fechaInicial]' AND '$params[fechaFinal]'";
				break;
				case "N":
					$condicion = "WHERE tipoDeDoctoXML = 'N' AND fechaXML BETWEEN '$params[fechaInicial]' AND '$params[fechaFinal]'";
				break;
				case "P":
					$condicion = "WHERE tipoDeDoctoXML = 'P' AND fechaXML BETWEEN '$params[fechaInicial]' AND '$params[fechaFinal]'";
				break;
				case "erf":
					if ($params['EmitidoRecibido'] == "emitido") {
						$condicion = "WHERE EmitidoRecibido = '$params[EmitidoRecibido]' AND fechaXML BETWEEN '$params[fechaInicial]' AND '$params[fechaFinal]'";
					} else {
						$condicion = "WHERE EmitidoRecibido = '$params[EmitidoRecibido]' AND fechaXML BETWEEN '$params[fechaInicial]' AND '$params[fechaFinal]'";
					}
				break;
				case "ofna":
					$condicion = "WHERE x.idOficina = '$params[idOficina]' AND fechaXML BETWEEN '$params[fechaInicial]' AND '$params[fechaFinal]'";
				break;
			}
			
			/* $sql = "SELECT idXML,folioXML,rfcEmisor,nombreEmisor,rfcReceptor
				,nombreReceptor,fechaXML,totalXML,tipoDeDoctoXML,uuid,mimeArchivoPDF,codigoValidaCFDI
				,versionXML,poliza,ordenDeCompra,estadoValidaCFDI,x.idUsr,nombreUsr,x.idOficina,nombreOfna, fechaPago
				FROM $_SESSION[bd].dbo.xmls x
				JOIN sistemasDeApoyo.dbo.usuarios u ON u.idUsr = x.idUsr
				JOIN sistemasDeApoyo.dbo.oficinas o ON o.idOficina = x.idOficina
				$condicion 
				$esAdmin
				ORDER BY fechaXML DESC"; */
			$sql = "SELECT * FROM xmls x
				$condicion 
				ORDER BY fechaXML DESC";
			//echo $sql;
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
			// return $this->bd->consulta($sql);
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
			// return $this->bd->consulta($sql);
			return $this->bd->consulta($sql);
		}
		public function getInfoXML($uuid) {
			$sql = "SELECT * FROM xmls WHERE uuid = '$uuid'";
			return $this->bd->consulta($sql);
			// return $this->bd->consulta($sql);
		}
		public function nuevoXML($nvoUUID){
			$sql = "SELECT * FROM xmls WHERE uuid = '$nvoUUID'";
			$query = $this->bd->consulta($sql);
			$contador = $query->rowCount();
			// 				,CONVERT (VARBINARY(MAX), '$this->archivoXML', 1)
			if ( $contador == 0 ) {
				/* $sql = "INSERT INTO xmls (
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
					,tipoDeDoctoXML
					,archivoXML
					,sizeArchivoXML
					,mimeArchivoXML
					,versionXML
					,EmitidoRecibido
					,idUsr
					,idOficina) 
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
					,'$this->tipoDeDoctoXML'
					'$this->archivoXML'
					,'$this->sizeArchivoXML'
					,'$this->typeArchivoXML'
					,'$this->versionXML'
					,'$this->EmitidoRecibido'
					,'$this->idUsr'
					,'$this->idOficina'
					)
				"; */
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
					,RegimenFiscal
					,RegimenFiscalReceptor
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
					,$this->emisorRegimenFiscal
					,$this->regimenFiscalReceptor
					,'$this->EmitidoRecibido')
				";
				//echo $sql;
				$this->bd->consulta($sql);
			}
		}
		public function borrarXML($uuid) {
			$sql = "DELETE FROM xmls WHERE uuid = '$uuid'";
			// return $this->bd->consulta($sql);
			return $this->bd->consulta($sql);
			/* $stmt = $this->bd->getConexion()->prepare($sql);
			$stmt->execute();
			$stmt->closeCursor(); */
		}
		public function guardarPolizaOrdenCompra($id) {
			if (empty($this->fechaPago) || is_null($this->fechaPago) ) { $this->fechaPago = '2010-01-01'; }
			$sql = "UPDATE xmls SET poliza = '$this->poliza', ordenDeCompra = '$this->ordenDeCompra', fechaPago = '$this->fechaPago'
				WHERE idXML = $id";
			return $this->bd->consulta($sql);
			/* $stmt = $this->bd->getConexion()->prepare($sql);
			$stmt->execute();
			$stmt->closeCursor(); */
			// echo $sql;
		}
		public function guardarPDF($id) {
			$sql = "UPDATE xmls SET mimeArchivoPDF = '$this->mimeArchivoPDF', sizeArchivoPDF = '$this->sizeArchivoPDF', archivoPDF = '$this->archivoPDF' 
				WHERE idXML = $id";
			// $sql = "UPDATE xmls SET mimeArchivoPDF = '$this->mimeArchivoPDF', sizeArchivoPDF = '$this->sizeArchivoPDF', archivoPDF = CONVERT (VARBINARY(MAX), '$this->archivoPDF', 1)
			//	WHERE idXML = $id";
			// echo $sql;
			return $this->bd->consulta($sql);
			/* $stmt = $this->bd->getConexion()->prepare($sql);
			$stmt->execute();
			$stmt->closeCursor(); */
		}
		public function validaCFDI($id) {
			$sql = "UPDATE xmls set codigoValidaCFDI = '$this->codigoValidaCFDI', estadoValidaCFDI = '$this->estadoValidaCFDI'
				WHERE idXML = $id";
			// echo $sql;
			return $this->bd->consulta($sql);
			/* $stmt = $this->bd->getConexion()->prepare($sql);
			$stmt->execute();
			$stmt->closeCursor(); */
		}
		
		public function getRegimenFiscal($cve) {
			$sql = "SELECT * FROM sistemasDeApoyo.dbo.regimenFiscal WHERE claveRegimenFiscal = $cve";
			$data = $this->bd->consulta($sql);
			return $data[0]['descripcionRegimenFiscal'];
			//return print_r($data);
		}
		public function getMetodoPago($cve) {
			$sql = "SELECT * FROM sistemasDeApoyo.dbo.metodoPago WHERE claveMetodoPago = '$cve'";
			$data = $this->bd->consulta($sql);
			return $data[0]['descripcionMetodoPago'];
			//return print_r($data);
		}
		public function getFormaPago($cve) {
			$sql = "SELECT * FROM sistemasDeApoyo.dbo.formaPago WHERE claveFormaPago = $cve";
			$data = $this->bd->consulta($sql);
			return $data[0]['descripcionFormaPago'];
		}
		public function getUsoCFDI($cve) {
			$sql = "SELECT * FROM sistemasDeApoyo.dbo.usoCFDI WHERE claveUsoCFDI = '$cve'";
			$data = $this->bd->consulta($sql);
			return $data[0]['descripcionUsoCFDI'];
		}
		public function getImpuesto($cve) {
			$sql = "SELECT * FROM sistemasDeApoyo.dbo.impuestos WHERE claveImpuesto = '$cve'";
			$data = $this->bd->consulta($sql);
			return $data[0]['descripcionImpuesto'];
		}
		
		# GET's
		public function getIdXML(){ return $this->idXML; }
		public function getRfcEmisor(){ return $this->rfcEmisor; }
		public function getNombreEmisor(){ return $this->nombreEmisor; }
		public function getEmisorRegimenFiscal(){ return $this->emisorRegimenFiscal; }
		public function getRfcReceptor(){ return $this->rfcReceptor; }
		public function getNombreReceptor(){ return $this->nombreReceptor; }
		public function getRegimenFiscalReceptor(){ $this->regimenFiscalReceptor; }
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
		public function getEstadoValidaCFDI(){ return $this->estadoValidaCFDI; }
		public function getCodigoValidaCFDI(){ return $this->codigoValidaCFDI; }
		/* public function getResultado(){ return $this->resultado; } */
		
		# SET´s
		public function setIdXML($str){ $this->idXML = $str; }
		public function setRfcEmisor($str){ $this->rfcEmisor = $str; }
		public function setNombreEmisor($str){ $this->nombreEmisor = $str; }
		public function setEmisorRegimenFiscal($str){ $this->emisorRegimenFiscal = $str; }
		public function setRfcReceptor($str){ $this->rfcReceptor = $str; }
		public function setNombreReceptor($str){ $this->nombreReceptor = $str; }
		public function setRegimenFiscalReceptor($str){ $this->regimenFiscalReceptor = $str; }
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
		public function setIdUsr($str){ $this->idUsr = $str; }
		public function setIdOficina($str){ $this->idOficina = $str; }
		public function setPoliza($str){ $this->poliza = $str; }
		public function setOrdenDeCompra($str){ $this->ordenDeCompra = $str; }
		public function setArchivoPDF($str){ $this->archivoPDF = $str; }
		public function setMimeArchivoPDF($str){ $this->mimeArchivoPDF = $str; }
		public function setSizeArchivoPDF($str){ $this->sizeArchivoPDF = $str; }
		public function setCodigoValidaCFDI($str){ $this->codigoValidaCFDI = $str; }
		public function setEstadoValidaCFDI($str){ $this->estadoValidaCFDI = $str; }
		public function setFechaPago($str){ $this->fechaPago = $str; }
	}
?>
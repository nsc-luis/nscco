<?php
	session_start();
	ini_set("display_errors", "on");
	include_once("cDoctoCFDI.php");
	include_once("cProveedores.php");
	include_once("XML2Array.php");
	//$doctoCFDI = new DoctoCFDI();
	
	//$doctoCFDI->bd->setNombreBD($_SESSION['bd']);
	$rfcEmpresa = strtoupper($_SESSION['rfcEmpresa']);
	$errorDeValidacionXML = false;
	
	function array_change_key_case_recursive($input)
	{
		$input = array_change_key_case($input);
        foreach($input as $key=>$array){
            if(is_array($array)){
                $input[$key] = array_change_key_case_recursive($array);
            }
        }
        return $input; 
	}
	function mssql_escape($data) {
		if(is_numeric($data))
		return $data;
		$unpacked = unpack('H*hex', $data);
		return '0x' . $unpacked['hex'];
	}
	
	# Empieza la lectura de cada uno de los XMLs cargados
	foreach ($_FILES as $xml) {
		
		$doctoCFDI = new DoctoCFDI($_SESSION['bd']);
		
		// Habilita la captura de errores
		libxml_use_internal_errors(true);
		$nvoXML = new DOMDocument();
		$nvoXML->load($xml['tmp_name']);
		
		$msj = "";
		
		$xmlFile = XML2Array::createArray($nvoXML);
		$xmlFile = array_change_key_case_recursive($xmlFile);
		
		// echo "<pre>";
		// print_r($xmlFile);
		// echo "</pre>";
		
		switch ($xmlFile['cfdi:comprobante']['@attributes']['version']) {
			case '3.2':
				$emisorRFC = $xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['rfc'];
				$receptorRFC = $xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['rfc'];
				$emisorNombre = isset($xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['nombre']) ? $xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['nombre'] : "";
				$receptorNombre = isset($xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['nombre']) ? $xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['nombre'] : "";
				$lugarDeExpedicion = $xmlFile['cfdi:comprobante']['@attributes']['lugarexpedicion'];
				$serieXML = isset($xmlFile['cfdi:comprobante']['@attributes']['serie']) ? $xmlFile['cfdi:comprobante']['@attributes']['serie'] : "";
				$folioXML = isset($xmlFile['cfdi:comprobante']['@attributes']['folio']) ? $xmlFile['cfdi:comprobante']['@attributes']['folio'] : "";
				$uuid = $xmlFile['cfdi:comprobante']['cfdi:complemento']['tfd:timbrefiscaldigital']['@attributes']['uuid'];
				$subTotalXML = $xmlFile['cfdi:comprobante']['@attributes']['subtotal'];
				$impuestoXML = isset($xmlFile['cfdi:comprobante']['cfdi:impuestos']) ? $xmlFile['cfdi:comprobante']['cfdi:impuestos']['@attributes']['totalimpuestostrasladados'] : "";
				$totalXML = $xmlFile['cfdi:comprobante']['@attributes']['total'];
				$tipoDocto = '4';
				$fecha = $xmlFile['cfdi:comprobante']['@attributes']['fecha'];
				$fechaXML = explode("T",$fecha);
				$tipoDeDoctoXML = $xmlFile['cfdi:comprobante']['@attributes']['tipodecomprobante'];
				$EmitidoRecibido = strtoupper($emisorRFC) == $rfcEmpresa ? "emitido" : "recibido"; 
			break;
			case '3.3':
				$tipoDeDoctoXML = $xmlFile['cfdi:comprobante']['@attributes']['tipodecomprobante'];
				$emisorRFC = $xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['rfc'];
				$receptorRFC = $xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['rfc'];
				$emisorNombre = isset($xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['nombre']) ? $xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['nombre'] : "";
				$receptorNombre = isset($xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['nombre']) ? $xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['nombre'] : "";
				$lugarDeExpedicion = $xmlFile['cfdi:comprobante']['@attributes']['lugarexpedicion'];
				$serieXML = isset($xmlFile['cfdi:comprobante']['@attributes']['serie']) ? $xmlFile['cfdi:comprobante']['@attributes']['serie'] : "";
				$folioXML = isset($xmlFile['cfdi:comprobante']['@attributes']['folio']) ? $xmlFile['cfdi:comprobante']['@attributes']['folio'] : "";
				$uuid = $xmlFile['cfdi:comprobante']['cfdi:complemento']['tfd:timbrefiscaldigital']['@attributes']['uuid'];
				$subTotalXML = $xmlFile['cfdi:comprobante']['@attributes']['subtotal'];
				$impuestoXML = isset($xmlFile['cfdi:comprobante']['cfdi:impuestos']['@attributes']['totalimpuestostrasladados']) ? $xmlFile['cfdi:comprobante']['cfdi:impuestos']['@attributes']['totalimpuestostrasladados'] : "0.00";
				$totalXML = isset($xmlFile['cfdi:comprobante']['@attributes']['total']) ? $xmlFile['cfdi:comprobante']['@attributes']['total'] : "0.00";
				$tipoDocto = '4';
				$fecha = $xmlFile['cfdi:comprobante']['@attributes']['fecha'];
				$fechaXML = explode("T",$fecha);
				//// $rfcEmisor = strtoupper($xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['rfc']);
				//// $rfcReceptor = strtoupper($xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['rfc']);
				//// $nombreEmisor = isset($xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['nombre']) ? $xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['nombre'] : ""; 
				//// $nombreReceptor = isset($xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['nombre']) ? $xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['nombre'] : "";
				$versionXML = $xmlFile['cfdi:comprobante']['@attributes']['version'];
				$EmitidoRecibido = strtoupper($emisorRFC) == $rfcEmpresa ? "emitido" : "recibido"; 
			break;
		}
		
		if ($emisorRFC == $rfcEmpresa || $receptorRFC == $rfcEmpresa) {
			# Preparando archivo_temporal para ser cargado a la BD
			$fp = fopen($xml['tmp_name'],"r");
			$dataFile = fread($fp, $xml['size']);
			$dataFile = addslashes($dataFile);
			// $dataFile = mssql_escape($dataFile);
			// echo $dataFile;
			// $dataFile = bin2hex($dataFile);
			// $dataFile = "0x".$dataFile;
			// $dataFile = base64_encode($dataFile);
			fclose($fp);
			
			try {
				# Estableciendo valores validados para ser cargados a la BD
				$doctoCFDI->setRfcEmisor($emisorRFC);
				$doctoCFDI->setNombreEmisor($emisorNombre);
				$doctoCFDI->setRfcReceptor($receptorRFC);
				$doctoCFDI->setNombreReceptor($receptorNombre);
				$doctoCFDI->setFolioXML($serieXML . $folioXML);
				$doctoCFDI->setFechaXML($fechaXML['0']);
				$doctoCFDI->setUuid($uuid);
				$doctoCFDI->setSubTotalXML($subTotalXML);
				$doctoCFDI->setImpuestoXML($impuestoXML);
				$doctoCFDI->setTotalXML($totalXML);
				$doctoCFDI->setIdTipoDocto($tipoDocto);
				$doctoCFDI->setTipoDeDoctoXML($tipoDeDoctoXML);
				$doctoCFDI->setArchivoXML($dataFile);
				$doctoCFDI->setSizeArchivoXML($xml['size']);
				$doctoCFDI->setTypeArchivoXML($xml['type']);
				$doctoCFDI->setVersionXML($versionXML);
				$doctoCFDI->setEmitidoRecibido($EmitidoRecibido);
				$doctoCFDI->setIdUsr($_SESSION['idUsuario']);
				// $doctoCFDI->setIdOficina($_SESSION['idOficina']);
				
				$doctoCFDI->nuevoXML($uuid);
				
				# Registra proveedores
				//$proveedor = new Proveedores();
				$proveedor = new Proveedores($_SESSION['bd']);
				//$proveedor->bd->setNombreBD($_SESSION['bd']);
				
				if ($EmitidoRecibido == "recibido") {
					// $direccionEmisor = "$emisorCalle $emisorNoExterior $emisorNoInterior
						// , $emisorColonia, $emisorMunicipio, $emisorEstado
						// , $emisorPais, cp: $emisorCodigoPostal";
					$proveedor->getInfoProveedor($emisorRFC);
					$qRFCProveedor = $proveedor->getRFCProveedor();
					
					if ($qRFCProveedor == "") {
						$proveedor->setRFCProveedor($emisorRFC);
						$proveedor->setNombreProveedor($emisorNombre);
						// $proveedor->setDireccionProveedor($lugarDeExpedicion);				
						$proveedor->nuevoProveedor();
					}
					if ($qRFCProveedor == $emisorRFC) {
						$proveedor->setNombreProveedor($emisorNombre);
						// $proveedor->setDireccionProveedor($lugarDeExpedicion);
						$proveedor->editaInfoProveedor($emisorRFC);
					}
				}
				$proveedor->bd->CerrarBD();
				$errorDeValidacionXML = false;
			}
			catch (Throwable $t){
				$errorDeValidacionXML = true;
				echo $t;
			}
		}
		$doctoCFDI->bd->cerrarBD();
	}
	if ($errorDeValidacionXML == true) return false;
	else echo "<SCRIPT> location.reload(); </SCRIPT>";
?>
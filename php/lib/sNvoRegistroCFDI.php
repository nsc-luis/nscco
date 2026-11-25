<?php
	session_start();
	$rfcEmpresa = strtoupper($_SESSION['bd']);
	include_once("cDoctoCFDI.php");
	include_once("cProveedores.php");
	$doctoCFDI = new DoctoCFDI();
	$proveedor = new Proveedores();
	$bd = $_SESSION['bd'];
	$doctoCFDI->bd->setNombreBD($bd);
	$proveedor->bd->setNombreBD($bd);
	$errorDeValidacionXML = "";
	
	function libxml_display_error($error) {
		$return = "\n";
		switch ($error->level) {
			case LIBXML_ERR_WARNING:
				$return .= "Warning $error->code: ";
				break;
			case LIBXML_ERR_ERROR:
				$return .= "Error $error->code: ";
				break;
			case LIBXML_ERR_FATAL:
				$return .= "Fatal Error $error->code: ";
				break;
		}
		$return .= trim($error->message);
		if ($error->file) {
			$return .= " in $error->file";
		}
		$return .= " on line $error->line\n";
			return $return;
	}
	function libxml_display_errors() {
		$ruta = "../../tmp/";
		$bitacora = fopen($ruta . "bitacora.txt", "a");
		$errors = libxml_get_errors();
		foreach ($errors as $error) {
			fwrite($bitacora, libxml_display_error($error) . PHP_EOL);
		}
		fclose($bitacora);
		libxml_clear_errors();
	}
	
	# Empieza la lectura de cada uno de los XMLs cargados
	foreach ($_FILES as $xml) {
		
		// Habilita la captura de errores
		libxml_use_internal_errors(true);
		$nvoXML = new DOMDocument();
		$nvoXML->load($xml['tmp_name']);

		if (!$nvoXML->schemaValidate('xsd_cfdi_v32.xsd')) {
			$errorDeValidacionXML .= 'DOMDocument::schemaValidate() Generated Errors!';
			libxml_display_errors();
			//continue;
		}
		
		$msj = "";
		$xmlFile = simplexml_load_file($xml['tmp_name']);
		$ns = $xmlFile->getNamespaces(true); 
		
		# Si no encuentra 'cfdi' como namespace descarta que el doctos sea un CFDI
		if (!$xmlFile->registerXPathNamespace('cfdi', $ns['cfdi'])) {
			$msj .= "El documento XML ingresado NO es un CFDI";
			continue;
		}
		$xmlFile->registerXPathNamespace('cfdi', $ns['cfdi']);
		$xmlFile->registerXPathNamespace('tfd', $ns['tfd']);
		
		# Obteniendo/Validando datos del XML para la base de datos
		foreach ($xmlFile->xpath('//cfdi:Comprobante') as $cfdiComprobante){ 
			if ($cfdiComprobante['fecha']) { $fechaDoctoXML = $cfdiComprobante['fecha']; }
			else { 
				$msj .= "No se ha encontrado el atributo fecha (" . $xml['name'] . ")<br />";
				continue;
			}
			
			if ($cfdiComprobante['version']) { $versionXML = $cfdiComprobante['version']; }
			else { 
				$msj .= "La version del CFDI es diferente a 3.2 (" . $xml['name'] . ")<br />";
				continue;
			}

			if ($cfdiComprobante['serie']) { $serieXML = $cfdiComprobante['serie']; }
			else { $serieXML = ""; }
			
			if ($cfdiComprobante['folio']) { $folioXML = $cfdiComprobante['folio']; }
			else { $folioXML = ""; }
			
			if ($cfdiComprobante['total']) { $totalXML = $cfdiComprobante['total']; }
			else {
				$msj .= "No se ha encontrado el atributo total (" . $xml['name'] . ")<br />";
				continue;
			}

			if ($cfdiComprobante['subTotal']) { $subTotalXML = $cfdiComprobante['subTotal']; }
			else {
				$msj .= "No se ha encontrado el atributo subTotal (" . $xml['name'] . ")<br />";
				continue;
			}
		} 
		foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
			if ($Emisor['rfc']) { $emisorRFC = strtoupper($Emisor['rfc']); }
			else {
				$msj .= "No se ha encontrado el atributo Emisor-RFC (" . $xml['name'] . ")<br />";
				continue;
			}
			
			if ($Emisor['nombre']) { $emisorNombre = $Emisor['nombre']; }
			else {
				$msj .= "No se ha encontrado el atributo Emisor-Nombre (" . $xml['name'] . ")<br />";
				continue;
			}
		}
		foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){
			$emisorCalle = $DomicilioFiscal['calle'];
			$emisorNoExterior = $DomicilioFiscal['noExterior'];
			$emisorNoInterior = $DomicilioFiscal['noInterior'];
			$emisorColonia = $DomicilioFiscal['colonia'];
			$emisorMunicipio = $DomicilioFiscal['municipio'];
			$emisorEstado = $DomicilioFiscal['estado'];
			$emisorPais = $DomicilioFiscal['pais'];
			$emisorCodigoPostal = $DomicilioFiscal['codigoPostal'];
		}
		
		foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
			if ($Emisor['rfc']) { $receptorRFC = strtoupper($Receptor['rfc']); }
			else {
				$msj .= "No se ha encontrado el atributo Receptor-RFC (" . $xml['name'] . ")<br />";
				continue;
			}
			
			if ($Emisor['nombre']) { $receptorNombre = $Receptor['nombre']; }
			else {
				$msj .= "No se ha encontrado el atributo Receptor-Nombre (" . $xml['name'] . ")<br />";
				continue;
			}
		}
		foreach ($xmlFile->xpath('//tfd:TimbreFiscalDigital') as $tfd) {
			if ($tfd['UUID']) { $uuid = $tfd['UUID']; }
			else {
				$msj .= "No se ha encontrado el atributo UUID (" . $xml['name'] . ")<br />";
				continue;
			}
		}
		
		if ($emisorRFC != $rfcEmpresa && $receptorRFC != $rfcEmpresa) {
			$msj .= "El XML del CFDI no corresponde a esta empresa (" . $xml['name'] . ")<br />";
		}
		
		# Fecha del documento
		$fechaXML = explode("T",$fechaDoctoXML); 
		
		# Determinando si el CFDI es INGRESO o EGRESO
		$tipoDocto = ($rfcEmpresa == $emisorRFC) ? "3" : "4";
		$impuestoXML = $totalXML - $subTotalXML;
		
		# Validando SI el XML contiene errores antes de ser cargado, de lo contrario se carga a la BD
		if ($msj != "") { echo $msj; }
		else {
			# Preparando archivo_temporal para ser cargado a la BD
			$fp = fopen($xml['tmp_name'],"r+b");
			$dataFile = fread($fp, $xml['size']);
			$dataFile = addslashes($dataFile);
			fclose($fp);
			
			# Estableciendo valores validados para ser cargados a la BD
			$doctoCFDI->setRfcXML($emisorRFC);
			$doctoCFDI->setRazonSocialXML($emisorNombre);
			$doctoCFDI->setFolioXML($serieXML . $folioXML);
			$doctoCFDI->setFechaXML($fechaXML['0']);
			$doctoCFDI->setUuid($uuid);
			$doctoCFDI->setSubTotalXML($subTotalXML);
			$doctoCFDI->setImpuestoXML($impuestoXML);
			$doctoCFDI->setTotalXML($totalXML);
			$doctoCFDI->setIdTipoDocto($tipoDocto);
			$doctoCFDI->setArchivoXML($dataFile);
			$doctoCFDI->setSizeArchivoXML($xml['size']);
			$doctoCFDI->setTypeArchivoXML($xml['type']);
			
			$doctoCFDI->nuevoXML($uuid);
			
			# Registra proveedores
			if ($tipoDocto == "4") {
				$direccionEmisor = "$emisorCalle $emisorNoExterior $emisorNoInterior
					, $emisorColonia, $emisorMunicipio, $emisorEstado
					, $emisorPais, cp: $emisorCodigoPostal";
				$proveedor->getInfoProveedor($emisorRFC);
				$qRFCProveedor = $proveedor->getRFCProveedor();
				if ($qRFCProveedor == "") {
					$proveedor->setRFCProveedor($emisorRFC);
					$proveedor->setNombreProveedor($emisorNombre);
					$proveedor->setDireccionProveedor($direccionEmisor);				
					$proveedor->nuevoProveedor();
				}
				if ($qRFCProveedor == $emisorRFC) {
					$proveedor->setNombreProveedor($emisorNombre);
					$proveedor->setDireccionProveedor($direccionEmisor);
					$proveedor->editaInfoProveedor($emisorRFC);
				}
			}
		}
	}
	echo "<SCRIPT> location.reload(); </SCRIPT>";
?>
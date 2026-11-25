<?php
	session_start();
	include_once ("/php/lib/cDoctoCFDI.php");
	include_once ("/php/lib/phpqrcode.php");
	$doctoXML = new DoctoCFDI();
	$doctoXML->bd->setNombreBD($_SESSION['bd']);
	$rfcEmpresa = strtoupper($_SESSION['bd']);
	
	$rfcProvFiltro = "0";
	$fecha = "2016";
	$fUUID = "";
	$fTipoDocto = "0";
	
	$xmls = $doctoXML->cfdis($rfcProvFiltro,$fecha,$fUUID,$fTipoDocto);
	$doctos = mysql_fetch_array($xmls);
	
	$html = "
		<table>
			<tr>
				<th>RFC</th><th>Nombre</th><th>UUID</th>
			</tr>";
	
	while ($row = mysql_fetch_array($xmls)) {
		$uuid = $row['uuid'];
		$data = mysql_fetch_assoc($doctoXML->getInfoXML($uuid));
		$xmlFile = simplexml_load_string($data['archivoXML']);
		$ns = $xmlFile->getNamespaces(true); 
		$xmlFile->registerXPathNamespace('cfdi', $ns['cfdi']);
		$xmlFile->registerXPathNamespace('tfd', $ns['tfd']);
		
		foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
			$rfc = $Emisor['rfc'];
			$nombre = $Emisor['nombre'];
		}
		foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
			$rfcReceptor = $Receptor['rfc'];
			$nombreReceptor = $Receptor['nombre'];
		}
		
		if ($rfc != $rfcEmpresa && $rfcReceptor != $rfcEmpresa){
			$html .= "<tr><td>$rfcReceptor</td><td>$nombreReceptor</td><td>$uuid</td></tr>";
			$doctoXML->borrarXML($uuid);
		}
	}
	
	$html .= "</table>";
	
	echo $html;
?>
	
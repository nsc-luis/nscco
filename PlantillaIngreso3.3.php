<?php
	session_start();
	ini_set("display_errors", "off");
	include_once ("./php/lib/XML2Array.php");
	include_once ("./php/lib/phpqrcode.php");
	include_once ("./php/lib/cDoctoCFDI.php");
	$objCFDI = new DoctoCFDI($_SESSION['bd']);
	// $objCFDI->bd->setNombreBD($_SESSION['bd']);
	
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
	
	function divideCadena($str,$len) {
		$newStr = $str;
		$bar = array();
		for ($i=0;$i<strlen($newStr);$len) {
			array_push($bar,substr($newStr,$i,$len));
			$newStr = substr($newStr,$len,strlen($newStr));
		}
		$foo = "";
		foreach ($bar as $element) {
			$foo .= $element . "<br />";
		}
		return $foo;
	}
	
	// $url = "http://www.nscco.com.mx/php/lib/PDO_getXML_UUID.php?uuid=" . $_GET['uuid'];
	$url = "http://localhost/php/lib/PDO_getXML_UUID.php?uuid=" . $_GET['uuid'];
	$response_xml_data = file_get_contents($url,"r");
	$xmlFile = XML2Array::createArray($response_xml_data);
	$xmlFile = array_change_key_case_recursive($xmlFile);
	
	$nombreEmisor = isset($xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['nombre']) ? $xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['nombre'] : ""; 
	$nombreReceptor = isset($xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['nombre']) ? $xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['nombre'] : "";
	$folio = isset($xmlFile['cfdi:comprobante']['@attributes']['folio']) ? $xmlFile['cfdi:comprobante']['@attributes']['folio'] : "";
	$serie = isset($xmlFile['cfdi:comprobante']['@attributes']['serie']) ? $xmlFile['cfdi:comprobante']['@attributes']['serie'] : "";
	$sellocfd = "||1.0|" . $_GET['uuid'] . "|" . $xmlFile['cfdi:comprobante']['@attributes']['fecha'] . "|" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['tfd:timbrefiscaldigital']['@attributes']['sellosat'] . "|" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['tfd:timbrefiscaldigital']['@attributes']['nocertificadosat'] . "||";
	
	$str = explode(".",$xmlFile['cfdi:comprobante']['@attributes']['total']);
	$str1 = str_pad($str[0], 10, "0", STR_PAD_LEFT);
	$str2 = str_pad($str[1], 6, "0", STR_PAD_RIGHT);
	$totalqr = $str1 . "." . $str2;
	$qrStr = "?re=" . strtoupper($xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['rfc']) . "&rr=" . strtoupper($xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['rfc']) . "&tt=" . $totalqr . "&id=" . $_GET['uuid'];
	$TMPDIR = "tmp/";
	$fileName = $_GET['uuid'] . ".png";
	$pngPath = $TMPDIR . $fileName;
	if (!file_exists($pngPath)) {
        QRcode::png($qrStr, $pngPath);
	}
	
	$html = "
		<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
		<html>
		<head>
			<title></title>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
			<link type='text/css' href='css/jquery-ui.min.css' rel='stylesheet'>
			<link type='text/css' href='css/representacionCFDI.css' rel='stylesheet'>	
			<script src='js/jquery.js' language='javascript'></script>
			<script src='js/jquery-ui.min.js' language='javascript'></script>
			<script src='js/fnRepresentacionCFDI.js' language='javascript'></script>
		</head>
		<body onload='resizeTo(800,600);'>
			<TABLE>
				<TR>
					<td>
						<span class='titulo'>EMISOR:</span>
						<TABLE style='border:1px solid black'>
							<TR>
								<TD width='15%' class='CellStyleCaption'>Nombre:</TD>
								<TD width='60%'>" . $nombreEmisor . "</TD>
							</TR>
							<TR>
								<TD class='CellStyleCaption'>R.F.C.:</TD>
								<TD>" . strtoupper($xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['rfc']) . "</TD>
							</TR>
							<TR>
								<TD class='CellStyleCaption'>Regimen fiscal:</TD>
								<TD>" . $xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['regimenfiscal'] . " - " . $objCFDI->getRegimenFiscal($xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['regimenfiscal']) . "</TD>
							</TR>
						</TABLE>
						<span class='titulo'>RECEPTOR:</span>
						<TABLE style='border:1px solid black'>
							<TR>
								<TD width='15%' class='CellStyleCaption'>Nombre:</TD>
								<TD width='60%'>" . $nombreReceptor . "</TD>
							</TR>
							<TR>
								<TD class='CellStyleCaption'>R.F.C.:</TD>
								<TD>" . strtoupper($xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['rfc']) . "</TD>
							</TR>
						</TABLE>
					</td>
					<td width='25%' align='center' valign='top' align='center'>
						<b>Docto: " . $xmlFile['cfdi:comprobante']['@attributes']['tipodecomprobante'] . "</b><br />
						<span class='titulo5'> Folio </span>
						" . ($serie . $folio) . "
						<span class='titulo5'> Cert. Emisor </span>
						" . $xmlFile['cfdi:comprobante']['@attributes']['nocertificado'] . "<br />
						<span class='titulo5'> Folio Fiscal </span>
						" . $_GET['uuid'] . "<br />
						<span class='titulo5'> Fecha </span>
						" . $xmlFile['cfdi:comprobante']['@attributes']['fecha'] . "
					</td>
				</TR>
			</TABLE>
			
			<!-- <p class='btnImprimir'><button id='btnImprimirRepresentacionCFDI'>Imprimir</button></p> -->
			
			<TABLE width='100%'>
				<TR>
					<td width='50%'><b>Metodo de pago: </b>" . $xmlFile['cfdi:comprobante']['@attributes']['metodopago'] . " - " . $objCFDI->getMetodoPago($xmlFile['cfdi:comprobante']['@attributes']['metodopago']) . "</td>
					<TD width='49%'><b>Forma de pago: </b>" . $xmlFile['cfdi:comprobante']['@attributes']['formapago'] . " - " . $objCFDI->getFormaPago($xmlFile['cfdi:comprobante']['@attributes']['formapago']) . "</td>
				</TR>
				<tr>
					<TD><b>Lugar de expedicion: </b>" . $xmlFile['cfdi:comprobante']['@attributes']['lugarexpedicion'] . "</TD>
					<TD><b>Moneda: </b>" . $xmlFile['cfdi:comprobante']['@attributes']['moneda'] . "</td>
				</tr>
				<tr>
					<TD><b>Uso de CFDI: </b>" . $xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['usocfdi'] . " - " . $objCFDI->getUsoCFDI($xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['usocfdi']) . "</TD>
				</tr>
			</TABLE>
			<div id='partidas'>
				<TABLE>
					<TR>
						<TD width='12%' class='titulo4'>Cantidad</TD>
						<TD width='8%' class='titulo4'>Unidad</TD>
						<TD width='37%' class='titulo4'>Descripcion</TD>
						<TD width='14%' class='titulo4'>Valor Unitario</TD>
						<TD width='14%' class='titulo4'>Impuesto</TD>
						<TD width='15%' class='titulo4'>Importe</TD>
					</TR>
	";
	
	if ($xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes'] == 0) {
		foreach ($xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto'] as $concepto) {
			$html .= "
				<TR>
					<TD align='center'>" . $concepto['@attributes']['cantidad'] . "</TD>
					<TD align='center'>" . $concepto['@attributes']['claveunidad'] . "</TD>
					<TD>" . $concepto['@attributes']['descripcion'] . ". Clave: " . $concepto['@attributes']['claveprodserv'] . "</TD>
					<TD align='right'>" . $concepto['@attributes']['valorunitario'] . "</TD>
					<TD align='center'>
						Importe: "
						. $concepto['cfdi:impuestos']['cfdi:traslados']['cfdi:traslado']['@attributes']['importe'] . " | Impuesto: "
						. $objCFDI->getImpuesto($concepto['cfdi:impuestos']['cfdi:traslados']['cfdi:traslado']['@attributes']['impuesto']) . "
					</TD>
					<TD align='right'>" . ($concepto['@attributes']['cantidad'] * $concepto['@attributes']['valorunitario']) . "</TD>
				</TR>
			";
		}
	} else {
		foreach ($xmlFile['cfdi:comprobante']['cfdi:conceptos'] as $concepto) {
			$html .= "
				<TR>
					<TD align='center'>" . $concepto['@attributes']['cantidad'] . "</TD>
					<TD align='center'>" . $concepto['@attributes']['claveunidad'] . "</TD>
					<TD>" . $concepto['@attributes']['descripcion'] . ". Clave: " . $concepto['@attributes']['claveprodserv'] . "</TD>
					<TD align='right'>" . $concepto['@attributes']['valorunitario'] . "</TD>
					<TD align='center'>
						Importe: "
						. $concepto['cfdi:impuestos']['cfdi:traslados']['cfdi:traslado']['@attributes']['importe'] . " | Impuesto: "
						. $objCFDI->getImpuesto($concepto['cfdi:impuestos']['cfdi:traslados']['cfdi:traslado']['@attributes']['impuesto']) . "
					</TD>
					<TD align='right'>" . ($concepto['@attributes']['cantidad'] * $concepto['@attributes']['valorunitario']) . "</TD>
				</TR>
			";
		}
	}
	
	$html .= "
		</TABLE>
		</div>
		<TABLE>
			<tr>
				<td rowspan='7' style='width: 80%;'>
					<span class='titulo'>Importe con letra:</span>
					<i>IMPORTE CON LETRA AUN NO PREPARADO</i>
				</td>
			</tr>
			<TR>
				<TD class='titulo3'>SubTotal</TD>
				<TD class='CellStyle'>" . $xmlFile['cfdi:comprobante']['@attributes']['subtotal'] . "</TD>
			</TR>
			<TR>
				<TD class='titulo3'>Impuestos</TD>
				<TD class='CellStyle'>" . $xmlFile['cfdi:comprobante']['cfdi:impuestos']['@attributes']['totalimpuestostrasladados'] . "</TD>
			</TR>
			<TR>
				<TD class='titulo3'>Total</TD>
				<TD class='CellStyle'>" . $xmlFile['cfdi:comprobante']['@attributes']['total'] . "</TD>
			</TR>
		</TABLE>
		<table width='100%'>
			<tr>
				<td rowspan='7'>
					<img src='" . $pngPath . "' />
				</td>
			</tr>	
			<TR>
				<TD class='tituloCadena'>Sello digital del CFDI:</TD>
			</TR>
			<TR>
				<TD class='CellStyleCadena'>" . divideCadena($xmlFile['cfdi:comprobante']['cfdi:complemento']['tfd:timbrefiscaldigital']['@attributes']['sellocfd'],105) . "</TD>
			</TR>
			<TR>
				<TD class='tituloCadena'>Sello del SAT</TD>
			</TR>
			<TR>
				<TD class='CellStyleCadena'>" . divideCadena($xmlFile['cfdi:comprobante']['cfdi:complemento']['tfd:timbrefiscaldigital']['@attributes']['sellosat'],105) . "</TD>
			</TR>
			<TR>
				<TD class='tituloCadena'>
					Cadena original del complemento de certificacion digital del SAT
				</TD>
			</TR>
			<TR>
				<TD class='CellStyleCadena'>" . divideCadena($sellocfd,105) . "</TD>
			</TR>
		</table>
		<br />
		Este documento es una representacion impresion de un CFDI v3.3
	";
	echo $html;
	//echo "hi!";
?>

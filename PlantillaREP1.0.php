<?php
	session_start();
	ini_set("display_errors", "off");
	include_once ("/php/lib/XML2Array.php");
	include_once ("/php/lib/phpqrcode.php");
	include_once ("/php/lib/cDoctoCFDI.php");
	$objCFDI = new DoctoCFDI();
	$objCFDI->bd->setNombreBD($_SESSION['bd']);
	
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
	
	// $db = new PDO('mysql:host=localhost;dbname=ccp040210lja;charset=utf8mb4', 'root', '@1B2c3d4');
	// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
	$url = "http://nscco.com.mx/php/lib/PDO_getXML_UUID.php?uuid=" . $_GET['uuid'];
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
	$fileName = $uuidBD . ".png";
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
								<TD>" . $xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['regimenfiscal'] . " - " . $objCFDI->getC_RegimenFiscal($xmlFile['cfdi:comprobante']['cfdi:emisor']['@attributes']['regimenfiscal']) . "</TD>
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
					<TD><b>Lugar de expedicion: </b>" . $xmlFile['cfdi:comprobante']['@attributes']['lugarexpedicion'] . "</TD>
				</TR>
			</TABLE>
			<div id='partidas'>
				<TABLE>
					<TR>
						<TD width='12%' class='titulo4'>Cantidad</TD>
						<TD width='8%' class='titulo4'>Unidad</TD>
						<TD width='51%' class='titulo4'>Descripcion</TD>
						<TD width='14%' class='titulo4'>Valor Unitario</TD>
						<TD width='15%' class='titulo4'>Importe</TD>
					</TR>
					<TR>
						<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['cantidad'] . "</TD>
						<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['claveunidad'] . "</TD>
						<TD>" . $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['descripcion'] . ". Clave: " . $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['claveprodserv'] . "</TD>
						<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['valorunitario'] . "</TD>
						<TD align='right'>" . ($xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['cantidad'] * $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['valorunitario']) . "</TD>
					</TR>
				</TABLE>
				<TABLE>
					<TR>
						<TD class='titulo4'>FechaPago</TD>
						<TD class='titulo4'>FormaDePagoP</TD>
						<TD class='titulo4'>MonedaP</TD>
						<TD class='titulo4'>Monto</TD>
						<TD class='titulo4'>NumOperacion</TD>
					</TR>
					<TR>
						<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['@attributes']['fechapago'] . "</TD>
						<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['@attributes']['formadepagop'] . " - " . $objCFDI->getC_FormaPago($xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['@attributes']['formadepagop']) . "</TD>
						<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['@attributes']['monedap'] . "</TD>
						<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['@attributes']['monto'] . "</TD>
						<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['@attributes']['numoperacion'] . "</TD>
					</TR>
				</TABLE>
				<P style='padding:0; margin:0; width:100%; font-weight:bold; text-decoration:underline; font-style:italic; text-align:center;'>DOCUMENTOS RELACIONADOS</P>
				<TABLE>
					<TR>
						<TD class='titulo4'>IdDocumento</TD>
						<TD class='titulo4'>Folio</TD>
						<TD class='titulo4'>MonedaDR</TD>
						<TD class='titulo4'>MetodoDePagoDR</TD>
						<TD class='titulo4'>NumParcialidad</TD>
						<TD class='titulo4'>ImpSaldoAnt</TD>
						<TD class='titulo4'>ImpPagado</TD>
						<TD class='titulo4'>ImpSaldoInsoluto</TD>
					</TR>
	";
	
	if (isset($xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][0])) {
		for ($i=0; $i<count($xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']); $i++) {
			$serie = isset($xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['serie']) ? $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['serie'] : "";
			$folio = isset($xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['folio']) ? $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['folio'] : "";
			$html .= "
				<TR>
					<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['iddocumento'] . "</TD>
					<TD align='center'>" . $serie . $folio . "</TD>
					<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['monedadr'] . "</TD>
					<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['metododepagodr'] . " - " . $objCFDI->getC_MetodoPago($xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['metododepagodr']) . "</TD>
					<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['numparcialidad'] . "</TD>
					<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['impsaldoant'] . "</TD>
					<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['imppagado'] . "</TD>
					<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado'][$i]['@attributes']['impsaldoinsoluto'] . "</TD>
				</TR>
			";
		}
	} else {
		$serie = isset($xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['serie']) ? $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['serie'] : "";
		$folio = isset($xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['folio']) ? $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['folio'] : "";
		$html .= "
			<TR>
				<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['iddocumento'] . "</TD>
				<TD align='center'>" . $serie . $folio . "</TD>
				<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['monedadr'] . "</TD>
				<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['metododepagodr'] . " - " . $objCFDI->getC_MetodoPago($xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['metododepagodr']) . "</TD>
				<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['numparcialidad'] . "</TD>
				<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['impsaldoant'] . "</TD>
				<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['imppagado'] . "</TD>
				<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['pago10:pagos']['pago10:pago']['pago10:doctorelacionado']['@attributes']['impsaldoinsoluto'] . "</TD>
			</TR>
		";
	}
	
	$html .= "
		</TABLE>
		</div>
		<!-- <TABLE>
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
		</TABLE> -->
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
		Este documento es una representacion impresion de un REP v3.3
	";
	echo $html;
?>

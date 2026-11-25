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
							<TR>
								<TD class='CellStyleCaption'>Registro patronal:</TD>
								<TD>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:emisor']['@attributes']['registropatronal'] . "</TD>
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
						<span class='titulo5'> Cert. Emisor </span>
						" . $xmlFile['cfdi:comprobante']['@attributes']['nocertificado'] . "<br />
						<span class='titulo5'> Folio Fiscal </span>
						" . $_GET['uuid'] . "<br />
						<span class='titulo5'> Fecha </span>
						" . $xmlFile['cfdi:comprobante']['@attributes']['fecha'] . "
					</td>
				</TR>
			</TABLE>
			<span class='titulo5'> EMPLEADO </span>
			<table>
				<tr>
					<td with='33%'>
						<b>Nombre: </b>" . $nombreReceptor . " ( " . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:receptor']['@attributes']['numempleado'] . " ) <br />
						<b>CURP: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:receptor']['@attributes']['curp'] . "<br />
						<b>RFC: </b>" . strtoupper($xmlFile['cfdi:comprobante']['cfdi:receptor']['@attributes']['rfc']) . "<br />
						<b>NumSeguridadSocial: </b>" .  $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:receptor']['@attributes']['numseguridadsocial'] . "<br />
						<b>FechaInicioRelLaboral: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:receptor']['@attributes']['fechainiciorellaboral'] . "
					</td>
					<td with='33%'>
						<b>Periodo: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['@attributes']['fechainicialpago']
						. " - " . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['@attributes']['fechafinalpago'] . "<br />
						<b>PeriodicidadPago: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['@attributes']['periodicidadpago']
						. " ( " . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['@attributes']['numdiaspagados'] . " )" . "<br />
						<b>FechaPago: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['@attributes']['fechapago'] . "<br />
						<b>SalarioDiarioIntegrado: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:receptor']['@attributes']['salariodiariointegrado'] . "<br />
						<b>SalarioDiario: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:receptor']['@attributes']['salariobasecotapor'] . "<br />
					</td>
					<td with='33%'>
						<b>Departamento: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:receptor']['@attributes']['departamento'] . "<br />
						<b>Puesto: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:receptor']['@attributes']['puesto'] . "<br />
						<b>TipoJornada: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:receptor']['@attributes']['tipojornada'] . "<br />
						<b>TipoRegimen: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:receptor']['@attributes']['tiporegimen'] . "<br />
						<b>TipoContrato: </b>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:receptor']['@attributes']['tipocontrato'] . "
					</td>
				</tr>
			</table>
			<TABLE>
				<TR>
					<TD width='12%' class='titulo4'>Cantidad</TD>
					<TD width='8%' class='titulo4'>Unidad</TD>
					<TD width='37%' class='titulo4'>Descripcion</TD>
					<TD width='14%' class='titulo4'>Valor Unitario</TD>
					<TD width='15%' class='titulo4'>Importe</TD>
					<TD width='14%' class='titulo4'>Descuento</TD>
				</TR>
				<TR>
					<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['cantidad'] . "</TD>
					<TD align='center'>" . $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['claveunidad'] . "</TD>
					<TD>" . $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['descripcion'] . ". Clave: " . $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['claveprodserv'] . "</TD>
					<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['valorunitario'] . "</TD>
					<TD align='right'>" . ($xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['cantidad'] * $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['valorunitario']) . "</TD>
					<TD align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:conceptos']['cfdi:concepto']['@attributes']['descuento'] . "</TD>
				</TR>
			</TABLE>
			<hr>
			<table>
				<tr>
					<td>
						<span class='titulo5'>Percepciones</span>
						<table>
							<tr>
								<th>Clave</th>
								<th>Concepto</th>
								<th>Grabado</th>
								<th>Excento</th>
							</tr>
	";
	
	if ($xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:percepciones']['nomina12:percepcion'][0]) {
		foreach($xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:percepciones']['nomina12:percepcion'] as $percepcion) {
			$html .= "
				<tr>
					<td>" . $percepcion['@attributes']['clave'] . " ( " . $percepcion['@attributes']['tipopercepcion'] . " )</td>
					<td>" . $percepcion['@attributes']['concepto'] . "</td>
					<td align='right'>" . $percepcion['@attributes']['importegravado'] . "</td>
					<td align='right'>" . $percepcion['@attributes']['importeexento'] . "</td>
				</tr>
			";
		}
	} else {
		$html .= "
			<tr>
				<td>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:percepciones']['nomina12:percepcion']['@attributes']['clave'] . " ( " . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:percepciones']['nomina12:percepcion']['@attributes']['tipopercepcion'] . " )</td>
				<td>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:percepciones']['nomina12:percepcion']['@attributes']['concepto'] . "</td>
				<td align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:percepciones']['nomina12:percepcion']['@attributes']['importegravado'] . "</td>
				<td align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:percepciones']['nomina12:percepcion']['@attributes']['importeexento'] . "</td>
			</tr>
		";
	}
	
	$html .= "
		</table>
			</td>
			<td style='vertical-align:top;'>
				<span class='titulo5'>Deducciones</span>
				<table>
					<tr>
						<th>Clave</th>
						<th>Concepto</th>
						<th>Importe</th>
					</tr>
	";
	if ($xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:deducciones']['nomina12:deduccion'][0]) {
		foreach($xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:deducciones']['nomina12:deduccion'] as $deduccion) {
			$html .= "
				<tr>
					<td>" . $deduccion['@attributes']['clave'] . ' ( ' . $deduccion['@attributes']['tipodeduccion'] . " )</td>
					<td>" . $deduccion['@attributes']['concepto'] . "</td>
					<td align='right'>" . $deduccion['@attributes']['importe'] . "</td>
				</tr>
			";
		}
	} else {
		$html .= "
			<tr>
				<td>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:deducciones']['nomina12:deduccion']['@attributes']['clave'] . ' ( ' . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:deducciones']['nomina12:deduccion']['@attributes']['tipodeduccion'] . " )</td>
				<td>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:deducciones']['nomina12:deduccion']['@attributes']['concepto'] . "</td>
				<td align='right'>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:deducciones']['nomina12:deduccion']['@attributes']['importe'] . "</td>
			</tr>
		";
	}
	
	$html .= "
		</table>
		</td>
		</tr>
		</table>
		<hr>
		<table>
			<tr>
				<td><b>Total sueldos: </b></td>
				<td>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:percepciones']['@attributes']['totalsueldos'] . "</td> 
				<td><b>Total gravado: </b></td>
				<td>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:percepciones']['@attributes']['totalgravado'] . "</td>
				<td><b>Total excento: </b></td>
				<td>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:percepciones']['@attributes']['totalexcento'] . "</td>
			</tr>
			<tr>
				<td><b>Total otras deducciones: </b></td>
				<td>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:deducciones']['@attributes']['totalotrasdeducciones'] . "</td>
				<td><b>Total impuestos retenidos: </b></td>
				<td>" . $xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:deducciones']['@attributes']['totalimpuestosretenidos'] . "</td>
			</tr>
		</table>
		<hr>
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
		Este documento es una representacion impresa de un CFDI v3.3 para Nomina v1.2
	";
		
		/*
					
						
		*/
		
		// echo $xmlFile['cfdi:comprobante']['@attributes']['fecha'];
			
		/* $html .= "
			<span class='titulo5'> EMPLEADO </span>
				<hr />
				<table>
					<tr>
						<td with='33%'>
							<b>Nombre: </b>" . $xmlFile['cfdi:Comprobante']['cfdi:Receptor']['@attributes']['nombre'] 
							. ' ( ' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['NumEmpleado'] . ' )' . '<br />
							<b>CURP: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['CURP'] . '<br />
							<b>RFC: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Receptor']['@attributes']['rfc'] . '<br />
							<b>NumSeguridadSocial: </b>' .  $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['NumSeguridadSocial'] . '<br />
							<b>FechaInicioRelLaboral: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['FechaInicioRelLaboral'] . '
						</td>
						<td with="33%">
							<b>Departamento: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['Departamento'] . '<br />
							<b>Puesto: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['Puesto'] . '<br />
							<b>TipoJornada: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['TipoJornada'] . '<br />
							<b>TipoRegimen: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['TipoRegimen'] . '<br />
							<b>TipoContrato: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['TipoContrato'] . '
						</td>
						<td with="33%">
							<b>Periodo: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['FechaInicialPago']
							. ' - ' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['FechaFinalPago'] . '<br />
							<b>PeriodicidadPago: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['PeriodicidadPago']
							. ' ( ' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['NumDiasPagados'] . ' )' . '<br />
							<b>FechaPago: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['FechaPago'] . '<br />
							<b>SalarioDiarioIntegrado: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['@attributes']['SalarioDiarioIntegrado'] . '<br />
							<b>SalarioDiario: </b>
						</td>
					</tr>
				</table>
				<hr />
				
	$qrStr = "?re=" . $xmlFile['cfdi:Comprobante']['cfdi:Emisor']['@attributes']['rfc'] . "&rr=" . $xmlFile['cfdi:Comprobante']['cfdi:Receptor']['@attributes']['rfc'] . "&tt=" . $xmlFile['cfdi:Comprobante']['@attributes']['total'] . "&id=" . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['UUID'];
	$fileName = $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['UUID'] . ".png";
	$pngPath = $DIR_QR . $fileName;
	if (!file_exists($pngPath)) {
		QRcode::png($qrStr, $pngPath);
	}
	
	$selloCFD = cortarCadenaLarga($xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['selloCFD'],110);
	$selloSAT = cortarCadenaLarga($xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['selloSAT'],110);
	
	$html.=	'
							</td>
						</table>
					</tr>
				</table>
				<hr />
				<table>
					<tr>
						<td rowspan="7">
							<img src="' . $pngPath . '">
						</td>
						<td width="40%"><b>formaDePago: </b>' . $xmlFile['cfdi:Comprobante']['@attributes']['formaDePago'] . '</td>
						<td width="20%" align="right"><b>Percepciones TotalGravado: </b></td>
						<td width="20%" align="right">' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['nomina:Percepciones']['@attributes']['TotalGravado'] . '</td>
					</tr>
					<tr>
						<td><b>metodoDePago: </b>' . $xmlFile['cfdi:Comprobante']['@attributes']['metodoDePago'] . '</td>
						<td align="right"><b>Percepciones TotalExento: </b></td>
						<td align="right">' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['nomina:Percepciones']['@attributes']['TotalExento'] . '</td>
					</tr>
					<tr>
						<td><b>Moneda: </b>' . $xmlFile['cfdi:Comprobante']['@attributes']['Moneda'] . '</td>
						<td align="right"><b>Deducciones TotalGravado: </b></td>
						<td align="right">' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['nomina:Deducciones']['@attributes']['TotalGravado'] . '</td>
					</tr>
					<tr>
						<td><b>TipoCambio: </b>' . $xmlFile['cfdi:Comprobante']['@attributes']['TipoCambio'] . '</td>
						<td align="right"><b>Deducciones TotalExento: </b></td>
						<td align="right">' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['nomina:Nomina']['nomina:Deducciones']['@attributes']['TotalExento'] . '</td>
					</tr>
					<tr>
						<td><b>noCertificado: </b>' . $xmlFile['cfdi:Comprobante']['@attributes']['noCertificado'] . '</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td><b>noCertificadoSAT: </b>' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['noCertificadoSAT'] . '</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td align="right"><b>Total: </b></td>
						<td align="right">' . $xmlFile['cfdi:Comprobante']['@attributes']['total'] . '</td>
					</tr>
				</table>
				<hr />
				<span class="titulo5">selloCFD </span>
				' . $selloCFD . '<br />
				<span class="titulo5">selloSAT </span>
				' . $selloSAT . '<br />
				<span class="titulo5">Cadena Original</span>
				||' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['version'] . '
				|' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['UUID'] . '
				|' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['FechaTimbrado'] . '
				|' . $selloCFD . '
				|' . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['noCertificadoSAT'] . '||
				<hr />
				<b>Este documento es una representación impresa de un CFDI</b>
			</body>
		</html>
	'; */
	
		 echo $html;
		
		/* echo "<pre>";
		print_r($xmlFile['cfdi:comprobante']['cfdi:complemento']['nomina12:nomina']['nomina12:percepciones']['nomina12:percepcion']);
		echo "</pre>"; */
		
		/* $dompdf=new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		file_put_contents($newPDFDir . $filePDFName . ".pdf", $dompdf->output()); */
		
		/* $zip->addFile($ZIP_PATH . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['UUID'] . ".pdf",$xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['UUID'] . ".pdf");
		
		unlink($ZIP_PATH . $xmlFile['cfdi:Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@attributes']['UUID'] . ".pdf");
		unlink($pngPath); */
	// }
	
	// $zip->close();
	
	/* header("Content-Type: application/zip");
	header("Content-Length: " . filesize($zipFile));
	header("Content-Disposition: attachment; filename=$zipName");
	header("Content-transfer-encoding: binary"); */
	
	// print file_get_contents($zipFile);
?>
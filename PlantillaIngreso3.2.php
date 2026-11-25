<?php
	session_start();
	include_once ("/php/lib/cDoctoCFDI.php");
	include_once ("/php/lib/phpqrcode.php");
	$doctoXML = new DoctoCFDI();
	$doctoXML->bd->setNombreBD($_SESSION['bd']);
	$uuid = $_GET['uuid'];
	header('Content-Type: text/html; charset=UTF-8');
	$datosXML = mysql_fetch_assoc($doctoXML->getInfoXML($uuid));
	
	$xmlFile = simplexml_load_string($datosXML['archivoXML']);
	$ns = $xmlFile->getNamespaces(true); 
	$xmlFile->registerXPathNamespace('cfdi', $ns['cfdi']);
	$xmlFile->registerXPathNamespace('tfd', $ns['tfd']);
	
	foreach ($xmlFile->xpath('//cfdi:Comprobante') as $cfdiComprobante){ 
		$fecha = $cfdiComprobante['fecha'];
		$serie = $cfdiComprobante['serie'];
		$folio = $cfdiComprobante['folio'];
		$total = $cfdiComprobante['total'];
		$subTotal = $cfdiComprobante['subTotal'];
		$LugarExpedicion = $cfdiComprobante['LugarExpedicion'];
		$NumCtaPago = $cfdiComprobante['NumCtaPago'];
		$certificado = $cfdiComprobante['certificado'];
		$formaDePago = $cfdiComprobante['formaDePago'];
		$metodoDePago = $cfdiComprobante['metodoDePago'];
		$noCertificado = $cfdiComprobante['noCertificado'];
		$sello = $cfdiComprobante['sello'];
	}
	foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
		$rfc = $Emisor['rfc'];
		$nombre = $Emisor['nombre'];
	}
	foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){
		$calle = $DomicilioFiscal['calle'];
		$codigoPostal = $DomicilioFiscal['codigoPostal'];
		$colonia = $DomicilioFiscal['colonia'];
		$estado = $DomicilioFiscal['estado'];
		$municipio = $DomicilioFiscal['municipio'];
		$noExterior = $DomicilioFiscal['noExterior'];
		$noInterior = $DomicilioFiscal['noInterior'];
		$pais = $DomicilioFiscal['pais'];
	}
	foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:RegimenFiscal') as $RegimenFiscal){
		$regimen = $RegimenFiscal['Regimen'];
	}
	foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
		$rfcReceptor = $Receptor['rfc'];
		$nombreReceptor = $Receptor['nombre'];
	}
	foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $Domicilio){
		$calleReceptor = $Domicilio['calle'];
		$noExterior = $Domicilio['noExterior'];
		$noInterior = $Domicilio['noInterior'];
		$codigoPostalReceptor = $Domicilio['codigoPostal'];
		$coloniaReceptor = $Domicilio['colonia'];
		$estadoReceptor = $Domicilio['estado'];
		$municipioReceptor = $Domicilio['municipio'];
		$paisReceptor = $Domicilio['pais'];
	}
	
	$i = 0;
	$conceptos = array();
	foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto){
		$conceptos[$i] = array(
			"unidad" => $Concepto['unidad'],
			"importe" => $Concepto['importe'],
			"cantidad" => $Concepto['cantidad'],
			"descripcion" => $Concepto['descripcion'],
			"valorUnitario" => $Concepto['valorUnitario']
		);
		$i++;
	}

	foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $Impuestos){ 
		$totalImpuestosTrasladados = $Impuestos['totalImpuestosTrasladados'];
	}
	foreach ($xmlFile->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){ 
		$importeImpuesto = $Traslado['importe'];
		$impuesto = $Traslado['impuesto'];
		$tasa = $Traslado['tasa'];
	}
	foreach ($xmlFile->xpath('//tfd:TimbreFiscalDigital') as $TimbreFiscalDigital) {
		$uuidBD = $TimbreFiscalDigital['UUID'];
		$fechaTimbrado = $TimbreFiscalDigital['fechaTimbrado'];
		$selloCFD = $TimbreFiscalDigital['selloCFD'];
		$selloSAT = $TimbreFiscalDigital['selloSAT'];
		$noCertificadoSAT = $TimbreFiscalDigital['noCertificadoSAT'];
	}
	
	$direccion = "";
	
	$direccion .= isset($calle) ? $calle . " " : "";
	$direccion .= isset($noExterior) ? $noExterior . " " : "";
	$direccion .= isset($noInterior) ? $noInterior : "";
	
	$str = explode(".",$total);
	$str1 = str_pad($str[0], 10, "0", STR_PAD_LEFT);
	$str2 = str_pad($str[1], 6, "0", STR_PAD_RIGHT);
	$totalqr = $str1 . "." . $str2;
	$qrStr = "?re=" . $rfc . "&rr=" . $rfcReceptor . "&tt=" . $totalqr . "&id=" . $uuid;
	$TMPDIR = "tmp/";
	$fileName = $uuidBD . ".png";
	$pngPath = $TMPDIR . $fileName;
	if (!file_exists($pngPath)) {
        QRcode::png($qrStr, $pngPath);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link type="text/css" href="css/jquery-ui.min.css" rel="stylesheet">
		<link type="text/css" href="css/representacionCFDI.css" rel="stylesheet">	
		<script src="js/jquery.js" language="javascript"></script>
		<script src="js/jquery-ui.min.js" language="javascript"></script>
		<script src="js/fnRepresentacionCFDI.js" language="javascript"></script>
	</head>
	<body onload="resizeTo(800,600);">
		<TABLE>
			<TR>
				<td width="15%" class="tdEncabezadoLogo">
					<img src="images/simboloAdmiracion.png" />
				</td>
				<TD width="60%" align="center" valign="top" class="titulo2">
					<span class="nomEmisor"><?php echo $nombre; ?></span>
					<HR />
						<?php echo $direccion; ?> <?php if(isset($colonia)){echo $colonia;} ?> C.P. <?php if(isset($codigoPostal)){echo $codigoPostal;}; ?> <BR />
						<?php if(isset($municipio)){echo $municipio;} ?>, <?php if(isset($estado)){echo $estado;} ?>, <?php if(isset($pais)){echo $pais;} ?> <BR />
						R.F.C. <?php if(isset($rfc)){echo $rfc;} ?> <br />
						<?php if(isset($regimen)){echo $regimen;} ?> <br />
				</TD>
				<td width="25%" align="center" valign="top" align="center">
					<b>Docto CFDI</b><br />
					<span class="titulo5"> Folio </span>
					<?php echo $serie; ?><?php echo $folio; ?> <br />
					<span class="titulo5"> Cert. Emisor </span>
					<?php echo $noCertificado; ?> <br />
					<span class="titulo5"> Folio Fiscal </span>
					<?php echo $uuidBD; ?> <br />
					<span class="titulo5"> Fecha </span>
					<?php echo $fecha; ?>
				</td>
			</TR>
		</TABLE>
		
		<p class="btnImprimir"><button id="btnImprimirRepresentacionCFDI">Imprimir</button></p>
		
		<span class="titulo">CLIENTE:</span>
		<TABLE style="border:1px solid black">
			<TR>
				<TD width="10%" colspan="2" class="CellStyleCaption">Nombre:</TD>
				<TD width="65%" colspan="9" ><?php echo $nombreReceptor; ?></TD>
				<TD width="7%" colspan="1" class="CellStyleCaption">RFC:</TD>
				<TD width="18%" colspan="4" ><?php echo $rfcReceptor; ?></TD>
			</TR>
			<TR>
				<TD width="10%" colspan="2" class="CellStyleCaption">Domicilio:</TD>
				<TD width="60%" colspan="9" ><?php if(isset($calleReceptor)){echo $calleReceptor;} ?></TD>
				<TD width="7%" colspan="1" class="CellStyleCaption">Colonia:</TD>
				<TD width="23%" colspan="4" ><?php if(isset($coloniaReceptor)){echo $coloniaReceptor;} ?></TD>
			</TR>
			<TR>
				<TD width="10%" colspan="2" class="CellStyleCaption"> </TD>
				<TD width="20%" colspan="4" > </TD>
				<TD width="20%" colspan="2" class="CellStyleCaption">Codigo postal:</TD>
				<TD width="20%" colspan="3" ><?php if(isset($codigoPostalReceptor)){echo $codigoPostalReceptor;} ?></TD>
				<TD width="7%" colspan="1" class="CellStyleCaption">Municipio:</TD>
				<TD width="23%" colspan="4" ><?php if(isset($municipioReceptor)){echo $municipioReceptor;} ?></TD>
			</TR>
			<TR>
				<TD width="10%" colspan="2" class="CellStyleCaption">Localidad:</TD>
				<TD width="20%" colspan="4" ><?php echo ""; ?></TD>
				<TD width="20%" colspan="2" class="CellStyleCaption">Estado:</TD>
				<TD width="20%" colspan="3" ><?php if(isset($estadoReceptor)){echo $estadoReceptor;} ?></TD>
				<TD width="7%" colspan="1" class="CellStyleCaption">Pais:</TD>
				<TD width="23%" colspan="4" ><?php if(isset($paisReceptor)){echo $paisReceptor;} ?></TD>
			</TR>
		</TABLE>
        <TABLE>
   			<TR>
				<td width="40%"><b>METODO PAGO: </b><?php echo $metodoDePago; ?></td>
   				<TD><b>No de Serie del Certificado del SAT: </b><?php echo $noCertificadoSAT; ?></TD>
   			</TR>
			<tr>
				<TD width="40%"><b>No. CUENTA: </b><?php echo $NumCtaPago ?></TD>
				<TD><b>Fecha y hora de certificacion: </b><?php echo $fecha; ?></td>
			</tr>
			<tr>
				<TD width="40%"><b>CONDICIONES: </b><?php echo ""; ?></TD>
				<td>
					<b>Lugar de expedicion: </b>
					<?php echo $LugarExpedicion; ?>
				</td>
			</tr>
        </TABLE>
		<div id="partidas">
			<TABLE>
				<TR>
					<TD width="12%" class="titulo4">Cantidad</TD>
					<TD width="8%" class="titulo4">Unidad</TD>
					<TD width="51%" class="titulo4">Descripcion</TD>
					<TD width="14%" class="titulo4">Valor Unitario</TD>
					<TD width="15%" class="titulo4">Importe</TD>
				</TR>
				<?php
					foreach ($conceptos as $concepto) {
						echo "<tr>";
						echo "<TD style='text-align:center' class='CellStyle'>" . $concepto['cantidad'] . "</TD>";
						echo "<TD class='CellStyle' style='text-align:center'>" . $concepto['unidad'] . "</TD>";
						echo "<TD>" . $concepto['descripcion'] . "</TD>";
						echo "<TD class='CellStyle'>" . $concepto['valorUnitario'] . "</TD>";
						echo "<TD class='CellStyle'>" . $concepto['importe'] . "</TD>";
						echo "</tr>";
					}
				?>
			</TABLE>
		</div>
		<TABLE>
			<tr>
				<td rowspan="7" style="width: 80%;">
					<span class="titulo">Importe con letra:</span>
					<i>IMPORTE CON LETRA AUN NO PREPARADO</i>
				</td>
			</tr>
			<TR>
				<TD class="titulo3">SubTotal</TD>
				<TD class="CellStyle"><?php echo $subTotal; ?></TD>
			</TR>
			<TR>
				<TD class="titulo3">IVA</TD>
				<TD class="CellStyle"><?php if(isset($importeImpuesto)){echo $importeImpuesto;} ?></TD>
			</TR>
			<TR>
				<TD class="titulo3">Total</TD>
				<TD class="CellStyle"><?php echo $total; ?></TD>
			</TR>
		</TABLE>
		Efectos fiscales al pago. <?php echo $formaDePago; ?>. Este documento es una impresion de un CFDI.<br />
		<table width="100%">
			<tr>
				<td rowspan="7">
					<img src="<?php echo $pngPath; ?>" />
				</td>
			</tr>	
			<TR>
				<TD class="tituloCadena">Sello digital del CFDI:</TD>
			</TR>
			<TR>
				<TD class="CellStyleCadena"><?php echo $sello; ?></TD>
			</TR>
			<TR>
				<TD class="tituloCadena">Sello del SAT</TD>
			</TR>
			<TR>
				<TD class="CellStyleCadena"><?php echo $selloSAT; ?></TD>
			</TR>
			<TR>
				<TD class="tituloCadena">
					Cadena original del complemento de certificacion digital del SAT
				</TD>
			</TR>
			<TR>
				<TD class="CellStyleCadena"><?php echo "||1.0|" . $uuidBD . "|" . $fecha . "|" . $selloCFD . "|" . $noCertificadoSAT . "||"; ?></TD>
			</TR>
		</table>
	</body>
</html>

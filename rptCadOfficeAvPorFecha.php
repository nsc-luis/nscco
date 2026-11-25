<?php
	session_start();
	include_once("php/lib/cInventarios.php");
	include_once("php/lib/cClientes.php");
	$inv = new Inventarios($_SESSION['bd']);
	// $inv->bd->setNombreBD($_SESSION['bd']);
	
	$inv->setIdCliente($_GET['idCliente']);
	$inv->setFechaCaducidad($_GET['fecha']);
	$datos = $inv->ConsultaCaducidadOfficeAVPorClienteyFecha();
	$inv->bd->cerrarBD();
	
	$cte = new Clientes($_SESSION['bd']);
	// $cte->bd->setNombreBD($_SESSION['bd']);
	
	$datosCte = $cte->getInfoCte($_GET['idCliente']);
	$cte->bd->cerrarBD();
	
	$html = "";
	$html .= '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<STYLE>
					body { font-size: 15px; font-family: Tahoma; }
					table { width: 100%; }
					.laTabla th { border: 3px solid black; }
					.laTabla td { border-bottom: 1px solid black; }
					.algDer { text-align: right; }
					.centrado { text-align: center; }
					.resaltado { font-weight: bold; }
					.empresa { font-size: 3m; }
					#botones { width: 100%; text-align: right; }
					@media print 
					{
						@page {
						  size: Letter; DIN A4 standard, Europe
						  margin: 0;
						}
						html, body {
							width: 210mm;
							height: 297mm;
							height: 282mm;
							font-size: 11px;
							background: #FFF;
							overflow:visible;
						}
						body {
							padding-top:15mm;
						}
					}
				</STYLE>
				<script>
					function imprimirPagina() {
						window.print();
					}
					function expToExcel() {
						alert("Disponible proximanente! :)");
					}
				</script>
			</head>
			<body>
				<TABLE>
					<TR>
						<td class="centrado">
							<img src="images/logo_nscco_mini.png" />
						</td>
						<TD>
							<span class="empresa resaltado">NETWORK SHARE COMPUTER</span>
							<HR />
							JOSUÉ PALACIOS GRIMALDO<BR />
							TELEFONOS: (81)83670772 NEXTEL: (81)80632298<BR />
							E-MAIL: josuepal@nscco.com.mx, nscco@prodigy.net.mx<BR />
							<span class="resaltado">REPORTE DE CADUCIDAD DE LICENCIAS DE MS OFFICE</span><br/>
							<span class="resaltado">CLIENTE: </span>
						</TD>
					</TR>
					
				</TABLE>
				<div id="botones">
					<button onclick="imprimirPagina()">Imprimir</button>
				</div>
				<table class="laTabla">
					<tr>
						<th>ID</th>
						<th>Tipo</th>
						<th>Nombre</th>
						<th>Host</th>
						<th>Observaciones</th>
						<th>Caducidad</th>
						<th>Sucursal</th>
					</tr>
	';
	// echo "<pre>";
	// print_r($datos);
	// echo "<pre>";
	foreach ($datos as $licencias){
		$html .= '
					<tr>
						<td><a href="#">' . $licencias['idInventario'] . '</a></td>
						<td>' . $licencias['tipoLicencia'] . '</td>
						<td>' . $licencias['nombre'] . '</td>
						<td>' . $licencias['host'] . '</td>
						<td>' . $licencias['Observaciones'] . '</td>
						<td>' . $licencias['fechacaducidad'] . '</td>
						<td>' . $licencias['sucursal'] . '</td>
					</tr>
		';
	}
	$html .= '</table>
			</body>
		</html>';
	echo $html;
?>



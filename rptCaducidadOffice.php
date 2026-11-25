<?php
	session_start();
	include_once("php/lib/cInventarios.php");
	include_once("php/lib/cClientes.php");
	
	$inv = new Inventarios($_SESSION['bd']);
	// $inv->bd->setNombreBD($_SESSION['bd']);
	$inv->setIdCliente($_GET['idCte']);
	$inv->setFechaRegistro($_GET['filtroFecha']);
	$datos = $inv->ConsultaCaducidadOfficePorCliente();
	$inv->bd->cerrarBD();
	
	$cte = new Clientes($_SESSION['bd']);
	// $cte->bd->setNombreBD($_SESSION['bd']);
	$datosCte = $cte->getInfoCte($_GET['idCte']);
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
						  size: Letter; /* DIN A4 standard, Europe */
						  margin: 10mm;
						}
						html, body {
							width: 210mm;
							/* height: 297mm; */
							height: 282mm;
							font-size: 11px;
							background: #FFF;
							overflow:visible;
						}
						body {
							padding-top:15mm;
						}
						thead {
							display: table-header-group;
						}
						#botones {
							display: none;
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
				<div id="botones">
					<button onclick="imprimirPagina()">Imprimir</button>
				</div>
				<table class="laTabla">
					<thead>
						<TR>
							<td colspan="7">
								<table>
									<TD class="centrado">
										<img src="images/logo_nscco_mini.png" />
									</TD>
									<TD>
										<span class="empresa resaltado">NETWORK SHARE COMPUTER</span>
										<HR />
										JOSUÉ PALACIOS GRIMALDO<BR />
										TELEFONOS: (81)83670772 MOVIL: (81)80632298<BR />
										E-MAIL: josuepal@nscco.com.mx, nscco@prodigy.net.mx<BR />
										<span class="resaltado">REPORTE DE CADUCIDAD DE LICENCIAS DE MS OFFICE</span><br/>
										<span class="resaltado">CLIENTE: </span>'.$datosCte[0]["nombre"].' <br />
										<span>Total de dispositivos: <span class="resaltado">' . count($datos) . '</span>
									</TD>
								</table>
							</td>
						</TR>
						<tr>
							<th>#</th>
							<th>Fecha registro</th>
							<th>Host (idInventario)</th>
							<th>TipoLicencia</th>
							<th>Descripcion (idLicencia)</th>
							<th>Sucursal</th>
							<th>Fecha caducidad</th>
							<th>Observaciones</th>
						</tr>
					</thead>
					<tbody>
	';
	$contador = 1;
	foreach ($datos as $licenciaOffice){
		$html .= '
					<tr>
						<td>'. $contador .'</td>
						<td>' . $licenciaOffice['fecharegistro'] . '</td>
						<td>' . $licenciaOffice['Host'] . ' (' . $licenciaOffice['id'] . ')</td>
						<td>' . $licenciaOffice['TipoLicencia'] . '</td>
						<td>' . $licenciaOffice['Descripcion'] . ' (' . $licenciaOffice['idLicencia'] . ')</td>
						<td>' . $licenciaOffice['sucursal'] . '</td>
						<td>' . $licenciaOffice['fechacaducidad'] . '</td>
						<td>' . $licenciaOffice['lsobs'] . '<br />' . $licenciaOffice['observaciones'] . '</td>
					</tr>
		';
		$contador = $contador + 1;
	}

	$html .= '</tbody></table>
			</body>
		</html>';
	echo $html;
?>
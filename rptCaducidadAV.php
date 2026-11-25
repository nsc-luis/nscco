<?php
	try {
		session_start();
		include_once("php/lib/cInventarios.php");
		include_once("php/lib/cClientes.php");
		$inv = new Inventarios($_SESSION['bd']);
		// $inv->bd->setNombreBD($_SESSION['bd']);
		
		$inv->setIdCliente($_GET['idCte']);
		$datos = $inv->ConsultaCaducidadAVPorCliente();
		$inv->bd->cerrarBD();
		
		$cte = new Clientes($_SESSION['bd']);
		// $cte->bd->setNombreBD($_SESSION['bd']);
		
		$datosCte = $cte->getInfoCte($_GET['idCte']);
		$cte->bd->cerrarBD();
		
		/* echo "<pre>";
		print_r($datos);
		echo "</pre>"; */
		
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
							  margin: 0;
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
						}
					</STYLE>
					<script>
						function imprimirPagina() {
							window.print();
						}
					</script>
					<link type="text/css" href="css/jquery-ui.min.css" rel="stylesheet">
					<link type="text/css" href="css/default.css" rel="stylesheet">
					<script src="js/jquery.js" language="javascript"></script>
					<script src="js/jquery-ui.min.js" language="javascript"></script>
					<script src="js/jquery.cookie.js" language="javascript"></script>
					<script src="js/angular.min.js" language="javascript"></script>
					<script src="js/fnRptAntivirusPorCliente.js" language="javascript"></script>
				</head>
				<body>
					<div ng-app="mainModuleExpAntivirusPorCliente">
						<div ng-controller="exportaCaducidadPorCliente">
							<TABLE>
								<TR>
									<td class="centrado">
										<img src="images/logo_nscco_mini.png" />
									</td>
									<TD>
										<span class="empresa resaltado">NETWORK SHARE COMPUTER</span>
										<HR />
										JOSUÉ PALACIOS GRIMALDO<BR />
										TELEFONO: (81)80632298<BR />
										E-MAIL: josuepal@nscco.com.mx, nscco@prodigy.net.mx<BR />
										<span class="resaltado">REPORTE DE CADUCIDAD DE LICENCIAS DE ANTIVIRUS</span><br/>
										<span class="resaltado">CLIENTE: </span>' . $datosCte[0]['nombre'] . '
									</TD>
								</TR>
								
							</TABLE>
							<div id="botones">
								<button id="btnExpAntivirusPorCliente" ng-click="expAntivirusPorCliente(' . $datosCte[0]['idCliente'] . ')">Exportar listado a Excel</button>
								<button id="btnImprimir" onclick="imprimirPagina()">Imprimir</button>
							</div>
							<table class="laTabla">
								<tr>
									<th>#</th>
									<th>ID</th>
									<th>Host</th>
									<!--
									<th>TipoLicencia</th>
									<th>idLicencia</th>
									-->
									<th>Descripcion</th>
									<th>Usuario</th>
									<!--
									<th>FechaActInv</th>
									-->
									<th>Fecha caducidad</th>
									<th>Sucursal</th>
									<th>Departamento</th>
									<th>Observaciones</th>
								</tr>
		';
		
		$contador = 1;
		foreach ($datos as $licenciaAV){
			$html .= '
								<tr>
									<td>' . $contador . '</td>
									<td><a href="#">' . $licenciaAV['id'] . '</a></td>
									<td>' . $licenciaAV['Host'] . '</td>
									<!--
									<td>' . $licenciaAV['TipoLicencia'] . '</td>
									<td>' . $licenciaAV['idLicencia'] . '</td>
									-->
									<td>' . $licenciaAV['Fabricante'] . '</td>
									<td>' . $licenciaAV['usuario'] . '</td>
									<!--
									<td>' . $licenciaAV['fecharegistro'] . '</td>
									-->
									<td>' . $licenciaAV['fechacaducidad'] . '</td>
									<td>' . $licenciaAV['Sucursal'] . '</td>
									<td>' . $licenciaAV['departamento'] . '</td>
									<td>' . $licenciaAV['observaciones'] . '</td>
									
								</tr>
			';
			$contador++;
		}
		
		$html .= '
							</table>
						</div>
					</div>
				</body>
			</html>';
		echo $html;
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
?>
<?php
	session_start();
	include_once("php/lib/cOrdenDeServicio.php");
	require_once("php/lib/dompdf/dompdf_config.inc.php");
	$ods = new OrdenDeServicio($_SESSION['bd']);
	// $ods->bd->setNombreBD($_SESSION['bd']);
	$ordDeServ = $ods->rptServxCte($_GET['cveCte'], $_GET['fInicial'], $_GET['fFinal']);
	// echo $json_info = json_encode($ordDeServ);
	$ods->bd->cerrarBD();
	$sumPartConMercancia = 0;
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
					function expToExcel() {
						alert("Disponible proximanente! :)");
					}
				</script>
			</head>
			<body>
				<TABLE>
					<!-- <TR>
						<td class="centrado">
							<img src="images/logo_nscco_mini.png" />
						</td>
						<TD>
							<span class="empresa resaltado">NETWORK SHARE COMPUTER</span>
							<HR />
							JOSUÉ PALACIOS GRIMALDO<BR />
							TELEFONOS: (81)83670772 NEXTEL: (81)80632298<BR />
							E-MAIL: josuepal@nscco.com.mx, nscco@prodigy.net.mx<BR />
						</TD>
					</TR> -->
					<tr>
						<td class="centrado">
							<img src="images/logo_nscco_mini.png" />
						</td>
						<td>
							<span class="resaltado">REPORTE DE SERVICIOS POR CLIENTE</span><BR />
							<span class="resaltado">CLIENTE: </span>' . $ordDeServ[0]['nombreCte'] . '<BR />
							<span class="resaltado">FECHAS: </span>' . $_GET['fInicial'] . ' Y ' . $_GET['fFinal'] . '
						</td>
					</tr>
				</TABLE>
				<div id="botones">
					<button onclick="expToExcel()">Exportar a Excel</button>
					<button onclick="imprimirPagina()">Imprimir</button>
				</div>
				<table class="laTabla">
					<tr>
						<th>Reporte</th>
						<th>Oficina</th>
						<th>Fecha</th>
						<th>Imorte</th>
						<th>Status</th>
						<th>Referencia</th>
						<th>Usuario</th>
					</tr>';
		
						for ($i = 0; $i < count($ordDeServ) - 4; $i++) {
							$html .= '
								<tr>
									<td><a href="imprimirDocto.php?folio=' . $ordDeServ[$i]['folio'] . '" target="_blank">' . $ordDeServ[$i]['folio'] . '</a></td>
									<td>' . $ordDeServ[$i]['nombreSucursal'] . '</td>
									<td>' . $ordDeServ[$i]['fechaOrdServ'] . '</td>
									<td class="algDer">' . $ordDeServ[$i]['importe'] . '</td>
									<td class="centrado">' . $ordDeServ[$i]['status'] . '</td>
									<td class="centrado">' . $ordDeServ[$i]['referenciaFact'] . '</td>
									<td>' . $ordDeServ[$i]['usuario'] . '</td>
								</tr>
							';
						}
		
			$html .= '
				</table>
				<br />
				<br />
				<br />
				<table class="laTabla">
					<tr>
						<th>Cantidad</th>
						<th>Descripcion</th>
						<th>P/Unitario</th>
						<th>Importe</th>
					</tr>
			';
			foreach($ordDeServ['partConMercancia'] as $ordConMercancia) {
				$html .= '
					<tr>
						<td class="centrado">' . $ordConMercancia['cantidad'] . '</td>
						<td>' . $ordConMercancia['nombre'] . '</td>
						<td class="algDer">' . $ordConMercancia['precioUnitario'] . '</td>
						<td class="algDer">' . number_format($ordConMercancia['importe'], 2) . '</td>
					</tr>
				';
				$sumPartConMercancia = $sumPartConMercancia + $ordConMercancia['importe'];
			}
			$html .= '
					<tr>
						<td class="centrado">1</td>
						<td>' . $ordDeServ['listaDeOrdenes'] . '</td>
						<td class="algDer">' . number_format($ordDeServ['sumaServicios'],2) . '</td>
						<td class="algDer">' . number_format($ordDeServ['sumaServicios'],2) . '</td>
					</tr>
			';
			if ($ordDeServ['extras']['HrsExtPresencial'] > 0) {
				$html .= '
					<tr>
						<td class="centrado">' . $ordDeServ['extras']['HrsExtPresencial'] . '</td>
						<td>HORAS EXTRA DE SERVICIO PRESENCIAL SEGUN POLIZA ESTABLECIDA</td>
						<td class="algDer">' . number_format($ordDeServ['extras']['importeHrsExtPresencial'] / $ordDeServ['extras']['HrsExtPresencial'],2) . '</td>
						<td class="algDer">' . number_format($ordDeServ['extras']['importeHrsExtPresencial'],2) . '</td>
					</tr>
				';
			}
			if ($ordDeServ['extras']['HrsExtRemoto'] > 0) {
				$html .= '
					<tr>
						<td class="centrado">' . $ordDeServ['extras']['HrsExtRemoto'] . '</td>
						<td>HORAS EXTRA DE SERVICIO REMOTO SEGUN POLIZA ESTABLECIDA</td>
						<td class="algDer">' . number_format($ordDeServ['extras']['importeHrsExtRemoto'] / $ordDeServ['extras']['HrsExtRemoto'],2) . '</td>
						<td class="algDer">' . number_format($ordDeServ['extras']['importeHrsExtRemoto'],2) . '</td>
					</tr>
				';
			}
			
			$html .= '
					<tr>
						<td></td>
						<td></td>
						<td class="resaltado">SUBTOTAL</td>
						<td class="algDer resaltado">' . number_format($sumPartConMercancia + $ordDeServ['extras']['importeHrsExtPresencial'] + $ordDeServ['extras']['importeHrsExtRemoto'] + $ordDeServ['sumaServicios'],2) . '</td>
					</tr>
				</table>
			</body>
		</html>
	';
	
	echo $html;
	// $dompdf=new DOMPDF();
	// $dompdf->load_html($html);
	// ini_set("memory_limit","128M");
	// $dompdf->render();
	// $dompdf->stream("TEST.pdf");
	/* echo "<pre>";
	print_r($ordDeServ);
	echo "</pre>"; */
?>



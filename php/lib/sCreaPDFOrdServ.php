<?php
	session_start();
	//chdir ("php/lib");
	//require_once("./dompdf/dompdf_config.inc.php");
	include_once ("./cClientes.php");
	include_once ("./cProductos.php");
	include_once ("./cOrdenDeServicio.php");
	include_once ("./cMovtosPartidas.php");
	include_once ("./cUsuarios.php");
	require_once ('../../vendor/autoload.php');
	
	$cte = new Clientes($_SESSION['bd']);
	$prod = new Productos($_SESSION['bd']);
	$ordServ = new OrdenDeServicio($_SESSION['bd']);
	$usr = new Usuarios($_SESSION['bd']);
	
	$folio = $_GET['folio'];
	// $bd = $_SESSION['bd'];
	
	// $ordServ->bd->setNombreBD($bd);
	$ordServ->getInfoOrdServ($folio);
	// $cte->getInfoCte($ordServ->getCveCte());
	$cte->getInfoCteByCveCte($ordServ->getCveCte());
	$cte->getInfoSucCte($ordServ->getIdSucursalCte());
	$usr->getInfoUsr($ordServ->getUsuario());
	
	$usuario = $ordServ->getUsuario();
	$idTipoDocto = $ordServ->getIdTipoDocto();
	
	$codigoHTML = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<link type="text/css" href="../../css/jquery-ui.min.css" rel="stylesheet">
				<link type="text/css" href="../../css/imprimirDocto.css" rel="stylesheet">
				<script src="js/jquery.js" language="javascript"></script>
				<script src="js/jquery-ui.min.js" language="javascript"></script>
				<script src="js/fnImprimirDocto.js" language="javascript"></script>
			</head>
			<body>
				<TABLE>
					<TR>
						<td class="tdEncabezadoLogo">
							<img src="../../images/logo_nscco_mini.png" />
						</td>
						<TD class="tdEncabezadoEmpresa">
							<span class="empresa">NETWORK SHARE COMPUTER</span>
							<HR />
								JOSUÉ PALACIOS GRIMALDO<BR />
								NAYARIT 147, COL. VIVIENDA POPULAR, GPE, NUEVO LEON<BR />
								R.F.C.: PAGJ690609HV7<BR />
								TELEFONOS: (81)83670772 NEXTEL: (81)80632298<BR />
								E-MAIL: nscco@prodigy.net.mx<BR />
						</TD>
						<td class="tdEncabezadoFolio">
							<span class="titulo"> Folio </span>'
							. $folio . '<br />
							<span class="titulo"> Fecha </span>'
							. $ordServ->getFechaOrdServ() .
						'</td>
					</TR>
				</TABLE>
				<TABLE>
					<tr>
						<td colspan="2" class="tituloCte">CLIENTE</td>
						<td colspan="2" class="tituloCte">SUCURSAL</td>
					</tr>
					<TR>
						<TD class="datosCte">Nombre:</TD>
						<TD>' . $cte->getNombre() . '</TD>
						<TD class="datosCte">Sucursal:</TD>
						<TD>' . $cte->getNomSucursal() . '</TD>
					</tr>
					<tr>
						<TD class="datosCte">Direccion:</TD>
						<TD>' . $cte->getDireccion() . '</TD>
						<TD class="datosCte">Direccion:</TD>
						<TD>' . $cte->getDirSucCte() . '</TD>
					</tr>
					<tr>
						<TD class="datosCte">Telefono:</TD>
						<TD>' . $cte->getTelefono1() . '</TD>
						<TD class="datosCte">Telefono:</TD>
						<TD>' . $cte->getTel1Suc() . '</TD>
					</TR>
					<tr>
						<TD class="datosCte">E-mail:</TD>
						<TD colspan="3">' . $cte->getEmail() . '</TD>
					</TR>
					<tr>
						<TD class="datosCte">R.F.C.:</TD>
						<TD colspan="3">' . $cte->getRfc() . '</TD>
					</TR>
					
				</TABLE>
				<div id="partidas">
					<TABLE>
						<TR>
							<Th>Cantidad</Th>
							<Th>Unidad</Th>
							<Th>Descripcion</Th>
							<Th>Valor Unitario</Th>
							<Th>Importe</Th>
						</TR>
	';
	$movtos = new MovtosPartidas($_SESSION['bd']);
	$partidasOrdServ = $movtos->movtosPartidasOrdServ($idTipoDocto, $folio, $usuario);
	$col = 0;
	$subTotal = 0;
	while ($p = $partidasOrdServ->fetch()) {
		$cantidad = $p['cantidad'];
		$cveProd = $p['cveProd'];
		$detalle = nl2br($p['detalle']);
		$precioUnitario = $p['precioUnitario'];
		$importePart = $p['importePart'];
		$prod->getInfoProd($cveProd);
		$nomProd = $prod->getNombre();
		$unidad = $prod->getMedida();
		$subTotal = $subTotal + $importePart;
		$importePart = sprintf("$ %01.2f", $importePart);
		$precioUnitario = sprintf("$ %01.2f", $precioUnitario);
		if ($col%2 == 0) {
			$codigoHTML .= '
						<tr>
							<TD class="tdCantidad">' . $cantidad . '</TD>
							<TD class="tdUnidad">' . $unidad . '</TD>
							<TD>' . $nomProd . '<BR /><i>' . $detalle . '</i></TD>
							<TD class="moneda">' . $precioUnitario . '</TD>
							<TD class="moneda">' . $importePart . '</TD>
						</TR>
			';
		} else {
			$codigoHTML .= '
						<tr>
							<TD class="tdCantidad2">' . $cantidad . '</TD>
							<TD class="tdUnidad2">' . $unidad . '</TD>
							<TD class="tdDesc2">' . $nomProd . '<BR /><i>' . $detalle . '</i></TD>
							<TD class="moneda2">' . $precioUnitario . '</TD>
							<TD class="moneda2">' . $importePart . '</TD>
						</TR>
			';
		}
		$col++;
	}
	$codigoHTML .= '
					</TABLE>
				</div>
				<table>
					<tr>
						<td class="importeLetra"></td>
						<td class="subTotal">Sub-Total: ' . sprintf("$ %01.2f", $subTotal) . '</td>
					</tr>
				</table>
				<br />
				<br />
				<table>
					<tr>
						<td class="tdTiempoObsUsr">Tiempo invertido:</td>
						<td>' . $ordServ->getTiempoInvertido() . ' horas.</td>
					</tr>
					<tr>
						<td class="tdTiempoObsUsr">Observaciones:</td>
						<td>' . nl2br($ordServ->getObservacion()) . '</td>
					</tr>
					<tr>
						<td class="tdTiempoObsUsr">Elaboro:</td>
						<td>' . $usr->getNombre() . '</td>
					</tr>
					<tr>
						<td class="tdTiempoObsUsr">Recibio/ordeno:</td>
						<td>' . $ordServ->getRecibidoPor() . '</td>
					</tr>
				</table>
			</body>
		</html>
	';
	// echo $codigoHTML;
	/* $dompdf=new DOMPDF();
	// $dompdf->set_option('enable_html5_parser', TRUE);
	$dompdf->load_html($codigoHTML);
	ini_set("memory_limit","128M");
	$dompdf->render();
	$dompdf->stream($folio . ".pdf"); */

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->WriteHTML($codigoHTML);
	ini_set("memory_limit","128M");
	$mpdf->Output($folio . ".pdf", 'D');
?>
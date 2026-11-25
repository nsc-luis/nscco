<?php
	session_start();
	chdir ("php/lib");
	include_once ("cClientes.php");
	include_once ("cProductos.php");
	include_once ("cOrdenDeServicio.php");
	include_once ("cMovtosPartidas.php");
	include_once ("cUsuarios.php");
	
	$movtos = new MovtosPartidas($_SESSION['bd']);
	$cte = new Clientes($_SESSION['bd']);
	$prod = new Productos($_SESSION['bd']);
	$ordServ = new OrdenDeServicio($_SESSION['bd']);
	$usr = new Usuarios($_SESSION['bd']);
	
	$folio = $_GET['folio'];
	// $bd = $_SESSION['bd'];
	
	// $ordServ->bd->setNombreBD($bd);
	$ordServ->getInfoOrdServ($folio);
	$cte->getInfoCteByCveCte($ordServ->getCveCte());
	$cte->getInfoSucCte($ordServ->getIdSucursalCte());
	$usr->getInfoUsr($ordServ->getUsuario());
	
	$usuario = $ordServ->getUsuario();
	$idTipoDocto = $ordServ->getIdTipoDocto();
	
	echo $ordServ->getCveCte();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link type="text/css" href="css/jquery-ui.min.css" rel="stylesheet">
		<link type="text/css" href="css/imprimirDocto.css" rel="stylesheet">
		<script src="js/jquery.js" language="javascript"></script>
		<script src="js/jquery-ui.min.js" language="javascript"></script>
		<script src="js/fnImprimirDocto.js" language="javascript"></script>
	</head>
	<body onload="resizeTo(800,600);">
		<TABLE>
			<TR>
				<td class="tdEncabezadoLogo">
					<img src="images/logo_nscco_mini.png" />
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
					<span class="titulo"> Folio </span>
					<?php if(isset($folio)){echo $folio;} ?> <br />
					<span class="titulo"> Fecha </span>
					<?php echo $ordServ->getFechaOrdServ(); ?>
				</td>
			</TR>
		</TABLE>
		
		<p class="btnImprimir"><button id="btnImprimirDocto">Imprimir</button></p>

		<TABLE>
			<tr>
				<td colspan="2" class="tituloCte">CLIENTE</td>
				<td colspan="2" class="tituloCte">SUCURSAL</td>
			</tr>
			<TR>
				<TD class="datosCte">Nombre:</TD>
				<TD><?php echo $cte->getNombre(); ?></TD>
				<TD class="datosCte">Sucursal:</TD>
				<TD><?php echo $cte->getNomSucursal(); ?></TD>
			</tr>
			<tr>
				<TD class="datosCte">Direccion:</TD>
				<TD><?php echo $cte->getDireccion(); ?></TD>
				<TD class="datosCte">Direccion:</TD>
				<TD><?php echo $cte->getDirSucCte(); ?></TD>
			</tr>
			<tr>
				<TD class="datosCte">Telefono:</TD>
				<TD><?php echo $cte->getTelefono1(); ?></TD>
				<TD class="datosCte">Telefono:</TD>
				<TD><?php echo $cte->getTel1Suc(); ?></TD>
			</TR>
			<tr>
				<TD class="datosCte">E-mail:</TD>
				<TD colspan="3"><?php echo $cte->getEmail(); ?></TD>
			</TR>
			<tr>
				<TD class="datosCte">R.F.C.:</TD>
				<TD colspan="3"><?php echo $cte->getRfc(); ?></TD>
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
				<?php
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
							echo "
								<tr>
									<TD class='tdCantUni'>$cantidad</TD>
									<TD class='tdCantUni'>$unidad</TD>
									<TD>$nomProd<BR /><i>$detalle</i></TD>
									<TD class='moneda'>$precioUnitario</TD>
									<TD class='moneda'>$importePart</TD>
								</TR>
							";
						} else {
							echo "
								<tr>
									<TD class='tdCantUni'>$cantidad</TD>
									<TD class='tdCantUni'>$unidad</TD>
									<TD>$nomProd<BR /><i>$detalle</i></TD>
									<TD class='moneda'>$precioUnitario</TD>
									<TD class='moneda'>$importePart</TD>
								</TR>
							";
						}
						$col++;
					}
				?>
			</TABLE>
		</div>
		<TABLE>
			<tr>
				<td style="width: 80%;">
					<!-- <span class="titulo2">Importe con letra:</span>
					<i><span class="importeEnLetra"></span></i> -->
				</td>
				<TD class="subTotal">Sub-Total: <?php if(isset($subTotal)){echo sprintf("$ %01.2f", $subTotal);} ?></TD>
			</tr>
		</TABLE>
		<br />
		<br />
		<table>
			<tr>
				<td class="tdTiempoObsUsr">Tiempo invertido:</td>
				<td><?php print $ordServ->getTiempoInvertido(); ?> horas.</td>
			</tr>
			<tr>
				<td class="tdTiempoObsUsr">Observaciones:</td>
				<td><?php print nl2br($ordServ->getObservacion()); ?></td>
			</tr>
			<tr>
				<td class="tdTiempoObsUsr">Elaboro:</td>
				<td><?php echo $usr->getNombre(); ?></td>
			</tr>
			<tr>
				<td class="tdTiempoObsUsr">Recibio/ordeno:</td>
				<td><?php echo $ordServ->getRecibidoPor(); ?></td>
			</tr>
		</table>
	</body>
</html>

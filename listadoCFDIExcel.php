<?php
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=Reporte_Exportado_CFDI.xls");
	session_start();
	chdir("php/lib");
	include_once ("cDoctoCFDI.php");
	include_once ("cProveedores.php");
	$cfdi = new DoctoCFDI();
	$proveedor = new Proveedores();
	$bd = $_SESSION['bd'];
	$cfdi->bd->setNombreBD($bd);
	$proveedor->bd->setNombreBD($bd);
	
	$rfcProvFiltro = $_GET['rfcProvFiltro'];
	$fecha = $_GET['fecha'];
	$fTipoDocto = $_GET['fTipoDocto'];
	$fUUID = $_GET['fUUID'];
	$foo = split("-", $fecha);
	$bar = $foo[1] == "todos" ? $foo[0] : $fecha;
	$indiceMeses = array("01","02","03","04","05","06","07","08","09","10","11","12");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octrubre","Noviembre","Diciembre");
	
	$totalesGenerales = $cfdi->cfdiTotalGeneral($rfcProvFiltro,$bar,$fTipoDocto);
	while ($row = mysql_fetch_assoc($totalesGenerales)) {
		$subTotalGral = $row['subTotalGral'];
		$impuestoGral = $row['impuestoGral'];
		$totalGral = $row['totalGral'];
		$subTotalGral = sprintf('$ %01.2f', $subTotalGral);
		$impuestoGral = sprintf('$ %01.2f', $impuestoGral);
		$totalGral = sprintf('$ %01.2f', $totalGral);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script src="js/jquery.js" language="javascript"></script>
		<script src="js/jquery-ui.min.js" language="javascript"></script>
		<script src="js/fnModCFDI.js" language="javascript"></script>
		<style>
			.registroPar { background-color: #F2F2F2; }
			.totales { font-weight: bold; border-top: 1px solid black; }
		</style>
	</head>
	<body>
		<table>
			<tr>
				<th></th>
				<th>SubTotal</th>
				<th>Impuestos</th>
				<th>Total</th>
			</tr>
			<?php
				if ($foo[1] == "todos") {
					for ($i=0; $i<12; $i++) {
						$mes = $foo[0] . "-" . $indiceMeses[$i];
						$totalMensual = $cfdi->cfdiTotalMensual($rfcProvFiltro,$mes,$fTipoDocto);
						while ($row = mysql_fetch_array($totalMensual)) {
							$subTotalMes = $row['subTotalMes'];
							$impuestoMes = $row['impuestoMes'];
							$totalMes = $row['totalMes'];
							$subTotalMes = sprintf('$ %01.2f', $subTotalMes);
							$impuestoMes = sprintf('$ %01.2f', $impuestoMes);
							$totalMes = sprintf('$ %01.2f', $totalMes);
						}
						echo "
							<tr>
								<td>$meses[$i]</td>
								<td class='moneda'>$subTotalMes</td>
								<td class='moneda'>$impuestoMes</td>
								<td class='moneda'>$totalMes</td>
							</tr>
						";
					}
				} else {
					$totalMensual = $cfdi->cfdiTotalMensual($rfcProvFiltro,$fecha,$fTipoDocto);
					$mes = array_keys($indiceMeses, $foo[1]);
					$x = $meses[$mes[0]];
					while ($row = mysql_fetch_array($totalMensual)) {
						$subTotalMes = $row['subTotalMes'];
						$impuestoMes = $row['impuestoMes'];
						$totalMes = $row['totalMes'];
						$subTotalMes = sprintf('$ %01.2f', $subTotalMes);
						$impuestoMes = sprintf('$ %01.2f', $impuestoMes);
						$totalMes = sprintf('$ %01.2f', $totalMes);
					}
					echo "
						<tr>
							<td>$x</td>
							<td class='moneda'>$subTotalMes</td>
							<td class='moneda'>$impuestoMes</td>
							<td class='moneda'>$totalMes</td>
						</tr>
					";
				}
			?>
			<tr>
				<td class="totales"><strong>Totales</strong></td>
				<td class="totales moneda"><?php echo $subTotalGral; ?></td>
				<td class="totales moneda"><?php echo $impuestoGral; ?></td>
				<td class="totales moneda"><?php echo $totalGral; ?></td>
			</tr>
		</table>
		<hr />
		<table>
			<tr>
				<th>Fecha</th>
				<th>Folio</th>
				<th>Razon social</th>
				<th>SubTotal</th>
				<th>Impuesto</th>
				<th>Importe</th>
				<th>TipoDocto</th>
				<th>UUID</th>
			</tr>
		<?php
			$par = 0;
			$xmls = $cfdi->cfdis($rfcProvFiltro,$bar,/*$fUUID,*/$fTipoDocto);
			while ($xml = mysql_fetch_array($xmls)) {
				$folioXML = $xml['folioXML'];
				$idTipoDocto = $xml['nombre'];
				$razonSocialXML = $xml['razonSocialXML'];
				$fechaXML = $xml['fechaXML'];
				$subTotalXML = $xml['subTotalXML'];
				$impuestoXML = $xml['impuestoXML'];
				$totalXML = $xml['totalXML'];
				$uuid = $xml['uuid'];
				// $archivoXML = $xml['archivoXML'];
				$subTotalXML = sprintf('$ %01.2f', $subTotalXML);
				$impuestoXML = sprintf('$ %01.2f', $impuestoXML);
				$totalXML = sprintf('$ %01.2f', $totalXML);
				if ($par%2 == 0) {
		?>
		<tr>
			<td class="registroPar"><?php echo $fechaXML; ?></td>
			<td class="registroPar"><?php echo $folioXML; ?></td>
			<td class="registroPar"><?php echo $razonSocialXML; ?></td>
			<td class='registroPar moneda'><?php echo $subTotalXML; ?></td>
			<td class='registroPar moneda'><?php echo $impuestoXML; ?></td>
			<td class='registroPar moneda'><?php echo $totalXML; ?></td>
			<td class="registroPar"><?php echo $idTipoDocto; ?></td>
			<td class="registroPar"><?php echo $uuid; ?></td>
		</tr>
		<?php } else { ?>
		<tr>
			<td><?php echo $fechaXML; ?></td>
			<td><?php echo $folioXML; ?></td>
			<td><?php echo $razonSocialXML; ?></td>
			<td class='moneda'><?php echo $subTotalXML; ?></td>
			<td class='moneda'><?php echo $impuestoXML; ?></td>
			<td class='moneda'><?php echo $totalXML; ?></td>
			<td><?php echo $idTipoDocto; ?></td>
			<td><?php echo $uuid; ?></td>
		</tr>
		<?php } $par = $par + 1; } ?>
	</body>
</html>
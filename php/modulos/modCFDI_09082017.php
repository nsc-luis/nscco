<?php
	chdir("php/lib");
	include_once ("cDoctoCFDI.php");
	include_once ("cProveedores.php");
	$cfdi = new DoctoCFDI();
	$proveedor = new Proveedores();
	$bd = $_SESSION['bd'];
	$cfdi->bd->setNombreBD($bd);
	$proveedor->bd->setNombreBD($bd);
	
	$rfcProvFiltro = isset($_GET['rfcProvFiltro']) ? $_GET['rfcProvFiltro'] : "";
	if (isset($_GET['fecha'])) { 
		$fecha = $_GET['fecha'];
	} else {
		$fecha = date("Y") . "-" . date("m");
	}
	$fUUID = isset($_GET['fUUID']) ? $_GET['fUUID'] : "";
	$fTipoDocto = isset($_GET['fTipoDocto']) ? $_GET['fTipoDocto'] : "";
	$yearMonth = split("-",$fecha);
	$years = array(date("Y")-3,date("Y")-2,date("Y")-1,date("Y"));
?>
<script src="js/fnModCFDI.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Documentos CFDI (.xml)</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php echo $_SESSION['empresa']; ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php echo $_SESSION['nombre']; ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft">
	<label for="archivoXML">Seleccione docto(s) XML:</label>
	<input type="file" id="archivoXML" multiple />
	<button id="btnCargaXML" >Cargar documentos</button>
	<div id="result"></div>
	<hr />
	<fieldset>
		<legend>F I  L T O</legend>
		<label for="rfcProveedor">Nombre proveedor: (*)</label>
		<select id="rfcProvFiltro">
			<option value="0">Seleccione un proveedor</option>
			<?php
				$proveedores = $proveedor->proveedores();
				while ($row = mysql_fetch_array($proveedores)) {
					$rfcProveedor = $row['rfcProveedor'];
					$nombreProveedor = $row['nombreProveedor'];
					if ( $rfcProvFiltro == $rfcProveedor ) {
						echo "<option value='$rfcProveedor' selected>$nombreProveedor</option>";
					} else {
						echo "<option value='$rfcProveedor'>$nombreProveedor</option>";
					}
				}
			?>
		</select> <br/ >
		<label for="fYear-fMes">Año/Mes: </label>
		<select id="fYear">
			<?php
				foreach ($years as $year) {
					if ($year == $yearMonth[0]) {
						echo "<option value='$year' selected>$year</option>";
					} else {
						echo "<option value='$year'>$year</option>";
					}
				}
			?>
		</select>
		<select id="fMes">
			<option value="todos">Todos</option>
			<option value="01" <?php if ($yearMonth[1] == "01") { echo "selected"; } ?>>Enero</option>
			<option value="02" <?php if ($yearMonth[1] == "02") { echo "selected"; } ?>>Febrero</option>
			<option value="03" <?php if ($yearMonth[1] == "03") { echo "selected"; } ?>>Marzo</option>
			<option value="04" <?php if ($yearMonth[1] == "04") { echo "selected"; } ?>>Abril</option>
			<option value="05" <?php if ($yearMonth[1] == "05") { echo "selected"; } ?>>Mayo</option>
			<option value="06" <?php if ($yearMonth[1] == "06") { echo "selected"; } ?>>Junio</option>
			<option value="07" <?php if ($yearMonth[1] == "07") { echo "selected"; } ?>>Julio</option>
			<option value="08" <?php if ($yearMonth[1] == "08") { echo "selected"; } ?>>Agosto</option>
			<option value="09" <?php if ($yearMonth[1] == "09") { echo "selected"; } ?>>Septiembre</option>
			<option value="10" <?php if ($yearMonth[1] == "10") { echo "selected"; } ?>>Octubre</option>
			<option value="11" <?php if ($yearMonth[1] == "11") { echo "selected"; } ?>>Noviembre</option>
			<option value="12" <?php if ($yearMonth[1] == "12") { echo "selected"; } ?>>Diciembre</option>
		</select> <br/ >
		<label for="fUUID">UUID: </label>
		<input type="text" id="fUUID" validar="uuid" />Ej.: 4f382e44-95bf-4705-9641-1363740d0c4e <br />
		<label for="fTipoDocto">Tipo de documento: </label>
		<select id="fTipoDocto">
			<option value="0">Todos</option>
			<option value="3" <?php if ($fTipoDocto == "3") { echo "selected"; } ?>>Ingreso</option>
			<option value="4" <?php if ($fTipoDocto == "4") { echo "selected"; } ?>>Egreso</option>
		</select> <br />
		<span class="fright">
		<button id="filtroCFDI">Filtrar</button>
		<button id="btnExpExcelCFDI" >Exportar a Excel</button>
		</span>
	</fieldset>
	<div id="listaCFDI">
		<table>
			<tr>
				<th>Folio</th>
				<th>Razon social</th>
				<th>Fecha</th>
				<th>Importe</th>
				<th>TipoDocto</th>
				<th></th>
				<th></th>
			</tr>
			<?php
				$par = 0;
				$xmls = $cfdi->cfdis($rfcProvFiltro,$fecha,$fUUID,$fTipoDocto);
				while ($xml = mysql_fetch_array($xmls)) {
					$folioXML = $xml['folioXML'];
					$idTipoDocto = $xml['nombre'];
					$razonSocialXML = $xml['razonSocialXML'];
					$fechaXML = $xml['fechaXML'];
					$totalXML = $xml['totalXML'];
					$uuid = $xml['uuid'];
					$archivoXML = $xml['archivoXML'];
					$totalXML = sprintf("$ %01.2f", $totalXML);
					if ($par%2 == 0) {
						echo "
							<tr>
								<td class='tdModXMLFolio'>$folioXML</td>
								<td class='tdModXMLRazon'>$razonSocialXML</td>
								<td class='tdModXMLFecha'>$fechaXML</td>
								<td class='tdModXMLTotal moneda'>$totalXML</td>
								<td class='tdModXMLTipoDocto'>$idTipoDocto</td>
								<td class='tdModXMLBtn'>
									<a href='php/lib/sDownloadXMl.php?uuid=$uuid' title='Descargar CFDI'>
										<span class='ui-state-default ui-corner-all ui-icon ui-icon-extlink'></span>
									</a>
								</td>
								<td class='tdModXMLBtn'>
									<a href='#' class='$uuid representacionCFDI' title='Representacion impresa de CFDI'>
										<span class='ui-state-default ui-corner-all ui-icon ui-icon-script'></span>
									</a>
								</td>
							</tr>
						";
					} else {
						echo "
							<tr>
								<td class='tdModXMLFolio2'>$folioXML</td>
								<td class='tdModXMLRazon2'>$razonSocialXML</td>
								<td class='tdModXMLFecha2'>$fechaXML</td>
								<td class='tdModXMLTotal2 moneda'>$totalXML</td>
								<td class='tdModXMLTipoDocto2'>$idTipoDocto</td>
								<td class='tdModXMLBtn2'>
									<a href='php/lib/sDownloadXMl.php?uuid=$uuid' title='Descargar CFDI'>
										<span class='ui-state-default ui-corner-all ui-icon ui-icon-extlink'></span>
									</a>
								</td>
								<td class='tdModXMLBtn2'>
									<a href='#' class='$uuid representacionCFDI' title='Represintacion impresa de CFDI'>
										<span class='ui-state-default ui-corner-all ui-icon ui-icon-script'></span>
									</a>
								</td>
							</tr>
						";
					}
					$par = $par + 1;
				}
			?>
		</table>
	</div>
</div>
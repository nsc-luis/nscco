<?php
	chdir("php/lib");
	/* include_once ("cClientes.php");
	include_once ("cOrdenDeServicio.php");
	$ctes = new Clientes();
	$ordDeServ = new OrdenDeServicio();
	$ctes->bd->setNombreBD($_SESSION['bd']);
	$listaClientes = $ctes->clientes(); */
?>
<script src="js/fnModBackup.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Respaldos de base de datos</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php if(isset($_SESSION['empresa'])){echo $_SESSION['empresa'];} ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php if(isset($_SESSION['nombre'])){echo $_SESSION['nombre'];} ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft">
	<button id="btnNvoRespaldo">Generar nuevo respaldo</button>
	<hr>
	<table>
		<tr>
			<th>Respaldos disponibles</th>
			<th></th>
		</tr>
		<?php
			$dirResp = opendir("../../respBD");
			$par = 0;
			while ($archivo = readdir($dirResp)) {
				if (!is_dir($archivo)) {
					if ($par%2 == 0) {
						echo "<tr><td>$archivo</td>
							<td><button class='$archivo btnRestauraBkp'>Restaurar respaldo</button>
							</td></tr>
						";
					} else {
						echo "<tr><td class='modTdBkp'>$archivo</td>
							<td class='modTdBkp'><button class='$archivo btnRestauraBkp'>Restaurar respaldo</button>
							</td></tr>
						";
					}
				}
				$par = $par + 1;
			}
		?>
	</table>
	<div id="frmBackup" title="Nuevo respaldo">
		<label for="nomBackup">Nombre del archivo:*</label>
		<input type="text"id="nomBackup" validar="clave" /><br />
		<span class="avisoFrm">(*) Campos requeridos.</span>
		<hr>
		Al nombre del archivo se agregara la fecha y hora para mejor identificación.
		<br/>Ej: <i>NombreDelArchivo</i>_2015-01-01T08-30-59.zip
	</div>
	<div id="result"></div>
</div>
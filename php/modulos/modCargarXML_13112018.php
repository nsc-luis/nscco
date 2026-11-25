<?php
	chdir("php/lib");
	//include_once ("cDoctoCFDI.php");
	//$cfdis = new DoctoCFDI();
?>
<script src="js/fnModCFDI.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Documentos CFDI</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php echo $_SESSION['empresa']; ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php echo $_SESSION['nombre']; ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft">
	<label for="btnCargaXML">Seleccione docto(s) XML:</label>
	<input type="file" id="btnCargaXML1" multiple /><br />
	<div id="frmCargaXML" title="Carga de XMLs">
		<hr>
		Solo podra cargar documentos XML del CFDI para la empresa seleccionada
		(solo archivos .xml).
	</div>
</div>
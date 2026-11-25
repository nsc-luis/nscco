<script src="js/verActSistPaq.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Versiones del sistema de paqueteria</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php echo $_SESSION['empresa']; ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php echo $_SESSION['nombre']; ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<!-- <div id="contenido" class="fleft">
	<script type="text/javascript" charset="iso-8859-1" language="javascript" src="http://www.proverbia.net/webmaster/frase.asp?op=0&amp;ref="></script>
	<p class="cita">
	</p> <br />
	<p class="autorcita"></p>
</div> -->

<div ng-controller="verActSistPaq">
	Cargar comprimido: 
	<input type="file" id="archivoRar" /> <br />
	<button id="btnCargarRar">Cargar</button>
</div>

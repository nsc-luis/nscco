		<style>
			label { display: block; width: 180px; float: left; background-color: #F2F2F2; line-height: 20px; margin-right: 10px; margin-top: 5px;}
			input { margin-top: 5px; }
		</style>
		<div id="infoSesion">
			<span class="fleft infoVentana">Cotizaciones</span>
			<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
			<span class="fright"><strong>Empresa:</strong> <?php echo $_SESSION['empresa']; ?></span><br />
			<span class="fright"><strong>Usuario:</strong> <?php echo $_SESSION['nombre']; ?></span>
		</div>
		<div id="barraLateral" class="fleft">
			<strong>TAREAS ASIGNADAS.</strong>
		</div>
		<div id="contenido" class="fleft">
			DISPONIBLE PROXIMAMENTE.
		</div>
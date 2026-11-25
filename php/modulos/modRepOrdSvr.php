		<style>
			label { display: block; width: 180px; float: left; background-color: #F2F2F2; line-height: 20px; margin-right: 10px; margin-top: 5px;}
			input { margin-top: 5px; }
		</style>
		<div id="infoSesion">
			<span class="fleft infoVentana">Listado de ordenes de servicio</span>
			<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
			<span class="fright"><strong>Empresa:</strong> <?php echo $_SESSION['empresa']; ?></span><br />
			<span class="fright"><strong>Usuario:</strong> <?php echo $_SESSION['nombre']; ?></span>
		</div>
		<div id="barraLateral" class="fleft">
			<strong>TAREAS ASIGNADAS.</strong>
		</div>
		<div id="contenido" class="fleft">
			<div id="datosEmpresa" title="Datos de la nueva empresa">
				<label>Nombre:</label>
				<input type="text" id="nombre" maxlength=50 /> <br />
				<label>RFC:</label>
				<input type="text" id="rfc" maxlength=13 /> <br />
				<label>Calle:</label>
				<input type="text" id="calle" maxlength=25 /> <br />
				<label>Numero:</label>
				<input type="text" id="noExt" maxlength=15 /> <br />
				<label>Colonia:</label>
				<input type="text" id="colonia" maxlength=25 /> <br />
				<label>Municipio:</label>
				<input type="text" id="municipio" maxlength=25 /> <br />
				<label>Estado:</label>
				<input type="text" id="estado" maxlength=25 /> <br />
				<label>Pais:</label>
				<input type="text" id="pais" maxlength=15 /> <br />
				<label>Codigo postal:</label>
				<input type="text" id="cp" maxlength=9 /> <br />
				<label>Telefono1:</label>
				<input type="text" id="telefono1" maxlength=10 /> <br />
				<label>Nombre base de datos:</label>
				<input type="text" id="baseDatos" maxlength=15 /> <br />
				<p class="centrado"><button type="button" id="guardarEmpresa">Guardar</button></p>
				<div id="msjIngreso" class="centrado">
				</div>
			</div>
			<button id="nvaEmpresa">Nueva empresa</button>
			
		</div>
<script src="js/fnModTipoDisp.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Catalogo de tipos de dispositivo</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php if(isset($_SESSION['empresa'])){echo $_SESSION['empresa'];} ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php if(isset($_SESSION['nombre'])){echo $_SESSION['nombre'];} ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft" ng-controller="catTipoDispositivo">
	<button id="btnNvoTipoDisp" ng-click="dlgNvoTipoDisp()" >Nuevo tipo de dispositivo</button> <br /> 
	<div id="msjTipoDisp"></div>
	<table class="modTb" style="width:50%">
		<tr>
			<th>ID</th>
			<th>Tipo de dispositivo</th>
			<th></th>
		</tr>
		<tr class="trBD" ng-repeat="disp in listaTipoDispositivo">
			<td class="centrado">{{disp.idTipoDisp}}</td>
			<td>{{disp.nombre}}</td>
			<td style="text-align: center">
				<button title="Editar dispositivo" ng-click="dlgInfoTipoDisp(disp)" ><span class="ui-icon ui-icon-pencil"></span></button>
				<button title="Eliminar dispositivo" ng-click="dlgBorrarDisp(disp)"><span class="ui-icon ui-icon-trash"></span></button>
			</td>
		</tr>
	</table>
	<div id="dlgFrmTipoDisp" title="Nombre tipo de dispositivo">
		<label>Nombre: (*)</label>
		<input type="text" placeholder="ej.: Nombre tipo de dispoitivo" ng-model="currentDisp.nombre" value="{{currentDisp.nombre}}"/>
		<hr />
		<button id="btnGuardarNvoTipoDisp" ng-click="guardarNvoTipoDisp(currentDisp)">Guardar</button>
		<button id="btnGuardarTipoDisp" ng-click="guardarTipoDisp(currentDisp)">Guardar</button>
	</div>
	<div id="dlgBorrarDisp" title="Borrar tipo de dispositivo">
		Solo se permiten borrar tipos de dispositivo que no tengan movimientos. <br />
		¿Deseas continuar borrando el tipo de dispositivo: <strong>{{currentDispBorrar.nombre}}</strong>?
		<hr />
		<button id="confirmaBorrar" ng-click="borrarTipoDisp(currentDispBorrar)" >SI</button>
	</div>
</div>

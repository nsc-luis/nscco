<script src="js/fnModTipoLic.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Catalogo de tipos de licencia</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php if(isset($_SESSION['empresa'])){echo $_SESSION['empresa'];} ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php if(isset($_SESSION['nombre'])){echo $_SESSION['nombre'];} ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft" ng-controller="catTipoLicencia">
	<button id="btnNvoTipoLic" ng-click="dlgNvoTipoLic()" >Nuevo tipo de licencia</button> <br /> 
	<div id="msjTipoLic"></div>
	<table class="modTb" style="width:50%">
		<tr>
			<th>ID</th>
			<th>Tipo de licencia</th>
			<th></th>
		</tr>
		<tr class="trBD" ng-repeat="lic in listaTipoLicencia">
			<td class="centrado">{{lic.idtipolicencia}}</td>
			<td>{{lic.nombre}}</td>
			<td style="text-align: center">
				<button title="Editar licencia" ng-click="dlgInfoTipoLic(lic)" ><span class="ui-icon ui-icon-pencil"></span></button>
				<button title="Eliminar licencia" ng-click="dlgBorrarLic(lic)"><span class="ui-icon ui-icon-trash"></span></button>
			</td>
		</tr>
	</table>
	<div id="dlgFrmTipoLic" title="Nombre tipo de licencia">
		<label>Nombre: (*)</label>
		<input type="text" placeholder="ej.: Nombre tipo de licencia" ng-model="currentLic.nombre" value="{{currentLic.nombre}}"/>
		<hr />
		<button id="btnGuardarNvoTipoLic" ng-click="guardarNvoTipoLic(currentLic)">Guardar</button>
		<button id="btnGuardarTipoLic" ng-click="guardarTipoLic(currentLic)">Guardar</button>
	</div>
	<div id="dlgBorrarLic" title="Borrar tipo de licencia">
		Solo se permiten borrar tipos de licencias que no tengan movimientos. <br />
		¿Deseas continuar borrando el tipo de licencia: <strong>{{currentLicBorrar.nombre}}</strong>?
		<hr />
		<button id="confirmaBorrar" ng-click="borrarTipoLic(currentLicBorrar)" >SI</button>
	</div>
</div>

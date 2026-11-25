<script src="js/fnModLicSoft.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Catalogo de licencias de software</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php if(isset($_SESSION['empresa'])){echo $_SESSION['empresa'];} ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php if(isset($_SESSION['nombre'])){echo $_SESSION['nombre'];} ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft" ng-controller="catLicenciasSoftware">
	<button id="btnNvaLic" ng-click="dlgNvaLic()" >Nueva licencia</button> <br /> 
	<div id="msjLic"></div>
	
	<label style="width: 100px">Filtrar cliente: </label>
	<select ng-model="filtroCteConLic.nombreCte">
		<option value="">Todos</option>
		<option ng-repeat="cteConLic in listaCteConLicencias" value="{{cteConLic.nombre}}">{{cteConLic.nombre}}</option>
	</select> <br />
	<label style="width: 100px">Refinar: </label>
	<input type="text" placeholder="ej: Office, 2021-04" ng-model="filtroCteConLic.$" /> <br />
	
	<table class="modTb" style="width:100%">
		<tr>
			<th>ID</th>
			<th>Cliente</th>
			<th>Tipo</th>
			<th>Nombre</th>
			<th>Caducidad</th>
			<th>Instancias</th>
			<!-- <th>Status</th> -->
			<th></th>
		</tr>
		<tr class="trBD" ng-repeat="lic in listaLicenciaSoftware | filter: filtroCteConLic">
			<td class="centrado">{{lic.idlicencia}}</td>
			<td>{{lic.nombreCte}}</td>
			<td>{{lic.nombreTipoLic}}</td>
			<td>{{lic.nombreLic}}</td>
			<td>{{lic.fechaCaducidad}}</td>
			<td class="centrado">{{lic.instancias}}</td>
			<!-- <td>{{lic.activo}}</td> -->
			<td style="text-align: center">
				<button title="Ver dispositivos asociados" ng-click="dlgDispAsignados(lic)" ><span class="ui-icon ui-icon-search"></span></button>
				<button title="Editar licencia" ng-click="dlgInfoLic(lic)" ><span class="ui-icon ui-icon-pencil"></span></button>
				<button title="Eliminar licencia" ng-click="dlgBorrarLic(lic)"><span class="ui-icon ui-icon-trash"></span></button>
			</td>
		</tr>
	</table>
	<div id="dlgFrmLic" title="Datos de la licencia">
		<label>Nombre: (*)</label>
		<input type="text" placeholder="ej.: Nombre de la licencia" ng-model="currentLic.nombreLic" value="{{currentLic.nombreLic}}"/> <br />
		<label>Tipo: (*)</label>
		<select id="idTipoLicencia" ng-model="currentLic.idtipolicencia">
			<option ng-repeat="tipoLic in listaTipoLic" value="{{ tipoLic.idtipolicencia }}" ng-selected="currentLic.idtipolicencia == tipoLic.idtipolicencia" >{{ tipoLic.nombre }}</option>
		</select> <br />
		<label>Registro: (*)</label>
		<input type="text" placeholder="ej.: 2020-01-01" class="calendario" ng-model="currentLic.fechaRegistro" value="{{currentLic.fechaRegistro}}"/> <br />
		<label>Caducidad: (*)</label>
		<input type="text" placeholder="ej.: 2020-01-01" class="calendario" ng-model="currentLic.fechaCaducidad" value="{{currentLic.fechaCaducidad}}"/> <br />
		<label>Cliente: (*)</label>
		<select id="idCliente" ng-model="currentLic.idCliente">
			<option ng-repeat="cte in listaCtesParaLic" value="{{ cte.idCliente }}" ng-selected="currentLic.idCliente == cte.idCliente" >{{ cte.nombre }}</option>
		</select> <br />
		<label>Serie: (*)</label>
		<input type="text" placeholder="ej.: 4-4-4-4" ng-model="currentLic.serie" value="{{currentLic.serie}}"/> <br />
		<label>Observaciones:</label>
		<textarea cols="40" rows="5" placeholder="Observaciones de la licencia" value="{{ currentLic.observaciones }}" ng-model="currentLic.observaciones"></textarea>
		<hr />
		<button id="btnGuardarNvaLic" ng-click="guardarNvaLic(currentLic)">Guardar</button>
		<button id="btnGuardarLic" ng-click="guardarLic(currentLic)">Guardar</button>
	</div>
	<div id="dlgBorrarLic" title="Borrar licencia">
		Solo se permiten borrar licencias que no tengan movimientos. <br />
		¿Deseas continuar borrando la licencia: <strong>{{currentLicBorrar.nombreLic}}</strong>?
		<hr />
		<button id="confirmaBorrar" ng-click="borrarLic(currentLicBorrar)" >SI</button>
	</div>
	<div id="dlgDispAsignados" title="Dispositivos asignados a la licencia">
		<strong>Cliene: </strong> {{currentLicAsig.nombreCte}} <br />
		<strong>Tipo: </strong> {{currentLicAsig.nombreTipoLic}} <br />
		<strong>Nombre: </strong> {{currentLicAsig.nombreLic}} <br />
		<strong>Registro: </strong> {{currentLicAsig.fechaRegistro}} <br />
		<strong>Caducidad: </strong> {{currentLicAsig.fechaCaducidad}} <br />
		
		<table class="modTb">
			<tr>
				<th>Host</th>
				<th>MAC</th>
				<th>Cliente</th>
				<th>Oficina</th>
			</tr>
			<tr class="tdBD" ng-repeat="d in dispAsignados">
				<td>{{d.Host}}</td>
				<td>{{d.MAC}}</td>
				<td>{{d.nombreCte}}</td>
				<td>{{d.nombreSucCte}}</td>
			</tr>
		</table>
	</div>
</div>

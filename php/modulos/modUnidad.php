<script src="js/fnModUnidad.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Catalogo de unidades</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php if(isset($_SESSION['empresa'])){echo $_SESSION['empresa'];} ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php if(isset($_SESSION['nombre'])){echo $_SESSION['nombre'];} ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft" ng-controller="catUnidades">
	<button id="btnNvaUni" ng-click="dlgNvaUni()" >Nueva unidad</button> <br /> 
	<div id="msjUni"></div>
	<table class="modTb" style="width:50%">
		<tr>
			<th>ID unidad</th>
			<th>Medida</th>
			<th></th>
		</tr>
		<tr class="trBD" ng-repeat="unidad in listaUnidades ">
			<td class="centrado">{{unidad.idUnidad}}</td>
			<td>{{unidad.medida}}</td>
			<td style="text-align: center">
				<button title="Editar unidad" ng-click="dlgInfoUni(unidad)" ><span class="ui-icon ui-icon-pencil"></span></button>
				<button title="Eliminar unidad" ng-click="dlgBorrarUni(unidad)"><span class="ui-icon ui-icon-trash"></span></button>
			</td>
		</tr>
	</table>
	<div id="dlgFrmUni" title="Información de la unidad">
		<label>Medida: (*)</label>
		<input type="text" placeholder="ej.: Unidad de medida" ng-model="currentUni.medida" value="{{currentUni.medida}}"/>
		<hr />
		<button id="btnGuardarNvaUni" ng-click="guardarNvaUni(currentUni)">Guardar</button>
		<button id="btnGuardarUni" ng-click="guardarUni(currentUni)">Guardar</button>
	</div>
	<div id="dlgBorrarUni" title="Borrar unidad">
		Solo se permiten borrar unidades que no tengan movimientos. <br />
		¿Deseas continuar borrando la unidad de medida: <strong>{{currentUniBorrar.medida}}</strong>?
		<hr />
		<button id="confirmaBorrar" ng-click="borrarUni(currentUniBorrar)" >SI</button>
	</div>
</div>

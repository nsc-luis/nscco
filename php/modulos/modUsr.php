<script src="js/fnModUsr.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Catalogo de usuarios</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php if(isset($_SESSION['empresa'])){echo $_SESSION['empresa'];} ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php if(isset($_SESSION['nombre'])){echo $_SESSION['nombre'];} ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft" ng-controller="catUsuarios">
	<button id="btnNvoUsr" ng-click="dlgNvoUsr()" >Nuevo usuario</button> <br /> 
	<input type="text" placeholder="Refinar busqueda" ng-model="filtroUsr" />
	<div id="msjUsr"></div>
	<table class="modTb">
		<tr>
			<th>Usuario</th>
			<th>Nombre</th>
			<th>E-mail</th>
			<th></th>
		</tr>
		<tr class="trBD" ng-repeat="usuario in listaUsuarios | filter:filtroUsr ">
			<td class="centrado">{{usuario.usuario}}</td>
			<td>{{usuario.nombre}}</td>
			<td>{{usuario.emailUsr}}</td>
			<td style="text-align: center">
				<button title="Editar usuario" ng-click="dlgInfoUsr(usuario)" ><span class="ui-icon ui-icon-pencil"></span></button>
				<button title="Eliminar usuario" ng-click="dlgBorrarUsr(usuario)"><span class="ui-icon ui-icon-trash"></span></button>
			</td>
		</tr>
	</table>
	<div id="dlgFrmUsr" title="Información del usuario">
		<label>Usuario: (*)</label>
		<input type="text" placeholder="ej.: lgro" ng-model="currentUsr.usuario" value="{{currentUsr.usuario}}" /> <br />
		<label>Nombre: (*)</label>
		<input type="text" placeholder="ej.: Nombre del usuario" ng-model="currentUsr.nombre" value="{{currentUsr.nombre}}"/> <br />
		<label>Contraseña: (*)</label>
		<input type="password" ng-model="currentUsr.password" value="{{currentUsr.password}}" /> <br />
		<label>Confirmar contraseña: (*)</label>
		<input type="password" ng-model="currentUsr.confirmarPass" /> <br />
		<label>e-mail: (*)</label>
		<input type="text" placeholder="ej.: buzon@dominio.com" ng-model="currentUsr.emailUsr" value="{{currentUsr.emailUsr}}"/>
		<hr />
		<button id="btnGuardarNvoUsr" ng-click="guardarNvoUsr(currentUsr)">Guardar</button>
		<button id="btnGuardarUsr" ng-click="guardarUsr(currentUsr)">Guardar</button>
	</div>
	<div id="dlgBorrarUsr" title="Borrar usuario">
		Solo se permiten borrar usuarios que no tengan movimientos. <br />
		¿Deseas continuar borrando el usuario: <strong>{{currentUsrBorrar.nombre}}</strong>?
		<hr />
		<button id="confirmaBorrar" ng-click="borrarUsr(currentUsrBorrar)" >SI</button>
	</div>
</div>

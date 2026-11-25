<script src="js/fnModCte.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Catalogo de clientes</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php if (isset($_SESSION['empresa'])) { echo $_SESSION['empresa']; }?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php if (isset($_SESSION['nombre'])) { echo $_SESSION['nombre']; }?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft" ng-controller="catClientes">
	<button id="btnNvoCte2" ng-click="dlgNvoCte()" >Nuevo cliente</button> <br /> 
	<input type="text" placeholder="Refinar busqueda" ng-model="filtroCte" />
	<div id="msjCte"></div>
	<table class="modTb">
		<tr>
			<th>Clave</th>
			<th>RFC</th>
			<th>Nombre</th>
			<th>Saldo</th>
			<th>Poliza <br /> HrsExtPresencial <br /> HrsExtRemoto</th>
			<th></th>
		</tr>
		<tr class="trBD" ng-repeat="cliente in listaClientes | filter:filtroCte ">
			<td class="centrado">{{cliente.clave}}</td>
			<td>{{cliente.rfc}}</td>
			<td>{{cliente.nombre}} ( {{ cliente.idCliente }} )</td>
			<td class="fright" >{{cliente.saldo | currency}}</td>
			<td class="centrado" > 
				{{cliente.poliza}} <br /> 
				{{cliente.HrsExtPresencial}}hrs ({{cliente.importeHrsExtPresencial | currency}}) <br /> 
				{{cliente.HrsExtRemoto}}hrs ({{cliente.importeHrsExtRemoto | currency}})
			</td>
			<td style="text-align: center">
				<button title="Editar cliente" ng-click="dlgInfoCte(cliente)" ><span class="ui-icon ui-icon-pencil"></span></button>
				<button title="Sucursales del cliente" ng-click="dlgSucCte(cliente)" ><span class="ui-icon ui-icon-home"></span></button>
				<button title="Eliminar cliente" ng-click="dlgBorrarCte(cliente)"><span class="ui-icon ui-icon-trash"></span></button>
			</td>
		</tr>
	</table>
	<div id="dlgFrmCte" title="Información del cliente">
		<label>Clave: (*)</label>
		<input type="text" placeholder="ej.: lgro" ng-model="currentCte.clave" value="{{currentCte.clave}}" /> <br />
		<label>Nombre: (*)</label>
		<input type="text" placeholder="ej.: Luis Gerardo Rdz Ovalle" ng-model="currentCte.nombre" value="{{currentCte.nombre}}"/> <br />
		<label>RFC: (*)</label>
		<input type="text" placeholder="ej.: XAXX010101000" ng-model="currentCte.rfc" value="{{currentCte.rfc}}"/> <br />
		<label>Dirección:</label>
		<textarea cols="23" rows="5" ng-model="currentCte.direccion" value="{{currentCte.direccion}}"></textarea> <br />
		<label>Telefono1:</label>
		<input type="text" placeholder="ej.: 8110243970" ng-model="currentCte.telefono1" value="{{currentCte.telefono1}}"/> <br />
		<label>e-mail:</label>
		<input type="text" placeholder="ej.: nsc_luis@nscco.com.mx" ng-model="currentCte.email" value="{{currentCte.email}}"/> <br />
		<label>POLIZA:</label>
		<input type="checkbox" ng-model="currentCte.poliza" ng-checked="currentCte.poliza == 1 ? 1 : 0" ng-true-value="'1'" ng-false-value="'0'" id="chkBoxPoliza"/> <br /><br />
		<div id="datosDeLaPoliza" title="Datos de la poliza">
			<label>Servicio presencial: </label>
			<input type="text" ng-model="currentCte.horasServPresencial" value="{{currentCte.horasServPresencial}}"/> (HORAS) <br />
			<label>Servicio remoto: </label>
			<input type="text" ng-model="currentCte.horasServRemoto" value="{{currentCte.horasServRemoto}}"/> (HORAS) <br />
			<label>Hora extra prensencial: </label>
			<input type="text" ng-model="currentCte.precioHoraPresencial" value="{{currentCte.precioHoraPresencial}}"/> <br />
			<label>Hora extra remoto: </label>
			<input type="text" ng-model="currentCte.precioHoraRemoto" value="{{currentCte.precioHoraRemoto}}"/> <br />
		</div>
		<hr />
		<button id="btnGuardarNvoCte" ng-click="guardarNvoCte(currentCte)">Guardar</button>
		<button id="btnGuardarCte" ng-click="guardarCte(currentCte)">Guardar</button>
	</div>
	<div id="dlgSucursalesCte" title="Sucursales del cliente">
		Cliente: {{currentCteSuc.nombre}} <br />
		<button id="btnNvaSucCte2" ng-click="dlgNvaSucCte(currentCteSuc)">Nueva sucursal/oficina</button> <br />
		<table class="modTb">
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th></th>
			</tr>
			<tr class="trBD" ng-repeat="sucCte in listaSucursalesCte">
				<td class="centrado">{{sucCte.idSucursalCte}}</td>
				<td>{{sucCte.nombre}}</td>
				<td class="centrado">
					<button ng-click="infoSucCte(sucCte)" title="Editar sucursal/oficina"><span class="ui-icon ui-icon-pencil"></span></button>
					<button ng-click="borraSucCte(sucCte)" title="Borrar sucursal/oficina"><span class="ui-icon ui-icon-trash"></span></button>
				</td>
			</tr>
		</table>
	</div>
	<div id="dlgFrmSucursalCte" title="Información de la sucursal">
		<label>Nombre: (*)</label>
		<input type="text" placeholder="ej.: México" ng-model="currentSucCte.nombre" value="{{currentSucCte.nombre}}"/> <br />
		<label>Dirección:</label>
		<textarea cols="23" rows="5" ng-model="currentSucCte.dirSucCte" value="{{currentSucCte.dirSucCte}}"></textarea> <br />
		<label>Telefono1:</label>
		<input type="text" placeholder="ej.: 8110243970" ng-model="currentSucCte.telefono1" value="{{currentSucCte.telefono1}}"/>
		<hr />
		<button id="btnGuardarNvaSucCte" ng-click="guardarNvaSucCte(currentSucCte)">Guardar</button>
		<button id="btnGuardarSucCte" ng-click="guardarSucCte(currentSucCte)">Guardar</button>
	</div>
	<div id="dlgBorrarCte" title="Borrar cliente">
		Al borrar la empresa: <strong>{{currentCteBorrar.nombre}}</strong> tambien seran eliminadas totas <br />
		las sucursales/oficinas capturadas en ella, sin emmbargo, no se puede borrar una empresa con movimientos. <br />
		¿Deseas continuar borrando la empresa?
		<hr />
		<button id="confirmaBorrar" ng-click="borrarCte(currentCteBorrar)" >SI</button>
	</div>
</div>

<script src="js/fnModProd.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Catalogo de productos</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php echo $_SESSION['empresa']; ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php echo $_SESSION['nombre']; ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft" ng-controller="catProductos">
	<button id="btnNvoProd" ng-click="dlgNvoProd()" >Nuevo producto</button> <br /> 
	<input type="text" placeholder="Refinar busqueda" ng-model="filtroProd" />
	<div id="msjProd"></div>
	<table class="modTb">
		<tr>
			<th>Clave</th>
			<th>Nombre</th>
			<th>Unidad</th>
			<th>Precio</th>
			<th></th>
		</tr>
		<tr class="trBD" ng-repeat="producto in listaProductos | filter:filtroProd ">
			<td class="centrado">{{producto.clave}}</td>
			<td>{{producto.nombre}}</td>
			<td>{{producto.medida}}</td>
			<td class="fright" >{{producto.precio | currency}}</td>
			<td style="text-align: center">
				<button title="Editar producto" ng-click="dlgInfoProd(producto)" ><span class="ui-icon ui-icon-pencil"></span></button>
				<button title="Eliminar producto" ng-click="dlgBorrarProd(producto)"><span class="ui-icon ui-icon-trash"></span></button>
			</td>
		</tr>
	</table>
	<div id="dlgFrmProd" title="Información del producto">
		<label>Clave: (*)</label>
		<input type="text" placeholder="ej.: lgro" ng-model="currentProd.clave" value="{{currentProd.clave}}" /> <br />
		<label>Nombre: (*)</label>
		<input type="text" placeholder="ej.: nombre servicio ó producto" ng-model="currentProd.nombre" value="{{currentProd.nombre}}"/> <br />
		<label>Descripción:</label>
		<textarea rows=10 cols=40 placeholder="ej.: Descripcion larga del producto" ng-model="currentProd.descripcion">{{ currentProd.descripcion }}</textarea> <br /> <br />
		<label>Precio:</label>
		<input type="text" ng-model="currentProd.precio" value="{{currentProd.precio}}" /> <br />
		<label>Unidad: (*)</label>
		<select id="idUnidad" ng-model="currentProd.idUnidad">
			<option ng-repeat="unidad in listaUnidades" value="{{unidad.idUnidad}}" ng-selected="currentProd.idUnidad == unidad.idUnidad">{{ unidad.medida }}</option>
		</select><br />
		<label>Proveedor: (opcional)</label>
		<input type="text" placeholder="ej.: UNICOM, INGRAM, etc" ng-model="currentProd.proveedor" value="{{currentProd.proveedor}}"/> <br />
		<label>SKU: (opcional)</label>
		<input type="text" ng-model="currentProd.skuProveedor"  value="{{currentProd.skuProveedor}}" /> <br /><br />
		<hr />
		<button id="btnGuardarNvoProd" ng-click="guardarNvoProd(currentProd)">Guardar</button>
		<button id="btnGuardarProd" ng-click="guardarProd(currentProd)">Guardar</button>
	</div>
	<div id="dlgBorrarProd" title="Borrar producto">
		Solo se permiten borrar productos/servicios que no tengan movimientos. <br />
		¿Deseas continuar borrando el producto: <strong>{{currentProdBorrar.nombre}}</strong>?
		<hr />
		<button id="confirmaBorrar" ng-click="borrarProd(currentProdBorrar)" >SI</button>
	</div>
</div>

<script src="js/fnModOrdServ.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Ordenes de servicio</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php if(isset($_SESSION['empresa'])){echo $_SESSION['empresa'];} ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php if(isset($_SESSION['nombre'])){echo $_SESSION['nombre'];} ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft" ng-controller="listaDeOrdenes">
	<div id="frmOrdServ"></div>
	<div id="result"></div>
	
	<div id="filtro">
		<h3><strong>Filtrar ordenes de servicio.</strong></h3>
		<div>
			<label>Cliente: </label>
			<select ng-model="cveCte">
				<option value="">Todos</option>
				<option ng-repeat="cliente in listaClientes" value="{{cliente.clave}}">{{ cliente.nombre }}</option>
			</select> <br />
			<label>Oficina: </label>
			<select ng-model="idSucursalCte">
				<option value="">Todas</option>
				<option ng-repeat="oficina in listaOficinas | filter : cveCte" value="{{oficina.idSucursalCte}}">{{ oficina.nombre }}</option>
			</select> <br />
			<label>Status: </label>
			<select ng-model="idStatus">
				<option value="">Todos</option>
				<option ng-repeat="status in tiposStatus" value="{{status.idStatus}}">{{ status.status }}</option>
			</select> <br />
			<label>Usuario: </label>
			<select ng-model="usuario">
				<option value="">Todos</option>
				<option ng-repeat="usuario in listaUsuarios" value="{{usuario.usuario}}">{{ usuario.nombre }}</option>
			</select> <br />
			<label>Fecha inicial: </label>
			<input type="text" ng-model="fechaInicial" class="calendario"> <br />
			<label>Fecha final: </label>
			<input type="text" ng-model="fechaFinal" class="calendario">
			<button id="btnRemoverFiltro" ng-click="btnRemoverFiltro()" >Remover filtro</button>
			<button id="btnFijarFiltro" ng-click="btnFijarFiltro()" title="Permite fijar filtro rapido al recargar la página." >Fijar filtro</button>
		</div>
	</div>
	<p>
		<button ng-click="nvaOrdServ('<?php echo $_SESSION['usuario']; ?>')" id="btnNvaOrdServ">Nueva orden de servicio</button>
		<button id="descargarOrdServ" title="Descarga en un archivo zip los documentos seleccionados." >Descargar documentos</button>
		<button id="btnExpExcel" title="Genera en Excel listado y partidas para factura del filtro aplicado." ng-click="expOrdServExcel(usuario,cveCte,idSucursalCte,idStatus)" >Exportar a Excel</button>
		<button id="btnCambiarStatus" title="Permite cambiar en un solo movimiento el status de los documentos seleccionados." >Cambiar status</button>
	</p>
	<!-- <div class="controlDePaginas">
		<strong>REGISTROS POR PÁGINA: </strong>
		<select ng-change="fnSaltoPaginaOrdServ(saltoPaginaOrdServ)" ng-model="saltoPaginaOrdServ">
			<option value="50">50</option>
			<option value="75">75</option>
			<option value="100">100</option>
		</select>
		<button><span class="ui-icon ui-icon-seek-first"></span></button>
		<button><span class="ui-icon ui-icon-triangle-1-w"></span></button>
		<strong>PÁGINA{{ paginaOrdServ }} / X </strong>
		<button><span class="ui-icon ui-icon-triangle-1-e"></span></button>
		<button><span class="ui-icon ui-icon-seek-end"></span></button>
	</div> -->
	<div id="listaOrdenesDeServicio">
		<table>
			<tr>
				<th></th>
				<th>Folio</th>
				<th>Cliente</th>
				<th>Sucursal</th>
				<th>Importe</th>
				<th>Fecha</th>
				<th>Status</th>
				<th></th>
			</tr>
			<tr class="trBD" ng-repeat="orden in listaDeOrdenes | filter : { cveCte : cveCte, idStatus: idStatus, idSucursalCte : idSucursalCte, usuario : usuario } ">
				<td width="3%" class="centrado"><input type='checkbox' class="{{ orden.folio }}" style="width:10px;" /></td>
				<td width="12%" class="moneda"> {{ orden.folio }} </td>
				<td width="20%" > {{ orden.nombreCte }} </td>
				<td width="15%" > {{ orden.nombreSucursal }} </td>
				<td width="10%" class="moneda"> {{ orden.importe | currency }} </td>
				<td width="10%" class="centrado"> {{ orden.fechaOrdServ }} </td>
				<td width="7%" ng-dblclick="dlgCambiarStatus(orden)" > {{ orden.status }} </td>
				<td width="23%" class="centrado">
					<button ng-click="editarOrdServ(orden)" title="Editar"><span class="ui-icon ui-icon-pencil"></span></button>
					<button ng-click="imprimirOrdServ(orden)" title="Imprimir"><span class="ui-icon ui-icon-print"></span></button>
					<button ng-click="crearPDFOrdServ(orden)" title="Descargar PDF"><span class="ui-icon ui-icon-circle-arrow-s"></span></button>
					<button ng-click="dlgBorrarOrdServ(orden)" title="Borrar"><span class="ui-icon ui-icon-trash"></span></button>
				</td>
			</tr>
		</table>
	</div>
	
	<div id="cambioStatus" title="Cambiar status">
		Orden de servicio: <strong> {{ ordenACambiarStatus.folio }} </strong> <br />
		<label for="statusCambio">Cambiar status: (*)</label>
		<select id="statusCambio" ng-model="ordenACambiarStatus.idStatus">
			<option ng-repeat="status in tiposStatus" value="{{status.idStatus}}">{{ status.status }}</option>
		</select> <br />
		<label for="statusDetalleCambio">Referencia/Motivo: (*)</label>
		<input type="text" value="{{ ordenACambiarStatus.referenciaFact }}" ng-model="ordenACambiarStatus.referenciaFact" />
		<hr>
		<button ng-click="guardaStatus(ordenACambiarStatus)" class="btnCambiarStatus">Guardar</button>
	</div>
	<div id="cambioStatusVarios" title="Cambiar status">
		Orden de servicio: <br /> 
		<div id="variosFolios"></div>
		<label for="statusCambio">Cambiar status: (*)</label>
		<select id="idStatusParaVarios">
			<option ng-repeat="status in tiposStatus" value="{{status.idStatus}}" >{{ status.status }}</option>
		</select> <br />
		<label for="referenciaFactVarios">Referencia/Motivo: (*)</label>
		<input type="text" id="referenciaFactVarios" />
	</div>
	<div id="dlgBorrarOrdServ" title="Borrar orden de servicio">
		Solo se permiten borrar ordenes de servicio con status activo. <br />
		¿Deseas continuar borrando la orden: <strong>{{ ordenABorrar.folio }}</strong>?
		<hr>
		<button ng-click="borrarOrdServ(ordenABorrar)" class="btnBorrarOrdServ">Si</button>
	</div>
</div>
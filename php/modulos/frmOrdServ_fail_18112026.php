<script src="js/fnModOrdServ.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Orden de servicio</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php if(isset($_SESSION['empresa'])){echo $_SESSION['empresa'];} ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php if(isset($_SESSION['nombre'])){echo $_SESSION['nombre'];} ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft" ng-controller="ordenDeServicio">
	<div id="frmPartidaOrdServ"></div>
	<label for="folio">Folio:</label>
	<input type="text" name="folio" id="folio" value="{{ currentOrdenDeServ.folio }}" readonly /> <br />
	<label for="idStatus">Status:</label>
	<input type="text" value="{{ currentOrdenDeServ.status }}" readonly /> <br />
	<label for="fechaOrdServ">Fecha: (*)</label>
	<input type="text" class="calendario" ng-model="currentOrdenDeServ.fechaOrdServ" value="{{ currentOrdenDeServ.fechaOrdServ }}" />
	<span class="avisoFrm">(YYYY-MM-DD)</span><br />
	<label for="cveCte">Cliente: (*)</label>
	<select id="cveCte" ng-model="currentOrdenDeServ.cveCte"
		ng-options="cliente.clave as cliente.nombre for cliente in listaClientes"
		ng-change="currentOrdenDeServ.idSucursalCte = null">
	</select><br />
	<label for="idSucursalCte">Sucursal cte: (*)</label>
	<select id="idSucursalCte" ng-model="currentOrdenDeServ.idSucursalCte"
		ng-options="oficina.idSucursalCte as oficina.nombre for oficina in listaOficinas | filter : {cveCte: currentOrdenDeServ.cveCte} : true">
		<option value="">-- Selecciona --</option>
	</select><span class="avisoFrm">DEBES SELECCIONAR UNA SUCURSAL</span><br />
	<!-- <label for="referenciaFact">Referencia: </label>
	<input class="referenciaFact" type="text" name="referenciaFact" readonly id="referenciaFact" value="{{ currentOrdenDeServ.referenciaFact }}" /> <br /> -->
	
	<div id="areaPartidasOrdServ">		
		<div id="txtBuscarProducto" style="padding: 3px; margin: 5px; height: 30px; width: 410px; border: 1px solid black;">
		<input 
			placeholder="Escribe clave o descripcion del producto. Ej: hdd, disco duro" 
			style="border: 0px; width: 85%;" ng-model="cveProducto" 
			autocomplete
		/>
		<button style="margin-top: 4px; float: right;" title="Agregar partida" ng-click="dlgAgregarPartida(cveProducto)"><span class="ui-icon ui-icon-plus"></span></button>
		</div>
		
		<div id="partidasOrdServ">
			<table class='modTb'>
				<tr>
					<th width="10%">Cantidad</th>
					<th width="54%">Descripcion</th>
					<th width="12%">P. Unitario</th>
					<th width="12%">Importe</th>
					<th width="12%"></th>
				</tr>
				<tr class="trBD" ng-repeat="partida in partidas">
					<td class="centrado">{{ partida.cantidad }}</td>
					<td><strong>{{ partida.nombre }}</strong> <br />{{ partida.detalle }}</td>
					<td class="moneda">{{ partida.precioUnitario | currency }}</td>
					<td class="moneda">{{ partida.importePart | currency }}</td>
					<td class="centrado">
						<button ng-click="dlgPartidaSeleccionada(partida)" title="Editar partida"><span class="ui-icon ui-icon-pencil"></span></button>
						<button ng-click="dlgBorrarPartida(partida)" title="Borrar partida"><span class="ui-icon ui-icon-trash"></span></button>
					</td>
				</tr>
			</table>
		</div>
	</div>
		
	<p class="subTotal">SUBTOTAL: {{ currentOrdenDeServ.importe | currency }}</p>
	
	<label for="tiempoInvertido">Tiempo invertido: (*)</label>
	<input type="number" id="tiempoInvertido" validar="numero" ng-model="currentOrdenDeServ.tiempoInvertido" value="{{ currentOrdenDeServ.tiempoInvertido }}<?php /* if(isset($tiempoInvertido)){echo $tiempoInvertido;} */ ?>" /> 
	<span class="avisoFrm">(HORAS)</span><br />
	<label for="idTipoDeServ">Tipo de servicio: (*)</label>
	<select id="idTipoDeServ" ng-model="currentOrdenDeServ.idTipoDeServ"
		ng-options="tipoDeServ.idTipoDeServ as tipoDeServ.tipoDeServ for tipoDeServ in tiposDeServicio">
		<option value="">-- Selecciona --</option>
	</select><span class="avisoFrm">DEBES SELECCIONAR EL TIPO DE SERVICIO</span><br />
	<label for="observacion">Observaciones:</label>
	<textarea id="observacion" rows=2 cols=60 ng-model="currentOrdenDeServ.observacion" >{{ currentOrdenDeServ.observacion }}</textarea> <br />
	<label for="usuario">Usuario:</label>
	<input class="usuario" type="text" id="usuario" value="{{ currentOrdenDeServ.usuario }}" readonly /> <br />
	<label for="recibidoPor">Recibido/Ordeno: (*)</label>
	<input type="text" id="recibidoPor" validar="descripcion" ng-model="currentOrdenDeServ.recibidoPor" value="{{ currentOrdenDeServ.recibidoPor }}" /> <br />
	<p class="validateTips"><span class="avisoFrm">(*) Campos requeridos.</span></p>
	<br />
	
	<button id="btnVolver">Volver</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<button id="guardarOrdServ" ng-click="guardarOrdServ(currentOrdenDeServ)" >GUARDAR ORDEN DE SERVICO</button><br/>
	
	<div id="dlgProductoSeleccionado" title="Datos de la partida">
		<label>Clave:</label> <input type="text" value="{{ partidaActual.clave }}" readonly /> <br />
		<label>Nombre:</label> <input type="text" value="{{ partidaActual.nombre }}" readonly /> <br />
		<label>Descripcion:</label>
		<textarea id="partidaDescripcion" cols="60" rows="10" ng-model="partidaActual.descripcion" >{{ partidaActual.descripcion }}</textarea>
		<textarea id="partidaDetalle" cols="60" rows="10" ng-model="partidaActual.detalle" >{{ partidaActual.detalle }}</textarea>
		<br />
		<label>Cantidad:</label> <input type="number" ng-model="partidaActual.cantidad" value="{{ partidaActual.cantidad }}" /> <br />
		<label >Precio:</label> <input type="text" ng-model="partidaActual.precioUnitario" value="{{ partidaActual.precioUnitario }}"/> <br />
		<label>Total: </label> <input type="text" value="{{ partidaActual.cantidad * partidaActual.precioUnitario | currency }}" readonly />
		
		<hr />
		<button id="btnAgregarPartida" ng-click="agregaPartida(partidaActual)" >Agregar partida</button>
		<button id="btnGuardarPartida" ng-click="guardarPartida(partidaActual)" >Guardar partida</button>
	</div>
	
	<div id="dlgBorrarPartida" title="Borrar partida">
		¿Estas seguro que deseas borrar la partida <strong>{{ borrarPartida.nombre }} ( {{ borrarPartida.clave }} )</strong>?
		<hr>
		<button id="btnBorrarPartida" ng-click="btnBorrarPartida(borrarPartida)">Si, borrar partida</button>
	</div>
</div>
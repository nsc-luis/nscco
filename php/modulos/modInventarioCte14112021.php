<script src="js/fnModInventario.js" language="javascript"></script>
<style>
@media print 
	{
		@page {
		  size: Letter; /* DIN A4 standard, Europe */
		  /* margin: 0; */
		}
		html, body {
			width: 210mm;
			/* height: 297mm; */
			height: 282mm;
			font-size: 11px;
			background: #FFF;
			overflow:visible;
		}
		body {
			/* padding-top:15mm; */
		}
		#infoSesion, #barraLateral, #menu, #cabecera .salir { display: none; }
		#capaBtnImpInvCte { width: 100%; text-align: right; }
	}
</style>
<div id="infoSesion">
	<span class="fleft infoVentana">Catalogo de productos</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php echo $_SESSION['empresa']; ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php echo $_SESSION['nombre']; ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft" ng-controller="catInventario">
	<button id="btnNvoDisp" ng-click="dlgNvoDisp()" >Nuevo registro</button> <br /> 
	<div id="capaBtnImpInvCte">
		<button id="btnImpInvCte">Imprimir</button>
	</div>
	<div id="msjInv"></div>
	
	<div id="CompusDisp">
		<ul>
			<li><a href="#computadoras">Computadoras</a></li>
			<li><a href="#perifericos">Dispositivos perifericos</a></li>
		</ul>
		<!-- <h3>Computadoras</h3> -->
		<div id="computadoras">
			<input type="text" placeholder="Refinar busqueda" ng-model="filtroInventario" />
			<table class="modTb">
				<tr>
					<th>Hostname</th>
					<th>Fabricante, Windows</th>
					<th>MAC Address</th>
					<th>Sucursal</th>
					<th></th>
				</tr>
				<tr class="trBD" ng-repeat="dispositivo in listaDispositivos | filter:filtroInventario">
					<td class="centrado">{{dispositivo.Host}}</td>
					<td>({{ dispositivo.Fabricante }}) ({{ dispositivo.SO }})</td>
					<td>{{dispositivo.MAC}}</td>
					<td>{{dispositivo.nombreSucCte}}</td>
					<td style="text-align: center">
						<button title="Editar dispositivo" ng-click="dlgInfoDisp(dispositivo)" ><span class="ui-icon ui-icon-pencil"></span></button>
						<button title="Eliminar dispositivo" ng-click="dlgBorrarDisp(dispositivo)"><span class="ui-icon ui-icon-trash"></span></button>
					</td>
				</tr>
			</table>
		</div>
		<!-- <h3>Dispositivos perifericos</h3> -->
		<div id="perifericos">
			<input type="text" placeholder="Refinar busqueda" ng-model="filtroPerifericos" />
			<table class="modTb">
				<tr>
					<th>Nombre</th>
					<th>Marca</th>
					<th>Modelo</th>
					<th>Tipo</th>
					<th>Oficina</th>
					<th></th>
				</tr>
				<tr class="trBD" ng-repeat="periferico in listaPerifericos | filter:filtroPerifericos">
					<td>{{periferico.nombre}}</td>
					<td>{{ periferico.marca }}</td>
					<td>{{periferico.modelo}}</td>
					<td class="centrado">{{periferico.nombreTipoDisp}}</td>
					<td class="centrado">{{periferico.nombreOfna}}</td>
					<td style="text-align: center">
						<button title="Editar periferico" ng-click="dlgInfoPeriferico(periferico)" ><span class="ui-icon ui-icon-pencil"></span></button>
						<button title="Eliminar periferico" ng-click="dlgBorrarPeriferico(periferico)"><span class="ui-icon ui-icon-trash"></span></button>
					</td>
				</tr>
			</table>
		</div>
	</div>
	
	<div id="dlgFrmDisp" title="Información del dispositivo">
		<label>Cliente</label>
		<input type="text" placeholder="ej.: lgro" ng-model="currentDisp.nombreCte" value="{{currentDisp.nombreCte}}" readonly /> <br />
		<label>Sucursal</label>
		<select id="idSucursalCte" ng-model="currentDisp.idsucursalcte">
			<option ng-repeat="ofna in listaOficinas" value="{{ ofna.idSucursalCte }}"> {{ ofna.nombre }} </option>
		</select> <br />
		<label>MAC</label>
		<input type="text" placeholder="ej.: XX:XX:XX:XX:XX:XX" ng-model="currentDisp.MAC" value="{{currentDisp.MAC}}" readonly /> <br />
		<label>Hostname</label>
		<input type="text" placeholder="ej.: nsc_luis" ng-model="currentDisp.Host" value="{{currentDisp.Host}}" readonly /> <br />
		<label>SO</label>
		<input type="text" placeholder="ej.: Windows 10 Pro" ng-model="currentDisp.SO" value="{{currentDisp.SO}}" readonly /> <br />
		<label>Disco Duro</label>
		<input type="text" placeholder="ej.: 1TB" ng-model="currentDisp.HD" value="{{currentDisp.HD}}" readonly /> <br />
		<label>Procesador</label>
		<input type="text" placeholder="ej.: Intel Core i3" ng-model="currentDisp.CPU" value="{{currentDisp.CPU}}" readonly /> <br />
		<label>RAM (Utilizable)</label>
		<input type="text" placeholder="ej.: 6.9GB" ng-model="currentDisp.RAM" value="{{currentDisp.RAM}}" readonly /> <br />
		<label>Fabricante</label>
		<input type="text" placeholder="ej.: GIGABYTE" ng-model="currentDisp.Fabricante" value="{{currentDisp.Fabricante}}" readonly /> <br />
		<label>Fecha</label>
		<input type="text" placeholder="ej.: aaaa-mm-dd, etc" ng-model="currentDisp.fecharegistro" value="{{currentDisp.fecharegistro}}" readonly /> <br />
		<label>Licencias de Office</label>
		<select id="idLicOffice" ng-model="licOffice">
			<option ng-repeat="licOffice in listaDeOffice" value="{{licOffice.idlicencia}}">{{licOffice.descripcion}}</option>
		</select><br />
		<label>Licencias de Antivirus</label>
		<select id="idLicAV" ng-model="licAV">
			<option ng-repeat="licAV in listaDeAV" value="{{licAV.idlicencia}}">{{licAV.descripcion}}</option>
		</select><br />
		<label>Observaciones</label>
		<textarea cols="24" rows="4" ng-model="currentDisp.Observaciones">{{ currentDisp.Observaciones }}</textarea> <br />
		<hr />
		<button id="btnGuardarNvoDisp" ng-click="guardarNvoDisp(currentDisp)">Guardar</button>
		<button id="btnGuardarDisp" ng-click="guardarDisp(currentDisp)">Guardar</button>
	</div>
	
	<div id="dlgBorrarDisp" title="Borrar dispositivo">
		¿Deseas continuar borrando el dispositivo con MAC: <strong>{{currentDispBorrar.MAC}}</strong>?
		<hr />
		<button id="confirmaBorrar" ng-click="borrarDisp(currentDispBorrar)" >SI</button>
	</div>
	
	<div id="dlgFrmPeriferico" title="Datos del dispositivo">
		<label>Oficina (*): </label>
		<select ng-model="currentPeriferico.idSucursalCte">
			<option ng-repeat="ofna in listaOficinas" value="{{ ofna.idSucursalCte }}">{{ ofna.nombre }}</option>
		</select> <br />
		<label>Tipo de dispositivo (*): </label>
		<select ng-model="currentPeriferico.idTipoDisp">
			<option ng-repeat="td in listaTipoDispositivo" value="{{ td.idTipoDisp }}">{{ td.nombre }}</option>
		</select> <br />
		<label>Nombre (*): </label>
		<input type="text" placeholder="ej.: Impresora Xerox de RH" ng-model="currentPeriferico.nombre" value="{{currentPeriferico.nombre}}" /> <br />
		<label>Marca (*): </label>
		<input type="text" placeholder="ej.: Xerox" ng-model="currentPeriferico.marca" value="{{currentPeriferico.marca}}" /> <br />
		<label>Modelo (*): </label>
		<input type="text" placeholder="ej.: WorkCentre 3345" ng-model="currentPeriferico.modelo" value="{{currentPeriferico.modelo}}" /> <br />
		<label>Serie: </label>
		<input type="text" placeholder="ej.: ABCD1234#XX" ng-model="currentPeriferico.serie" value="{{currentPeriferico.serie}}" /> <br />
		<label>Parte: </label>
		<input type="text" placeholder="ej.: ABCD1234#XX" ng-model="currentPeriferico.parte" value="{{currentPeriferico.parte}}" /> <br />
		<label>Fecha registro: </label>
		<input type="text" placeholder="ej.: aaaa-mm-dd" ng-model="currentPeriferico.fechaRegistro" value="{{currentPeriferico.fechaRegistro}}" class="calendario" /> <br />
		<label>Observaciones: </label>
		<textarea cols="24" rows="4" ng-model="currentPeriferico.observaciones">{{currentPeriferico.observaciones}}</textarea> <br />
		<hr />
		<button id="btnGuardarNvoPeriferico" ng-click="guardarNvoPeriferico(currentPeriferico)">Guardar</button>
		<button id="btnGuardarPeriferico" ng-click="guardarPeriferico(currentPeriferico)">Guardar</button>
	</div>
	
	<div id="dlgBorrarPeriferico" title="Borrar periferico">
		¿Deseas continuar borrando el dispositivo: <strong>{{currentPerifericoBorrar.nombre}}</strong>?
		<hr />
		<button id="confirmaBorrar" ng-click="borrarPeriferico(currentPerifericoBorrar)" >SI</button>
	</div>
	
</div>

<script src="js/fnModBitRespCte.js" language="javascript"></script>
<style>
	@media print 
	{
		@page {
		  size: Letter; /* DIN A4 standard, Europe */
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
			padding-top:15mm;
		}
		#infoSesion, #barraLateral, #menu, #cabecera .salir { display: none; }
		#capaBtnImpBitCte { width: 100%; text-align: right; }
	}
</style>
<div id="infoSesion">
	<span class="fleft infoVentana">Bitacora de respaldo de clientes</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php if (isset($_SESSION['empresa'])) { echo $_SESSION['empresa']; }?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php if (isset($_SESSION['nombre'])) { echo $_SESSION['nombre']; }?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<div id="contenido" class="fleft" ng-controller="bitRespCte">
	<strong>CLIENTE: </strong> {{ cliente }}
	<hr />
	<div id="capaBtnImpBitCte">
		<button id="btnImprimirBitCte">Imprimir</button>
	</div>
	<p><input type="text" placeholder="Refinar filtro" ng-model="filtroRespaldos"></p>
	<table class="modTb">
		<tr>
			<th>Nombre de respaldo</th>
			<th>Tipo de respaldo</th>
			<th>Fecha/HoraInicial</th>
			<th>Fecha/Hora Final</th>
			<th>Tiempo de respaldo</th>
			<th></th>
		</tr>
		<tr class="trBD" ng-repeat="registro in bitRespCtes | filter:filtroRespaldos">
			<td>{{registro.nombreResp}}</td>
			<td>{{registro.tipoDeResp}}</td>
			<td class="centrado">{{registro.fechaInicialResp}}</td>
			<td class="centrado">{{registro.fechaFinalResp}}</td>
			<td class="fright" >{{registro.tiempoTranscurrido}} (horas)</td>
			<td class="centrado">
				<button title="Detalle del respaldo" ng-click="detalleBitResp(registro)"><span class="ui-icon ui-icon-clipboard"></span></button>
			</td>
		</tr>
	</table>
	<div id="dlgDetalleResp" title="Detelle del respaldo">
		<label>Cliente: </label><input type="text" value="{{ currentRegistro.nombreCte }}" readonly /> <br />
		<label>Nombre del respaldo: </label><input type="text" value="{{ currentRegistro.nombreResp }}" readonly /> <br />
		<label>Tipo de respaldo: </label><input type="text" value="{{ currentRegistro.tipoDeResp }}" readonly /> <br />
		<label>Origen: </label><input type="text" value="{{ currentRegistro.origen }}" readonly /> <br />
		<label>Destino: </label><input type="text" value="{{ currentRegistro.destino }}" readonly /> <br />
		<label>Destino remoto: </label><input type="text" value="{{ currentRegistro.destinoRemoto }}" readonly /> <br />
		<label>Fecha inicial: </label><input type="text" value="{{ currentRegistro.fechaInicialResp }}" readonly /> <br />
		<label>Fecha inicial: </label><input type="text" value="{{ currentRegistro.fechaFinalResp}}" readonly /> <br />
		<label>Tiempo de respaldo: </label><input type="text" value="{{ currentRegistro.tiempoTranscurrido}} (horas)" readonly /> <br />
		<label>Contenido: </label>
		<textarea cols="75" readonly >{{ currentRegistro.detalleResp }} </textarea>
	</div>
</div>

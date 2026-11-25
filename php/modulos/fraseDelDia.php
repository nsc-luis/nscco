<div id="infoSesion">
	<span class="fleft infoVentana">INFORMES RAPIDOS</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php echo $_SESSION['empresa']; ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php echo $_SESSION['nombre']; ?></span>
</div>
<div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div>
<!-- <div id="contenido" class="fleft">
	<script type="text/javascript" charset="iso-8859-1" language="javascript" src="http://www.proverbia.net/webmaster/frase.asp?op=0&amp;ref="></script>
	<p class="cita">
	</p> <br />
	<p class="autorcita"></p>
</div> -->

<div ng-controller="statusLicencias">
	<div id="statusInventario">
		<ul>
			<li><a href="#licenciasCaducidad15dias">Licencias que caducan en 15 dias.</a></li>
			<li><a href="#officeCaducados">Office caducados</a></li>
			<li><a href="#avCaducados">Antivirus caducados</a></li>
			<li><a href="#inventarioSinAsignacion">Inventario sin asignacion Office/Antivirus</a></li>
		</ul>
		<div id="licenciasCaducidad15dias">
			<table class="modTb">
				<tr>
					<th>ID</th>
					<th>Cliente</th>
					<th>Tipo</th>
					<th>Nombre</th>
					<th>Caducidad</th>
				</tr>
				<tr class="trBD" ng-repeat="lic in licenciasCaducidad15dias">
					<td>{{lic.idlicencia}}</td>
					<td>{{lic.nombreCte}}</td>
					<td>{{lic.tipoLicencia}}</td>
					<td>{{lic.nombre}}</td>
					<td>{{lic.fechacaducidad}}</td>
				</tr>
			</table>
		</div>
		<div id="officeCaducados">
			<table class="modTb">
				<tr>
					<th>ID</th>
					<th>Cliente</th>
					<th>Tipo</th>
					<th>Nombre</th>
					<th>Caducidad</th>
				</tr>
				<tr class="trBD" ng-repeat="o in officeCaducados">
					<td>{{o.idlicencia}}</td>
					<td>{{o.nombreCte}}</td>
					<td>{{o.tipoLicencia}}</td>
					<td>{{o.nombre}}</td>
					<td>{{o.fechacaducidad}}</td>
				</tr>
			</table>
		</div>
		<div id="avCaducados">
			<table class="modTb">
				<tr>
					<th>ID</th>
					<th>Cliente</th>
					<th>Tipo</th>
					<th>Nombre</th>
					<th>Caducidad</th>
				</tr>
				<tr class="trBD" ng-repeat="av in avCaducados">
					<td>{{av.idlicencia}}</td>
					<td>{{av.nombreCte}}</td>
					<td>{{av.tipoLicencia}}</td>
					<td>{{av.nombre}}</td>
					<td>{{av.fechacaducidad}}</td>
				</tr>
			</table>
		</div>
		<div id="inventarioSinAsignacion">
			<table class="modTb">
				<tr>
					<th>Hostname</th>
					<th>Cliente</th>
					<th>Oficina</th>
				</tr>
				<tr class="trBD" ng-repeat="inv in inventarioSinAsignacion">
					<td>{{inv.Host}}</td>
					<td>{{inv.nombreCte}}</td>
					<td>{{inv.nombreSucCte}}</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<!-- <div id="fastInfo" ng-controller="menuPrincipal">
	<h4>LICENCIAS DE ANTIVIRUS CADUCADOS O PROXIMOS A CADUCAR: <a href="#" class="noAVPorCaducar" ng-click="rptCaducidadAvUnaSemana()"> 23 </a></h4>
	<h4>LICENCIAS OFFICE 365 CADUCADAS O PROXIMAS A CADUCAR: <strong><a href="#">X</strong></a></h4>
	<h4>LICENCIAS CONTPAQ i CADUCADAS O PROXIMAS A CADUCAR: <strong><a href="#">Y</strong></a></h4>
	<h4>RESPALDOS INCOMPLETOS DE CLIENTES: <strong><a href="#">Z</a></strong></h4>
</div>
<hr />
<div ng-controller="ultimos3Respaldos">
	<div ng-repeat="empresas in ultimos3Respaldos['empresas']">
		<div style="border-bottom: 1px solid black">
			<strong> {{ empresas.nombreCte }} </strong>
			<ul>
				<li ng-repeat="respaldos in empresas['respaldos']" > <span style="font-style: italic; text-decoration: underline; font-weight: bold;">::: {{ respaldos.nombreResp }} :::</span>
					<table>
						<tr>
							<th>Tipo</th>
							<th>Fecha inicial</th>
							<th>Fecha final</th>
							<th>Status</th>
							<th></th>
						</tr>
						<tr class="trBD" ng-repeat="registros in respaldos['registros']">
							<td>{{ registros.tipoDeResp }}</td>
							<td>{{ registros.fechaInicialResp }}</td>
							<td>{{ registros.fechaFinalResp }}</td>
							<td>{{ registros.detalleResp | statusBitRespFilter }}</td>
							<td>
								<button title="Detalle del respaldo" ng-click="detalleBitResp(registros)"><span class="ui-icon ui-icon-clipboard"></span></button>
							</td>
						</tr>
					</table>
				</li>
			</ul>
		</div>
	</div>
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
</div> -->
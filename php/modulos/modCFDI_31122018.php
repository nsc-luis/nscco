<script src="js/fnModCFDI.js" language="javascript"></script>
<div id="infoSesion">
	<span class="fleft infoVentana">Documentos CFDI (.xml)</span>
	<span class="fright"><strong>Fecha:</strong> <?php echo date("F j, Y"); ?></span><br />
	<span class="fright"><strong>Empresa:</strong> <?php echo $_SESSION['empresa']; ?></span><br />
	<span class="fright"><strong>Usuario:</strong> <?php echo $_SESSION['nombre']; ?></span>
</div>
<!-- <div id="barraLateral" class="fleft">
	<strong>TAREAS ASIGNADAS.</strong>
</div> -->
<div id="contenido" class="fleft" ng-controller="modCFDI">
	<div id="accoFiltroCargaXml">
		<h3><strong>Cargar documentos CFDI</strong></h3>
		<div>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p>
					<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
					<strong>Aviso!</strong> Solo se permiten cargar 50 documentos con validación y hasta 100 CFDI sin validar.
				</p>
			</div>
			<p>
				<input type="file" id="archivoXML" multiple /> <br />
				<input id="chkValidarCFDI" type="checkbox" disabled /><strong>Validar status CFDI. </strong>Este proceso puede demorar varios minutos y valida status: [vigente | cancelado].
			</p>
			<button id="btnCargaXML2" >Cargar documentos</button>
			<div id="result"></div>
		</div>
		<h3><strong>Filtrar documentos CFDI</strong></h3>
		<div>
			<label for="rfcProveedor">Nombre proveedor: (*)</label>
			<select id="rfcProvFiltro" ng-model="rfcProveedor">
				<option value="0">Todos</option>
				<option ng-repeat="proveedor in proveedores" value="{{ proveedor.rfcProveedor }}">{{ proveedor.nombreProveedor }}</option>
			</select> <br/ >
			<label for="fYear-fMes">Año/Mes: </label>
			<select id="fYear" ng-model="year">
				<option ng-repeat="year in yearsList" value="{{ year.year }}" >{{ year.year }}</option>
			</select>
			<select id="fMes" ng-model="mes">
				<option value="todos">Todos</option>
				<option value="01">Enero</option>
				<option value="02">Febrero</option>
				<option value="03">Marzo</option>
				<option value="04">Abril</option>
				<option value="05">Mayo</option>
				<option value="06">Junio</option>
				<option value="07">Julio</option>
				<option value="08">Agosto</option>
				<option value="09">Septiembre</option>
				<option value="10">Octubre</option>
				<option value="11">Noviembre</option>
				<option value="12">Diciembre</option>
			</select> <br/ >
			<!-- <label for="fUUID">UUID: </label>
			<input type="text" id="fUUID" validar="uuid" />Ej.: 4f382e44-95bf-4705-9641-1363740d0c4e <br /> -->
			<label for="fTipoDocto">Tipo de documento: </label>
			<input type="text" value="cfdiEgreso" readonly /> <br />
			<button ng-click="exportaListaCFDIExcel()" id="btnExpExcelCFDI2" >Exportar a Excel</button>
			<button id="btnFiltroCFDI2" ng-click="filtrarCFDIs()">Filtrar</button>
			<button id="btnValidarCFDIs" ng-click="">Validar CFDIs</button>
		</div>
	</div>
	<div id="listaCFDI">
		<input type="text" placeholder="Refinar filtro rapido" ng-model="refinarFiltroRapido" /> <br />
		<table class="modTb">
			<tr>
				<th>Folio</th>
				<th>RFC emisor</th>
				<th>Nombre emisor</th>
				<th>RFC receptor</th>
				<th>Nombre receptor</th>
				<th>Fecha</th>
				<th>ImporteTotal</th>
				<th>TipoDocto</th>
				<th>Status</th>
				<th></th>
			</tr>
			<tr class="trBD" ng-repeat="cfdi in cfdis | filter : refinarFiltroRapido">
				<td class="centrado">{{ cfdi.folioXML }}</td>
				<td>{{ cfdi.rfcEmisor }}</td>
				<td>{{ cfdi.nombreEmisor }}</td>
				<td>{{ cfdi.rfcReceptor }}</td>
				<td>{{ cfdi.nombreReceptor }}</td>
				<td class="centrado">{{ cfdi.fechaXML }}</td>
				<td class="fright">{{ cfdi.totalXML | currency }}</td>
				<td class="centrado">{{ cfdi.tipoDeDoctoXML }}</td>
				<td class="centrado">{{ cfdi.estadoCFDI }}</td>
				<td class="centrado">
					<button ng-click="archivoXML(cfdi)" title="Ver XML"><span class="ui-icon ui-icon-script"></span></button>
					<button ng-click="representacionCFDI(cfdi)" title="Ver repesentación impresa"><span class="ui-icon ui-icon-print"></span></button>
					<button ng-click="validaCFDI(cfdi)" title="Valida este CFDI"><span class="ui-icon ui-icon-check"></span></button>
				</td>
			</tr>
		</table>
	</div>
</div>
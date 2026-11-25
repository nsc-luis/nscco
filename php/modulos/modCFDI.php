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
	<div id="result"></div>
	<div id="accoFiltroCargaXml">
		<h3><strong>Cargar documentos CFDI</strong></h3>
		<div>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p>
					<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
					<strong>Aviso!</strong> Solo se permite la carga de hasta 350 XML a la vez. <br />
					El tiempo de carga dependera de la cantidad de documentos XML ingresados.
				</p>
			</div>
			<p>
				<input type="file" id="archivoXML" multiple /> <br />
				<!-- <input id="chkValidarCFDI" type="checkbox" disabled /><strong>Validar status CFDI. </strong>Este proceso puede demorar varios minutos y valida status: [vigente | cancelado]. -->
			</p>
			<button id="btnCargaXML2" >Cargar documentos</button>
		</div>
		<h3><strong>Filtrar documentos CFDI</strong></h3>
		<div>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p>
					<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
					<strong>Aviso!</strong> Por defecto se muestran los documentos del año y mes actual.
				</p>
			</div>
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
			</select> <br />
			<button id="btnFiltroCFDI2" ng-click="filtrarCFDIs()">Filtrar</button>
			<!-- 
			<button id="btnValidarCFDIs" ng-click="">Validar CFDIs</button>
			<button ng-click="exportaListaCFDIExcel()" id="btnExpExcelCFDI2" >Exportar a Excel</button>
			-->
		</div>
	</div>
	<div id="listaCFDI">
		<input type="text" placeholder="Refinar filtro rapido" ng-model="refinarFiltroRapido" /> <br />
		<table class="modTb">
			<tr>
				<!-- <th></th> -->
				<th>Folio</th>
				<th>RFC emisor</th>
				<th>Nombre emisor</th>
				<th>RFC receptor</th>
				<th>Nombre receptor</th>
				<th>Oficina</th>
				<th>Fecha</th>
				<th>Importe Total</th>
				<th>Tipo Docto</th>
				<th>Estado</th>
				<th style="width: 180px;"></th>
			</tr>
			<tr class="trBD" ng-repeat="cfdi in cfdis | filter : refinarFiltroRapido">
				<!-- <td><input style="width: 10px;" type="checkbox" /></td> -->
				<td class="centrado">{{ cfdi.folioXML }}</td>
				<td>{{ cfdi.rfcEmisor }}</td>
				<td>{{ cfdi.nombreEmisor }}</td>
				<td>{{ cfdi.rfcReceptor }}</td>
				<td>{{ cfdi.nombreReceptor }}</td>
				<td>{{ cfdi.nombreOfna }}</td>
				<td class="centrado">{{ cfdi.fechaXML }}</td>
				<td class="fright">{{ cfdi.totalXML | currency }}</td>
				<td class="centrado">{{ cfdi.tipoDeDoctoXML }}</td>
				<td class="centrado">{{ cfdi.estadoValidaCFDI }}</td>
				<td class="centrado">
					<button ng-click="archivoXML(cfdi)" title="Ver XML"><span class="ui-icon ui-icon-script"></span></button>
					<button ng-click="representacionCFDI(cfdi)" title="Ver repesentación impresa"><span class="ui-icon ui-icon-print"></span></button>
					<button ng-click="validaCFDI(cfdi)" title="Valida este CFDI"><span class="ui-icon ui-icon-check"></span></button>
					<button ng-click="archivoPDF(cfdi)" title="Ver PDF">PDF</button> <br />
					<!-- <button ng-click="dlgCargarPDF(cfdi)" title="Cargar PDF">PDF</span></button> -->
					<button ng-click="dlgPolizaOCPDF(cfdi)">Cargar datos extra</button>
				</td>
			</tr>
		</table>
	</div>
	<div id="dlgPolizaOCPDF" title="DATOS EXTRA">
		<!-- Usuario: {{ currentCFDI.nombreUsr }} <br />
		Oficina: {{ currentCFDI.nombreOfna }} <br /> -->
		UUID: {{ currentCFDI.uuid }} <br />
		Version CFDI: {{ currentCFDI.versionXML }} <br />
		Codigo de validacion: {{ currentCFDI.codigoValidaCFDI }} <br />
		<!-- <label style="width: 140px;">Poliza: </label>
		<input type="text" value="{{ currentCFDI.poliza }}" ng-model="currentCFDI.poliza" /><br />
		<label style="width: 140px;">Orden de compra: </label>
		<input type="text" value="{{ currentCFDI.ordenDeCompra }}" ng-model="currentCFDI.ordenDeCompra"/> <br /> -->
		<label style="width: 140px;">Cargar archivo PDF </label> <br />
		<input type="file" id="archivoPDF" title="Cargar PDF"/>
		<div id="resutlGuardarPolizaOCPDF"></div>
		<hr />
		<button id="btnGuardarPolizaOCPDF" ng-click="guardarPolizaOCPDF(currentCFDI)">Guardar datos extra</button>
	</div>
	<div id="modalValidaCFDI"></div>
</div>
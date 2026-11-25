<body>
	<div id="contenedor" ng-app="mainModule">
		<div id="cabecera">
			<span class="fleft"><img src="images/logo_nscco.png" /></span>
			<span class="fleft textoCabecera">Por Internet</span>
			<span class="textoCabecera fright salir"><a href="#" id="cerrarSesion">Salir</a></span>
			<!-- <span class="textoCabecera fright salir"><a href="?mod=empresas" id="cambioEmpresa">Empresa</a></span> -->
			<span class="textoCabecera fright salir"><a href="?mod=frase" id="cambioUsuario">Inicio</a></span>
		</div>
		<div id="menu" class="fclear" ng-controller="menuPrincipal">
			<ul>
				<li class="menuLv1 fleft textoCabecera"><a href="#">Catalogos</a>
					<ul class="menuLv2">
						<li><a href="?mod=clientes">Clientes</a></li>
						<li><a href="?mod=productos">Productos y servicios</a></li>
						<li><a href="?mod=unidades">Unidades</a></li>
						<!-- <li><a href="?mod=impuestos">Impuestos</a></li> -->
						<li><a href="?mod=licenciaDeSoftware">Licencias de software</a></li>
						<li><a href="?mod=tipoLicencia">Tipos de licencia</a></li>
						<li><a href="?mod=tipoDispositivo">Tipo de dispositivo</a></li>
						<li><a href="?mod=usuarios">Usuarios</a></li>
					</ul>
				</li>
				<li class="menuLv1 fleft textoCabecera"><a href="#">Movimientos</a>
					<ul class="menuLv2">
						<li><a href="?mod=ordenDeServicio">Orden de servicio</a></li>
						<!-- <li><a href="?mod=cotizaciones">Cotizaciones</a></li>
						<li><a href="#">Facturar <span class="fright"> >> </span> </a>
							<ul class="menuLv3">
								<li><a href="#">Nueva factura</a></li>
								<li><a href="#">Desde O. de servicio</a></li>
								<li><a href="#">Desde Contizacion</a></li>
							</ul>
						</li>
						<li><a href="?mod=tareas">Tareas</a></li> -->
						<li><a href="#" ng-click="defaultListaCFDIProv()">Doctos CFDI Proveedores</a>
						<!-- <li><a href="#" ng-click="defaultListaCFDICte()">Doctos CFDI Clientes</a> -->
							<!-- <ul class="menuLv3">
								<li><a href="?mod=verCFDIs">Cargar XML Emitido</a></li>
								<li><a href="?mod=cargarCFDI">Cargar XML Recibido</a></li>
							</ul> -->
						</li>
					</ul>
				</li>
				<!--  <li class="menuLv1 fleft textoCabecera"><a href="#">Reportes</a>
					<ul class="menuLv2">
						<li><a href="?mod=resporteOrdenServicio">O. de servicio <span class="fright"> >> </span></a>
							<ul class="menuLv3">
								<li><a href="#">Todos</a></li>
								<li><a href="#">Por fecha/producto</a></li>
								<li><a href="#">Por fecha/cliente</a></li>
								<li><a href="#">Por referencia (factura)</a></li>
							</ul>
						</li>
						<li><a href="?mod=resporteOrdenServicio">Documentos CFDI <span class="fright"> >> </span></a>
							<ul class="menuLv3">
								<li><a href="#">Todos</a></li>
								<li><a href="#">Facturas fecha/emitidas</a></li>
								<li><a href="#">Facturas fecha/proveedor</a></li>
								<li><a href="#">Utilidad por fecha</a></li>
							</ul>
						</li>
						<!-- <li><a href="?mod=reporteCotizacion">Cotizaciones<span class="fright"> >> </span></a>
							<ul class="menuLv3">
								<li><a href="#">Todas</a></li>
								<li><a href="#">Por folio</a></li>
								<li><a href="#">Por cliente</a></li>
								<li><a href="#">Por fecha</a></li>
							</ul>
						</li>
					</ul> -->
				<!-- </li> -->
				<li class="menuLv1 fleft textoCabecera"><a href="#">Respaldos</a>
					<ul class="menuLv2">
						<!-- <li><a href="?mod=respaldar">Respaldar base de datos</a></li> -->
						<li><a href="#" ng-click="dlgListaCtesRespaldos()">Verificar respaldo de clientes</a></li>
					</ul>
				</li>
				<li class="menuLv1 fleft textoCabecera"><a href="#" ng-click="dlgInventarioCte()">Inventarios</a></li>
				<li class="menuLv1 fleft textoCabecera"><a href="#">Reportes</a>
					<ul class="menuLv2">
						<li><a href="#" ng-click="dlgRptServXCte();" >Servicios por cliente</a></li>
						<li><a href="#" ng-click="dlgRptCadAV();" >Caducidad de antivirus por cliente</a></li>
						<li><a href="#" ng-click="dlgRptCadOffice();" >Caducidad de Office por cliente</a></li>
						<li><a href="#" ng-click="dlgRptCadOfficeAvPorFecha();" >Caducidad de Office/AV por cliente y fecha de caducidad</a></li>
						<!--<li><a href="#" ng-click="dlgRptInventarioCte();" >Inventario por cliente</a></li>-->
						<!--<li><a href="#" ng-click="rptCaducidadAvUnaSemana();" >Antivirus caducados o por caducar a 1 semana</a></li>-->
					</ul>
				</li>
				<!-- <li class="menuLv1 fleft textoCabecera"><a href="?mod=verActPaq">Versiones de paqueteria</a></li> -->
			</ul>
			<div id="dlgListaCtesRespaldos" title="Selecciona un cliente">
				<label style="width: 100px;">CLIENTE: </label>
				<select ng-model="cte.nombreCte">
					<option ng-repeat="cte in listaCtesRespaldos" value="{{ cte.nombreCte }}">{{ cte.nombreCte }}</option>
				</select>
				<hr />
				<button id="btnVerBitRespCte" ng-click="iraBitRespCte(cte.nombreCte)">Ver bitacora de respaldos</button>
			</div>
			<div id="dlgListaCtesInventario" title="Selecciona un cliente">
				<label style="width: 100px;">CLIENTE: </label>
				<select id="idCteInventario">
					<option ng-repeat="cte in listaCtesInventario" value="{{ cte.idCliente }}">{{ cte.nombre }}</option>
				</select>
				<hr />
				<button id="btnVerInvCte" ng-click="inventarioCte()">Ver inventario del cliente</button>
			</div>
			<div id="dlgRptServXCte" title="Servicios por cliente">
				<label>Cliente: </label>
				<select ng-model="cveCteRptxCte">
					<option ng-repeat="cte in listaClientes" value="{{cte.clave}}" > {{ cte.nombre }} </option>
				</select> <br />
				<label>Fecha inicial: </label>
				<input type="text" class="calendario" ng-model="fIniRtpxCte" /> <br />
				<label>Fecha final: </label>
				<input type="text" class="calendario" ng-model="fFinRtpxCte" />
				<hr />
				<button id="btnRptServXCte" ng-click="btnRptServXCte()">Ejecutar</button>
			</div>
			<div id="dlgRptCadAV" title="Reporte caducidad de AV">
				<label>Cliente: </label>
				<select ng-model="rptCadAVIdCte">
					<option ng-repeat="cte in listaCtesInventario" value="{{cte.idCliente}}" > {{ cte.nombre }} </option>
				</select> <br />
				<!-- <label>Ordenado por: </label>
				<select ng-model="rptLicCadOrdenPor">
					<option value="noAsignado">Computadora sin asignacion de licencia</option>
					<option value="computadora">Computadora</option>
					<option value="licencia">Licencia</option>
				</select> -->
				<hr />
				<button id="btnRptCadAV" ng-click="btnRptCadAV()">Ejecutar</button>
			</div>
			
			<div id="dlgRptCadOffice" title="Reporte caducidad de Office">
				<label>Cliente: </label>
				<select ng-model="rptCadOfficeIdCte">
					<option ng-repeat="cte in listaCtesInventario" value="{{cte.idCliente}}" > {{ cte.nombre }} </option>
				</select> <br />
				<label>Filtro: </label>
				<select ng-model="optFiltroFechaCaducidad" ng-change="chFiltroFechaCaducidad()">
					<option value="completo" > Completo </option>
					<option value="mayorIgualFecha"> Fecha registro </option>
				</select> <br />
				<div id="activaFiltroFechaCaducidad" hidden style="display: {{ displayFiltroFechaCaducidad }}">
					<label>Fecha registro >= </label>
					<input type="text" class="calendario" ng-model="filtroFechaCaducidad"/>
				</div>
				<!-- <label>Ordenado por: </label>
				<select ng-model="rptLicCadOrdenPor">
					<option value="noAsignado">Computadora sin asignacion de licencia</option>
					<option value="computadora">Computadora</option>
					<option value="licencia">Licencia</option>
				</select> -->
				<hr />
				<button id="btnRptCadAV" ng-click="btnRptCadOffice()">Ejecutar</button>
			</div>
			
			<div id="dlgRptCadOfficeAvPorFecha" title="Reporte caducidad de Office/AV por fecha y cliente">
				<label>Cliente: </label>
				<select ng-model="rptCadOfficeAvPorFechaIdCliente">
					<option ng-repeat="cte in listaClientes" value="{{cte.idCliente}}" > {{ cte.nombre }} </option>
				</select> <br />
				<label>A la fecha: </label>
				<input type="text" class="calendario" ng-model="rptCadOfficeAvPorFecha"/>
				<!-- <label>Ordenado por: </label>
				<select ng-model="rptLicCadOrdenPor">
					<option value="noAsignado">Computadora sin asignacion de licencia</option>
					<option value="computadora">Computadora</option>
					<option value="licencia">Licencia</option>
				</select> -->
				<hr />
				<button id="btnRptCadOfficeAvPorFecha" ng-click="btnRptCadOfficeAvPorFecha()">Ejecutar</button>
			</div>
			
		</div>
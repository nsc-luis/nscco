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
						<li><a href="?mod=verCFDIs" id="lnkCargarCFDI">Doctos CFDI</a>
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
						<li><a href="?mod=respaldar">Respaldar base de datos</a></li>
						<li><a href="#" ng-click="dlgListaCtesRespaldos()">Verificar respaldo de clientes</a></li>
					</ul>
				</li>
			</ul>
			<div id="dlgListaCtesRespaldos" title="Selecciona un cliente">
				<label style="width: 100px;">CLIENTE: </label>
				<select ng-model="cte.nombreCte">
					<option ng-repeat="cte in listaCtesRespaldos" value="{{ cte.nombreCte }}">{{ cte.nombreCte }}</option>
				</select>
				<hr />
				<button id="btnVerBitRespCte" ng-click="iraBitRespCte(cte.nombreCte)">Ver bitacora de respaldos</button>
			</div>
		</div>
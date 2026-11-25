<?php
	session_start();
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
	
	function resultadoDeConsulta ($msj, $accion, $div, $complementoUrl) {
		$url = "http://" . $_SERVER['SERVER_NAME'] . "/" . $complementoUrl;
		// $url .= $complementoUrl;
		if ($msj == "") {
			$goToUrl = $accion == "recargar" ? "location.reload();" : "window.location.assign('" . $url . "');";
			return $goToUrl;
		} else {
			$mensaje = "alert('" . $msj . "');";
			return $mensaje;
		}
	}
	
	$proceso = isset($_POST['proceso']) ? $_POST['proceso'] : $_GET['proceso'];
	switch ($proceso) {
		
		/********* CLIENTES *********/
		case "listaClientes":
			include_once ("cClientes.php");
			$cte = new Clientes($_SESSION['bd']);
			// $cte->bd->setNombreBD($_SESSION['bd']);
			
			$listaClientes = $cte->clientes();
			echo $json_info = json_encode($listaClientes);
			$cte->bd->cerrarBD();
		break;
		
		case "guardarNvoCte":
			include_once ("cClientes.php");
			$cte = new Clientes($_SESSION['bd']);
			// $cte->bd->setNombreBD($_SESSION['bd']);
			
			$cte->setClave($_POST['clave']);
			$cte->setNombre($_POST['nombre']);
			$cte->setRFC(isset($_POST['rfc']) ? $_POST['rfc'] : '');
			$cte->setDireccion(isset($_POST['direccion']) ? $_POST['direccion'] : '');
			$cte->setTelefono1(isset($_POST['telefono1']) ? $_POST['telefono1'] : '');
			$cte->setEmail(isset($_POST['email']) ? $_POST['email'] : '');
			$cte->setPoliza(isset($_POST['poliza']) ? $_POST['poliza'] : '0');
			$cte->setHorasServPresencial(isset($_POST['horasServPresencial']) ? $_POST['horasServPresencial'] : '0');
			$cte->setPrecioHoraPresencial(isset($_POST['precioHoraPresencial']) ? $_POST['precioHoraPresencial'] : '0');
			$cte->setHorasServRemoto(isset($_POST['horasServRemoto']) ? $_POST['horasServRemoto'] : '0');
			$cte->setPrecioHoraRemoto(isset($_POST['precioHoraRemoto']) ? $_POST['precioHoraRemoto'] : '0');
			
			// CAMPOS NO USADOS PERO QUE SE TIENE QUE "SETEAR"
			$cte->setCalle(isset($_POST['calle']) ? $_POST['calle'] : '');
			$cte->setNoExt(isset($_POST['noExt']) ? $_POST['noExt'] : '');
			$cte->setColonia(isset($_POST['colonia']) ? $_POST['colonia'] : '');
			$cte->setMunicipio(isset($_POST['municipio']) ? $_POST['municipio'] : '');
			$cte->setEstado(isset($_POST['estado']) ? $_POST['estado'] : '');
			$cte->setPais(isset($_POST['pais']) ? $_POST['pais'] : '');
			$cte->setCP(isset($_POST['cp']) ? $_POST['cp'] : '');
			
			$cte->nuevoCliente();
			
			echo "<script>" . resultadoDeConsulta($cte->getMsj(), "recargar", "msjCte", "") . "</script>";
			$cte->bd->cerrarBD();
		break;
		
		case "guardarCte":
			include_once ("cClientes.php");
			$cte = new Clientes($_SESSION['bd']);
			// $cte->bd->setNombreBD($_SESSION['bd']);
			
			$cte->setNombre($_POST['nombre']);
			$cte->setRFC($_POST['rfc']);
			$cte->setDireccion($_POST['direccion']);
			$cte->setTelefono1($_POST['telefono1']);
			$cte->setEmail($_POST['email']);
			$cte->setPoliza($_POST['poliza']);
			$cte->setHorasServPresencial($_POST['horasServPresencial']);
			$cte->setPrecioHoraPresencial($_POST['precioHoraPresencial']);
			$cte->setHorasServRemoto($_POST['horasServRemoto']);
			$cte->setPrecioHoraRemoto($_POST['precioHoraRemoto']);
			
			$cte->editaCliente($_POST['clave']);
			
			echo "<script>" . resultadoDeConsulta($cte->getMsj(), "recargar", "msjCte", "") . "</script>";
			$cte->bd->cerrarBD();
		break;
		
		case "borrarCte":
			include_once ("cClientes.php");
			$cte = new Clientes($_SESSION['bd']);
			// $cte->bd->setNombreBD($_SESSION['bd']);
			
			$cte->borraCliente($_POST['clave']);
			
			echo "<script>" . resultadoDeConsulta($cte->getMsj(), "recargar", "msjCte", "") . "</script>";
			$cte->bd->cerrarBD();
		break;
		
		//// SUCURSALES DEL CLIENTE ////
		case "listaOficinas":
			include_once ("cClientes.php");
			$cte = new Clientes($_SESSION['bd']);
			// $cte->bd->setNombreBD($_SESSION['bd']);
			
			$listaOficinas = $cte->listaOficinas();
	
			$data = array();
			if ($listaOficinas->rowCount() !=0) {
				while ($row = $listaOficinas->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$cte->bd->cerrarBD();
		break;
		
		case "getSucPorCte":
			include_once ("cClientes.php");
			$cte = new Clientes($_SESSION['bd']);
			// $cte->bd->setNombreBD($_SESSION['bd']);
			
			$getSucPorCte = $cte->getSucPorCte($_GET['clave']);
	
			$data = array();
			if ($getSucPorCte->rowCount() !=0) {
				while ($row = $getSucPorCte->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$cte->bd->cerrarBD();
		break;
		
		case "getSuCteXidCte":
			include_once ("cClientes.php");
			$cte = new Clientes($_SESSION['bd']);
			// $cte->bd->setNombreBD($_SESSION['bd']);
			
			$getSuCteXidCte = $cte->getSuCteXidCte($_GET['idCte']);
	
			$data = array();
			if ($getSuCteXidCte->rowCount() !=0) {
				while ($row = $getSuCteXidCte->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$cte->bd->cerrarBD();
		break;
		
		case "guardarSucCte":
			include_once ("cClientes.php");
			$cte = new Clientes($_SESSION['bd']);
			// $cte->bd->setNombreBD($_SESSION['bd']);
			
			$cte->setNomSucursal($_POST['nombre']);
			$cte->setDirSucCte(isset($_POST['dirSucCte']) ? $_POST['dirSucCte'] : '' );
			$cte->setTel1Suc(isset($_POST['telefono1']) ? $_POST['telefono1'] : '');
			
			$cte->editaSucCte($_POST['idSucursalCte']);
			
			echo "<script>" . resultadoDeConsulta($cte->getMsj(), "recargar", "msjCte", "") . "</script>";
			$cte->bd->cerrarBD();
		break;
		
		case "guardarNvaSucCte":
			include_once ("cClientes.php");
			$cte = new Clientes($_SESSION['bd']);
			// $cte->bd->setNombreBD($_SESSION['bd']);
			
			$cte->setCveCte($_POST['cveCte']);
			$cte->setNomSucursal($_POST['nombre']);
			$cte->setDirSucCte(isset($_POST['dirSucCte']) ? $_POST['dirSucCte'] : '');
			$cte->setTel1Suc(isset($_POST['telefono1']) ? $_POST['telefono1'] : '');
			$cte->setIdCliente($_POST['idCliente']);
			
			$cte->nvaSucCte();
			
			echo "<script>" . resultadoDeConsulta($cte->getMsj(), "recargar", "msjCte", "") . "</script>";
			$cte->bd->cerrarBD();
		break;
		
		case "borraSucCte":
			include_once ("cClientes.php");
			$cte = new Clientes($_SESSION['bd']);
			// $cte->bd->setNombreBD($_SESSION['bd']);
			
			$cte->setCveCte($_POST['cveCte']);
			$cte->borraSucCte($_POST['idSucursalCte']);
			
			echo "<script>" . resultadoDeConsulta($cte->getMsj(), "recargar", "msjCte", "") . "</script>";
			$cte->bd->cerrarBD();
		break;
	
		/********* PRODUCTOS *********/
		case "listaProductos":
			include_once ("cProductos.php");
			$prod = new Productos($_SESSION['bd']);
			// $prod->bd->setNombreBD($_SESSION['bd']);
			
			$listaProductos = $prod->productos();
			$data = array();
			if ($listaProductos->rowCount() !=0) {
				while ($row = $listaProductos->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$prod->bd->cerrarBD();
		break;
		
		case "getInfoProdBuscado":
			include_once ("cProductos.php");
			$prod = new Productos($_SESSION['bd']);
			// $prod->bd->setNombreBD($_SESSION['bd']);
			
			$getInfoProdBuscado = $prod->getInfoProdBuscado($_GET['cadena']);
			$data = array();
			if ($getInfoProdBuscado->rowCount() !=0) {
				while ($row = $getInfoProdBuscado->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$prod->bd->cerrarBD();
		break;
		
		case "getInfoProd":
			include_once ("cProductos.php");
			$prod = new Productos($_SESSION['bd']);
			// $prod->bd->setNombreBD($_SESSION['bd']);
			
			$getInfoProd = $prod->getInfoProd($_GET['cveProd']);
			$data = array();
			if ($getInfoProd->rowCount() !=0) {
				while ($row = $getInfoProd->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$prod->bd->cerrarBD();
		break;

		case "listaUnidades":
			include_once ("cProductos.php");
			$prod = new Productos($_SESSION['bd']);
			// $prod->bd->setNombreBD($_SESSION['bd']);
			
			$listaUnidades = $prod->unidades();
			$data = array();
			if ($listaUnidades->rowCount() !=0) {
				while ($row = $listaUnidades->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$prod->bd->cerrarBD();
		break;
		
		case "guardarNvoProd":
			include_once ("cProductos.php");
			$prod = new Productos($_SESSION['bd']);
			// $prod->bd->setNombreBD($_SESSION['bd']);
			
			$prod->setClave($_POST['clave']);
			$prod->setNombre($_POST['nombre']);
			$prod->setDescripcion(isset($_POST['descripcion']) ? $_POST['descripcion'] : '');
			$prod->setPrecio(isset($_POST['precio']) ? $_POST['precio'] : '0');
			$prod->setIdUnidad(isset($_POST['idUnidad']) ? $_POST['idUnidad'] : '1');
			$prod->setIdImpuesto("1");
			$prod->setProveedor(isset($_POST['proveedor']) ? $_POST['proveedor'] : '');
			$prod->setSkuProveedor(isset($_POST['skuProveedor']) ? $_POST['skuProveedor'] : '');
			
			$prod->nuevoProducto();
			
			echo "<script>" . resultadoDeConsulta($prod->getMsj(), "recargar", "msjProd", "") . "</script>";
			$prod->bd->cerrarBD();
		break;
		
		case "guardarProd":
			include_once ("cProductos.php");
			$prod = new Productos($_SESSION['bd']);
			// $prod->bd->setNombreBD($_SESSION['bd']);
			
			$prod->setNombre($_POST['nombre']);
			$prod->setDescripcion(isset($_POST['descripcion']) ? $_POST['descripcion'] : '');
			$prod->setPrecio(isset($_POST['precio']) ? $_POST['precio'] : '0');
			$prod->setIdUnidad(isset($_POST['idUnidad']) ? $_POST['idUnidad'] : '1');
			$prod->setIdImpuesto("1");
			$prod->setProveedor(isset($_POST['proveedor']) ? $_POST['proveedor'] : '');
			$prod->setSkuProveedor(isset($_POST['skuProveedor']) ? $_POST['skuProveedor'] : '');
			
			$prod->editaProducto($_POST['clave']);
			
			echo "<script>" . resultadoDeConsulta($prod->getMsj(), "recargar", "msjProd", "") . "</script>";
			$prod->bd->cerrarBD();
		break;
		
		case "borrarProd":
			include_once ("cProductos.php");
			$prod = new Productos($_SESSION['bd']);
			// $prod->bd->setNombreBD($_SESSION['bd']);
			
			$prod->borraProducto($_POST['clave']);
			
			echo "<script>" . resultadoDeConsulta($prod->getMsj(), "recargar", "msjProd", "") . "</script>";
			$prod->bd->cerrarBD();
		break;
		
		/********* USUARIOS *********/
		case "listaUsuarios":
			include_once ("cUsuarios.php");
			$usr = new Usuarios($_SESSION['bd']);
			// $usr->bd->setNombreBD($_SESSION['bd']);
			
			$listaUsuarios = $usr->usuarios($_SESSION['usuario']);
			$data = array();
			if ($listaUsuarios->rowCount() !=0) {
				while ($row = $listaUsuarios->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$usr->bd->cerrarBD();
		break;
		
		case "guardarNvoUsr":
			include_once ("cUsuarios.php");
			$usr = new Usuarios($_SESSION['bd']);
			// $usr->bd->setNombreBD($_SESSION['bd']);
			
			$usr->setUsuario($_POST['usuario']);
			$usr->setNombre($_POST['nombre']);
			$usr->setPassword($_POST['password']);
			$usr->setEmailUsr($_POST['emailUsr']);
			
			$usr->insertaUsuario();
			
			echo "<script>" . resultadoDeConsulta($usr->getMsj(), "recargar", "msjUsr", "") . "</script>";
			$usr->bd->cerrarBD();
		break;
		
		case "guardarUsr":
			include_once ("cUsuarios.php");
			$usr = new Usuarios($_SESSION['bd']);
			// $usr->bd->setNombreBD($_SESSION['bd']);
			
			$usr->setNombre($_POST['nombre']);
			$usr->setPassword($_POST['password']);
			$usr->setEmailUsr($_POST['emailUsr']);
			
			$usr->actualizaUsuario($_POST['usuario']);
			
			echo "<script>" . resultadoDeConsulta($usr->getMsj(), "recargar", "msjUsr", "") . "</script>";
			$usr->bd->cerrarBD();
		break;
		
		case "borrarUsr":
			include_once ("cUsuarios.php");
			$usr = new Usuarios($_SESSION['bd']);
			// $usr->bd->setNombreBD($_SESSION['bd']);
			
			$usr->borraUsuario($_POST['usuario'],$_SESSION['usuario']);
			
			echo "<script>" . resultadoDeConsulta($usr->getMsj(), "recargar", "msjUsr", "") . "</script>";
			$usr->bd->cerrarBD();
		break;
		
		/********* UNIDADES *********/
		case "listaUnidades":
			include_once ("cUnidades.php");
			$uni = new Unidades($_SESSION['bd']);
			// $uni->bd->setNombreBD($_SESSION['bd']);
			
			$listaUnidades = $uni->unidades($_SESSION['usuario']);
			$data = array();
			if ($listaUnidades->rowCount() !=0) {
				while ($row = $listaUnidades->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$uni->bd->cerrarBD();
		break;
		
		case "guardarNvaUni":
			include_once ("cUnidades.php");
			$uni = new Unidades($_SESSION['bd']);
			// $uni->bd->setNombreBD($_SESSION['bd']);
			
			$uni->setMedida($_POST['medida']);
			$uni->nuevaUni();
			
			echo "<script>" . resultadoDeConsulta($uni->getMsj(), "recargar", "msjUni", "") . "</script>";
			$uni->bd->cerrarBD();
		break;
		
		case "guardarUni":
			include_once ("cUnidades.php");
			$uni = new Unidades($_SESSION['bd']);
			// $uni->bd->setNombreBD($_SESSION['bd']);
			
			$uni->setMedida($_POST['medida']);
			$uni->editarUni($_POST['idUnidad']);
			
			echo "<script>" . resultadoDeConsulta($uni->getMsj(), "recargar", "msjUni", "") . "</script>";
			$uni->bd->cerrarBD();
		break;
		
		case "borrarUni":
			include_once ("cUnidades.php");
			$uni = new Unidades($_SESSION['bd']);
			// $uni->bd->setNombreBD($_SESSION['bd']);
			
			$uni->borraUnidad($_POST['idUnidad']);
			
			echo "<script>" . resultadoDeConsulta($uni->getMsj(), "recargar", "msjUni", "") . "</script>";
			$uni->bd->cerrarBD();
		break;
		
		/********* ORDENES DE SERVICIO *********/
		case "nvaOrdServ":
			include_once ("cOrdenDeServicio.php");
			$ods = new OrdenDeServicio($_SESSION['bd']);
			// $ods->bd->setNombreBD($_SESSION['bd']);
			
			$ods->nvaOrdServ($_SESSION['usuario']);
			$ods->bd->cerrarBD();
		break;
		
		case "tiposStatus":
			include_once ("cStatus.php");
			$stu = new Status($_SESSION['bd']);
			// $stu->bd->setNombreBD($_SESSION['bd']);
			
			$tiposStatus = $stu->tiposStatus();
			$data = array();
			if ($tiposStatus->rowCount() !=0) {
				while ($row = $tiposStatus->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$stu->bd->cerrarBD();
		break;
		
		case "listaDeOrdenes":
			include_once ("cOrdenDeServicio.php");
			$ods = new OrdenDeServicio($_SESSION['bd']);
			// $ods->bd->setNombreBD($_SESSION['bd']);
			
			$ods->setCveCte($_GET['cveCte']);
			$ods->setIdSucursalCte($_GET['idSucursalCte']);
			$ods->setIdStatus($_GET['idStatus']);
			$usuario = $_GET['usuario'] == "" ? $ods->setUsuario("Todos") : $ods->setUsuario($_GET['usuario']);
			
			$ordenesDeServicio = $ods->ordenesDeServicio($_SESSION['usuario'],$_GET['fechaInicial'],$_GET['fechaFinal']);
			//$ordenesDeServicio = $ods->ordenesDeServicio($_SESSION['usuario'],"10","1");
			$data = array();
			if ($ordenesDeServicio->rowCount() !=0) {
				while ($row = $ordenesDeServicio->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$ods->bd->cerrarBD();
		break;
		
		case "guardarOrdServ":
			include_once ("cOrdenDeServicio.php");
			$ods = new OrdenDeServicio($_SESSION['bd']);
			// $ods->bd->setNombreBD($_SESSION['bd']);
			
			if ( $_POST['folio'] != "0" ) {
				$folio = $_POST['folio'];
				// $ods->getInfoOrdServ($folio);
				// $idStatus = $ods->getIdStatus();
				$ods->setCveCte(strtoupper($_POST['cveCte']));
				$ods->setIdSucursalCte(strtoupper($_POST['idSucursalCte']));
				$ods->setFechaOrdServ(strtoupper($_POST['fechaOrdServ']));
				$ods->setTiempoInvertido(strtoupper($_POST['tiempoInvertido']));
				$ods->setUsuario(strtoupper($_POST['usuario']));
				$ods->setRecibidoPor(strtoupper($_POST['recibidoPor']));
				$ods->setObservacion(strtoupper($_POST['observacion']));
				$ods->setIdTipoDeServ($_POST['idTipoDeServ']);
				
				if ($_POST['idStatus'] != "1") {
					$ods->setMsj("Solo se pueden modificar ordenes de servicio con status: \"activo\"");
					echo "<script>" . resultadoDeConsulta($ods->getMsj(), "recargar", "frmPartidaOrdServ", "") . "</script>";
				} else {	
					$ods->editarOrdserv($folio);
					echo "<script>" . resultadoDeConsulta($ods->getMsj(), "otro", "frmPartidaOrdServ", "system.php?mod=ordenDeServicio") . "</script>";
				}
			} else {
				// $ods->setFolio(strtoupper($_POST['folio']));
				$ods->setIdTipoDocto(1);
				$ods->setCveCte(strtoupper($_POST['cveCte']));
				$ods->setIdSucursalCte(strtoupper($_POST['idSucursalCte']));
				$ods->setFechaOrdServ(strtoupper($_POST['fechaOrdServ']));
				$ods->setTiempoInvertido(strtoupper($_POST['tiempoInvertido']));
				$ods->setUsuario(strtoupper($_SESSION['usuario']));
				$ods->setRecibidoPor(strtoupper($_POST['recibidoPor']));
				$ods->setObservacion(strtoupper($_POST['observacion']));
				$ods->setIdTipoDeServ($_POST['idTipoDeServ']);
				$ods->setIdStatus(strtoupper($_POST['idStatus']));
				$ods->setIdStatus(strtoupper($_POST['idStatus']));
				
				$ods->guardarNvaOrdServ();
				
				$url = "http://" . $_SERVER['SERVER_NAME'] . "/system.php?mod=ordenDeServicio";
				echo "<SCRIPT>window.location='$url';</SCRIPT>";
			}
		break;
		
		case "borrarOrdServ":
			include_once ("cOrdenDeServicio.php");
			$ordServ = new OrdenDeServicio($_SESSION['bd']);
			// $ordServ->bd->setNombreBD($_SESSION['bd']);
			
			$ordServ->borrarOrdServ($_POST['folio']);
			
			echo "<script>" . resultadoDeConsulta($ordServ->getMsj(), "recargar", "frmOrdServ", "") . "</script>";
			$ordServ->bd->cerrarBD();
		break;
		
		case "guardaStatus":
			include_once ("cOrdenDeServicio.php");
			$ordServ = new OrdenDeServicio($_SESSION['bd']);
			// $ordServ->bd->setNombreBD($_SESSION['bd']);
			
			$ordServ->setIdStatus($_POST['idStatus']);
			$ordServ->setReferenciaFact($_POST['referenciaFact']);
			$ordServ->guardaStatus($_POST['folio']);
			
			echo "<script>" . resultadoDeConsulta($ordServ->getMsj(), "recargar", "frmOrdServ", "") . "</script>";
			$ordServ->bd->cerrarBD();
		break;
		
		case "cambiarStatusVarios":
			include_once ("cOrdenDeServicio.php");
			$ordServ = new OrdenDeServicio($_SESSION['bd']);
			// $ordServ->bd->setNombreBD($_SESSION['bd']);
			
			$folios = $_POST['folios'];
			$ordServ->setIdStatus($_POST['idStatusParaVarios']);
			$ordServ->setReferenciaFact($_POST['referenciaFactVarios']);
			
			foreach ($folios as $folio) {
				$ordServ->guardaStatus($folio);
			}
			
			echo "<script>" . resultadoDeConsulta($ordServ->getMsj(), "recargar", "frmOrdServ", "") . "</script>";
			$ordServ->bd->cerrarBD();
		break;
		
		case "currentOrdenDeServ":
			include_once ("cOrdenDeServicio.php");
			$ods = new OrdenDeServicio($_SESSION['bd']);
			// $ods->bd->setNombreBD($_SESSION['bd']);
			
			$ordenDeServicio = $ods->getInfoOrdServNva($_GET['folio'],$_GET['usuario']);
			$data = array();
			if ($ordenDeServicio->rowCount() !=0) {
				while ($row = $ordenDeServicio->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$ods->bd->cerrarBD();
		break;
		
		case "tiposDeServ":
			include_once ("cTipoDeServ.php");
			$tds = new TipoDeServicio($_SESSION['bd']);
			// $tds->bd->setNombreBD($_SESSION['bd']);
			
			$tiposDeServicio = $tds->tiposDeServ();
			$data = array();
			if ($tiposDeServicio->rowCount() !=0) {
				while ($row = $tiposDeServicio->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$tds->bd->cerrarBD();
		break;
		
		/********* MOVTOS DE PARTIDAS *********/
		
		case "movtosPartidasOrdServ":
			include_once ("cMovtosPartidas.php");
			$mvtos = new MovtosPartidas($_SESSION['bd']);
			// $mvtos->bd->setNombreBD($_SESSION['bd']);
			
			$movtosPartidasOrdServ = $mvtos->movtosPartidasOrdServ($_GET['idTipoDocto'],$_GET['folio'],$_GET['usuario']);
			$data = array();
			if ($movtosPartidasOrdServ->rowCount() !=0) {
				while ($row = $movtosPartidasOrdServ->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$mvtos->bd->cerrarBD();
		break;
		
		case "nvaPartidaOrdServ":
			include_once ("cMovtosPartidas.php");
			$mvtos = new MovtosPartidas($_SESSION['bd']);
			// $mvtos->bd->setNombreBD($_SESSION['bd']);
			
			$mvtos->setIdTipoDocto($_GET['idTipoDocto']);
			$mvtos->setFolioDocto($_GET['folio']);
			$mvtos->setUsuario($_GET['usuario']);
			$mvtos->setCveProd($_GET['clave']);
			$mvtos->setIdUnidad($_GET['idUnidad']);
			$mvtos->setDetalle($_GET['descripcion']);
			$mvtos->setCantidad($_GET['cantidad']);
			$mvtos->setPrecioUnitario($_GET['precioUnitario']);
			
			$nvaPartidaOrdServ = $mvtos->nvaPartidaOrdServ();
			
			echo "<script>" . resultadoDeConsulta($mvtos->getMsj(), "recargar", "frmPartidaOrdServ", "") . "</script>";
			
			$mvtos->bd->cerrarBD();
		break;
		
		case "borrarPartida":
			include_once ("cMovtosPartidas.php");
			$mvtos = new MovtosPartidas($_SESSION['bd']);
			// $mvtos->bd->setNombreBD($_SESSION['bd']);
			
			$mvtos->borrarPartOrdServ($_POST['idMovto']);
			
			echo "<script>" . resultadoDeConsulta($mvtos->getMsj(), "recargar", "frmPartidaOrdServ", "") . "</script>";
			
			$mvtos->bd->cerrarBD();
		break;
		
		case "guardarPartida":
			include_once ("cMovtosPartidas.php");
			$mvtos = new MovtosPartidas($_SESSION['bd']);
			// $mvtos->bd->setNombreBD($_SESSION['bd']);
			
			$mvtos->setDetalle($_POST['detalle']);
			$mvtos->setCantidad($_POST['cantidad']);
			$mvtos->setPrecioUnitario($_POST['precioUnitario']);
			
			$mvtos->edDetallePartOrdServ($_POST['idMovto']);
			
			echo "<script>" . resultadoDeConsulta($mvtos->getMsj(), "recargar", "frmPartidaOrdServ", "") . "</script>";
			
			$mvtos->bd->cerrarBD();
		break;
		
		/********* BITACORA DE RESPALDOS CON CLIENTES *********/
		
		case "listaCtesRespaldos":
			include_once ("cRespaldosCtes.php");
			$rCtes = new RespaldoCtes('respctes');
			// $rCtes->bd->setNombreBD("respctes");
			$listaCtesRespaldos = $rCtes->listaCtesRespaldos();
			
			$data = array();
			if ($listaCtesRespaldos->rowCount() !=0) {
				while ($row = $listaCtesRespaldos->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$rCtes->bd->cerrarBD();
		break;
		case "bitRespCtes":
			include_once ("cRespaldosCtes.php");
			$rCtes = new RespaldoCtes('respctes');
			// $rCtes->bd->setNombreBD("respctes");
			$bitRespCtes = $rCtes->bitRespCtes($_GET['nombreCte']);
			
			$data = array();
			if ($bitRespCtes->rowCount() !=0) {
				while ($row = $bitRespCtes->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$rCtes->bd->cerrarBD();
		break;
		
		/********* MODULO CFDI *********/
		
		case "cfdis":
			include_once ("cDoctoCFDI.php");
			$cfdi = new DoctoCFDI($_SESSION['bd']);
			// $cfdi->bd->setNombreBD($_SESSION['bd']);
			// $cfdis = $cfdi->cfdis($_GET['rfcProveedor'],$_GET['fecha'],$_GET['tipoDocto']);
			$cfdis = $cfdi->cfdis($_GET);
			
			$data = array();
			if ($cfdis->rowCount() !=0) {
				while ($row = $cfdis->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$cfdi->bd->cerrarBD();
		break;
		
		case "proveedores":
			include_once ("cProveedores.php");
			$prov = new Proveedores($_SESSION['bd']);
			// $prov->bd->setNombreBD($_SESSION['bd']);
			$proveedores = $prov->proveedores();
			
			$data = array();
			if ($proveedores->rowCount() !=0) {
				while ($row = $proveedores->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$prov->bd->cerrarBD();
		break;
		
		case "yearsList":
			include_once ("cDoctoCFDI.php");
			$cfdi = new DoctoCFDI($_SESSION['bd']);
			// $cfdi->bd->setNombreBD($_SESSION['bd']);
			$yearsList = $cfdi->yearsList();
			
			$data = array();
			if ($yearsList->rowCount() !=0) {
				while ($row = $yearsList->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$cfdi->bd->cerrarBD();
		break;
		
		case "guardarPolizaOrdenCompra":
			include_once ("cDoctoCFDI.php");
			$cfdi = new DoctoCFDI($_SESSION['bd']);
			//$cfdi->setOrdenDeCompra($_POST['ordenDeCompra']);
			//$cfdi->setPoliza($_POST['poliza']);
			///$cfdi->setFechaPago($_POST['fechaPago']);
			
			//$cfdi->guardarPolizaOrdenCompra($_POST['idXML']);
			$cfdi->bd->cerrarBD();
		break;
		
		case "guardarPDF":
			include_once ("cDoctoCFDI.php");
			$cfdi = new DoctoCFDI($_SESSION['bd']);
			
			$fp = fopen($_FILES['archivoPDF']['tmp_name'],"r");
			$dataFile = fread($fp, $_FILES['archivoPDF']['size']);
			$dataFile = addslashes($dataFile);
			// $dataFile = base64_encode($dataFile);
			fclose($fp);
			
			$cfdi->setArchivoPDF($dataFile);
			$cfdi->setMimeArchivoPDF($_FILES['archivoPDF']['type']);
			$cfdi->setSizeArchivoPDF($_FILES['archivoPDF']['size']);
			try {
				$cfdi->guardarPDF($_POST['idXML']);
				echo "Ok!";
			}
			catch (Exception $e) {
				echo 'Error: ' . $e->getMessage();
			}
			
			$cfdi->bd->cerrarBD();
		break;
		
		case "validaCFDI":
			include_once ("cDoctoCFDI.php");
			$cfdi = new DoctoCFDI($_SESSION['bd']);
			// $cfdi->bd->setNombreBD($_SESSION['bd']);
			$idDocto = $_POST['idXML'];
			$emisor = $_POST['rfcEmisor'];
			$receptor = $_POST['rfcReceptor'];
			$total = $_POST['totalXML'];
			$uuid = $_POST['uuid'];
			//
			$soap = '
				<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
					<soapenv:Header/>
					<soapenv:Body>
						<tem:Consulta>
							<tem:expresionImpresa>
								?re='.$emisor.'&amp;rr='.$receptor.'&amp;tt='.$total.'&amp;id='.$uuid.'
							</tem:expresionImpresa>
						</tem:Consulta>
					</soapenv:Body>
				</soapenv:Envelope>
			';
			//encabezados
			$headers = [
				'Content-Type: text/xml;charset=utf-8',
				'SOAPAction: http://tempuri.org/IConsultaCFDIService/Consulta',
				'Content-length: '.strlen($soap)
			];

			$url = 'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $soap);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$res = curl_exec($ch);
			curl_close($ch);
			$xml = simplexml_load_string($res);
			$data = $xml->children('s', true)->children('', true)->children('', true);
			$data = json_encode($data->children('a', true), JSON_UNESCAPED_UNICODE);
			// print_r(json_decode($data));
			$data2 = json_decode($data);
			// echo $data2->Estado;
			
			$cfdi->setCodigoValidaCFDI($data2->CodigoEstatus);
			$cfdi->setEstadoValidaCFDI($data2->Estado);
			$cfdi->validaCFDI($idDocto);
			$cfdi->bd->cerrarBD();
			// echo "<script> location.reload(); </script>";
			// echo "<script> $('#modalValidaCFDI').dialog('close'); </script>";
			echo $data2->Estado;
		break;
		
		/********* MODULO INVENTARIOS *********/
		
		case "listaCtesInventario":
			try {
				include_once ("cInventarios.php");
				$inv = new Inventarios($_SESSION['bd']);
				// $inv->bd->setNombreBD($_SESSION['bd']);
				$listaCtesInventario = $inv->listaCtesInventario();
				
				echo $json_info = json_encode($listaCtesInventario);
				//$inv->bd->cerrarBD();
			}
			catch (Exception $e) {
				echo $e->getMessage();
			}
		break;
		
		case "listaDispositivos":
			include_once ("cInventarios.php");
			$inv = new Inventarios($_SESSION['bd']);
			// $inv->bd->setNombreBD($_SESSION['bd']);
			
			$inventario = $inv->inventario($_GET['id']);
			//echo "<pre>";print_r($inventario);echo "</pre>";
			echo $json_info = json_encode($inventario); 
			$inv->bd->cerrarBD();
		break;
		
		
		case "licAsingPorDisp":
			include_once ("cInventarios.php");
			$inv = new Inventarios($_SESSION['bd']);
			// $inv->bd->setNombreBD($_SESSION['bd']);
			
			$licAsingPorDisp = $inv->licAsingPorDisp($_GET['id']);
			$data = array();
			if ($licAsingPorDisp->rowCount() !=0) {
				while ($row = $licAsingPorDisp->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			echo $json_info = json_encode($data);
			$inv->bd->cerrarBD();
		break;
		
		
		case "guardarDisp":
			include_once ("cInventarios.php");
			$inv = new Inventarios($_SESSION['bd']);
			// $inv->bd->setNombreBD($_SESSION['bd']);
			
			$inv->setObservaciones($_POST['Observaciones']);
			$inv->setIdsucursalcte($_POST['idSucursalCte']);
			$inv->setUsuario($_POST['usuario']);
			$inv->setDepartamento($_POST['departamento']);
			$inv->setSerie($_POST['serie']);
			$fechaNva = explode("T",$_POST['fecharegistro']);
			$inv->setFechaRegistro($fechaNva[0]);
			
			$idLicAV = isset($_POST['idLicAV']) ? $_POST['idLicAV'] : 2;
			$idLicOffice = isset($_POST['idLicOffice']) ? $_POST['idLicOffice'] : 3;
			
			/* echo "<pre>";
			print_r($_POST);
			echo "</pre>"; */
			
			$inv->guardarDisp($_POST['idInventario'], $idLicAV, $idLicOffice);
			
			echo "<script>" . resultadoDeConsulta($inv->getMsj(), "recargar", "msjInv", "") . "</script>";
			
			$inv->bd->cerrarBD();
		break;
		
		case "borrarDisp":
			include_once ("cInventarios.php");
			$inv = new Inventarios($_SESSION['bd']);
			// $inv->bd->setNombreBD($_SESSION['bd']);
			
			$inv->borrarDisp($_POST['idInventario']);
			
			echo "<script>" . resultadoDeConsulta($inv->getMsj(), "recargar", "msjInv", "") . "</script>";
			
			$inv->bd->cerrarBD();
		break;
		
		case "ultimos3Respaldos":
			include_once ("cRespaldosCtes.php");
			$resp = new RespaldoCtes('respctes');
			// $resp->bd->setNombreBD("respctes");
			
			date_default_timezone_set();
			echo (date());
			
			$last3bkup = $resp->ultimos3Respaldos();
			
			/* echo "<pre>";
			print_r($last3bkup);
			echo "</pre>"; */
			
			echo $json_info = json_encode($last3bkup);
			
			$resp->bd->cerrarBD();
		break;
		
		case "infoLicencia":
			include_once("cInventarios.php");
			$inv = new Inventarios($_SESSION['bd']);
			// $inv->bd->setNombreBD($_SESSION['bd']);
			
			$infoLicencia = $inv->getInfoLicencia($_GET['idAV']);
			
			echo $json_info = json_encode($infoLicencia);
			$inv->bd->cerrarBD();
		break;
		
		case "guardarCambiosAV":
			include_once("cInventarios.php");
			$inv = new Inventarios($_SESSION['bd']);
			// $inv->bd->setNombreBD($_SESSION['bd']);
			
			$inv->setFechaCaducidad($_GET['fechacaducidad']);
			$inv->setObsLicencia($_GET['observaciones']);
			$inv->setIdLicencia($_GET['idlicencia']);
			
			$inv->guardarCambiosAV();
		break;
		
		case "licenciasCaducidad15dias":
			include_once("cInventarios.php");
			$inv = new Inventarios($_SESSION['bd']);
			// // $inv->bd->setNombreBD($_SESSION['bd']);
			
			$licenciasCaducidad15dias = $inv->licenciasCaducidad15dias();
			
			echo $json_info = json_encode($licenciasCaducidad15dias);
			$inv->bd->cerrarBD();
		break;
		
		case "officeCaducados":
			include_once("cInventarios.php");
			$inv = new Inventarios($_SESSION['bd']);
			// // $inv->bd->setNombreBD($_SESSION['bd']);
			
			$officeCaducados = $inv->officeCaducados();
			
			echo $json_info = json_encode($officeCaducados);
			$inv->bd->cerrarBD();
		break;
		
		case "avCaducados":
			include_once("cInventarios.php");
			$inv = new Inventarios($_SESSION['bd']);
			// // $inv->bd->setNombreBD($_SESSION['bd']);
			
			$avCaducados = $inv->avCaducados();
			
			echo $json_info = json_encode($avCaducados);
			$inv->bd->cerrarBD();
		break;
		
		case "inventarioSinAsignacion":
			include_once("cInventarios.php");
			$inv = new Inventarios($_SESSION['bd']);
			// // $inv->bd->setNombreBD($_SESSION['bd']);
			
			$inventarioSinAsignacion = $inv->inventarioSinAsignacion();
			
			echo $json_info = json_encode($inventarioSinAsignacion);
			$inv->bd->cerrarBD();
		break;
		
		/********* TIPO DE LICENCIA *********/
		
		case "listaTipoLicencia":
			include_once ("cTipoDeLicencia.php");
			$tipoLic = new TipoDeLicencia($_SESSION['bd']);
			// $tipoLic->bd->setNombreBD($_SESSION['bd']);
			$listaTipoLicencia = $tipoLic->listaTipoLicencia();
			
			$data = array();
			if ($listaTipoLicencia->rowCount() !=0) {
				while ($row = $listaTipoLicencia->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			echo $json_info = json_encode($data);
			$tipoLic->bd->cerrarBD();
		break;
		
		case "guardarNvoTipoLic":
			include_once ("cTipoDeLicencia.php");
			$tipoLic = new TipoDeLicencia($_SESSION['bd']);
			// $tipoLic->bd->setNombreBD($_SESSION['bd']);
			
			$tipoLic->setNombre($_POST['nombre']);
			$tipoLic->guardarNvoTipoLic();
			
			echo "<script>" . resultadoDeConsulta($tipoLic->getMsj(), "recargar", "tipoLicencia", "") . "</script>";
			
			$tipoLic->bd->cerrarBD();
		break;
		
		case "guardarTipoLic":
			include_once ("cTipoDeLicencia.php");
			$tipoLic = new TipoDeLicencia($_SESSION['bd']);
			// $tipoLic->bd->setNombreBD($_SESSION['bd']);
			
			$tipoLic->setNombre($_POST['nombre']);
			$tipoLic->guardarTipoLic($_POST['idtipolicencia']);
			
			echo "<script>" . resultadoDeConsulta($tipoLic->getMsj(), "recargar", "tipoLicencia", "") . "</script>";
			
			$tipoLic->bd->cerrarBD();
		break;
		
		case "borrarTipoLic":
			include_once ("cTipoDeLicencia.php");
			$tipoLic = new TipoDeLicencia($_SESSION['bd']);
			// $tipoLic->bd->setNombreBD($_SESSION['bd']);
			
			$tipoLic->borrarTipoLic($_POST['idtipolicencia']);
			
			echo "<script>" . resultadoDeConsulta($tipoLic->getMsj(), "recargar", "tipoLicencia", "") . "</script>";
			
			$tipoLic->bd->cerrarBD();
		break;
		
		/********* LICENCIA DE SOFTWARE *********/
		
		case "listaLicenciaSoftware":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			$listaLicenciaSoftware = $lic->listaLicenciaSoftware();
			
			$data = array();
			if ($listaLicenciaSoftware->rowCount() !=0) {
				while ($row = $listaLicenciaSoftware->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			echo $json_info = json_encode($data);
			$lic->bd->cerrarBD();
		break;
		
		case "listaCteConLicencias":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			$listaCteConLicencias = $lic->listaCteConLicencias();
			
			$data = array();
			if ($listaCteConLicencias->rowCount() !=0) {
				while ($row = $listaCteConLicencias->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			echo $json_info = json_encode($data);
			$lic->bd->cerrarBD();
		break;
		
		case "listaCtesParaLic":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			$listaCtesParaLic = $lic->listaCtesParaLic();
			
			$data = array();
			if ($listaCtesParaLic->rowCount() !=0) {
				while ($row = $listaCtesParaLic->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			echo $json_info = json_encode($data);
			$lic->bd->cerrarBD();
		break;
		
		case "listaTipoLic":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			$listaTipoLic = $lic->listaTipoLic();
			
			$data = array();
			if ($listaTipoLic->rowCount() !=0) {
				while ($row = $listaTipoLic->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			echo $json_info = json_encode($data);
			$lic->bd->cerrarBD();
		break;
		
		case "guardarNvaLic":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			
			$lic->setIdTipoLicencia($_POST['idTipoLicencia']);
			$lic->setNombre($_POST['nombreLic']);
			$lic->setFechaRegistro($_POST['fechaRegistro']);
			$lic->setFechaCaducidad($_POST['fechaCaducidad']);
			$lic->setObservaciones($_POST['observaciones']);
			$lic->setIdCliente($_POST['idCliente']);
			$lic->setSerie($_POST['serie']);
			$lic->guardarNvaLic();
			
			echo "<script>" . resultadoDeConsulta($lic->getMsj(), "recargar", "tipoLicencia", "") . "</script>";
			
			$lic->bd->cerrarBD();
		break;
		
		case "guardarLic":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			
			$lic->setIdTipoLicencia($_POST['idTipoLicencia']);
			$lic->setNombre($_POST['nombreLic']);
			$lic->setFechaRegistro($_POST['fechaRegistro']);
			$lic->setFechaCaducidad($_POST['fechaCaducidad']);
			$lic->setObservaciones($_POST['observaciones']);
			$lic->setIdCliente($_POST['idCliente']);
			$lic->setSerie($_POST['serie']);
			$lic->guardarLic($_POST['idlicencia']);
			
			echo "<script>" . resultadoDeConsulta($lic->getMsj(), "recargar", "tipoLicencia", "") . "</script>";
			
			$lic->bd->cerrarBD();
		break;
		
		case "borrarLic":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			
			$lic->borrarLic($_POST['idlicencia']);
			
			echo "<script>" . resultadoDeConsulta($lic->getMsj(), "recargar", "tipoLicencia", "") . "</script>";
			
			$lic->bd->cerrarBD();
		break;
		
		case "listLicToAssign":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			
			$listLicToAssign = $lic->listLicToAssign();
			
			$data = array();
			if ($listLicToAssign->rowCount() !=0) {
				while ($row = $listLicToAssign->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			echo $json_info = json_encode($data);
			$lic->bd->cerrarBD();
		break;
		
		case "listaDeOffice":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			
			$listaDeOffice = $lic->listaDeOffice();
			
			$data = array();
			if ($listaDeOffice->rowCount() !=0) {
				while ($row = $listaDeOffice->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			echo $json_info = json_encode($data);
			$lic->bd->cerrarBD();
		break;
		
		case "listaDeAV":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			
			$listaDeAV = $lic->listaDeAV();
			
			$data = array();
			if ($listaDeAV->rowCount() !=0) {
				while ($row = $listaDeAV->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			echo $json_info = json_encode($data);
			$lic->bd->cerrarBD();
		break;
		
		case "dispAsignados":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			
			$dispAsignados = $lic->dispAsignados($_GET['idLic']);
			
			echo $json_info = json_encode($dispAsignados);
			$lic->bd->cerrarBD();
		break;
		
		/********* TIPO DE DISPOSITIVO *********/
		
		case "listaTipoDispositivo":
			include_once ("cTipoDeDispositivo.php");
			$tipoDisp = new TipoDeDispositivo($_SESSION['bd']);
			// $tipoDisp->bd->setNombreBD($_SESSION['bd']);
			$listaTipoDispositivo = $tipoDisp->listaTipoDispositivo();
			
			$data = array();
			if ($listaTipoDispositivo->rowCount() !=0) {
				while ($row = $listaTipoDispositivo->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			echo $json_info = json_encode($data);
			$tipoDisp->bd->cerrarBD();
		break;
		
		case "guardarNvoTipoDisp":
			include_once ("cTipoDeDispositivo.php");
			$tipoDisp = new TipoDeDispositivo($_SESSION['bd']);
			// $tipoDisp->bd->setNombreBD($_SESSION['bd']);
			
			$tipoDisp->setNombre($_POST['nombre']);
			$tipoDisp->guardarNvoTipoDisp();
			
			echo "<script>" . resultadoDeConsulta($tipoDisp->getMsj(), "recargar", "tipoDispositivo", "") . "</script>";
			
			$tipoDisp->bd->cerrarBD();
		break;
		
		case "guardarTipoDisp":
			include_once ("cTipoDeDispositivo.php");
			$tipoDisp = new TipoDeDispositivo($_SESSION['bd']);
			// $tipoDisp->bd->setNombreBD($_SESSION['bd']);
			
			$tipoDisp->setNombre($_POST['nombre']);
			$tipoDisp->guardarTipoDisp($_POST['idTipoDisp']);
			
			echo "<script>" . resultadoDeConsulta($tipoDisp->getMsj(), "recargar", "tipoDispositivo", "") . "</script>";
			
			$tipoDisp->bd->cerrarBD();
		break;
		
		case "borrarTipoDisp":
			include_once ("cTipoDeDispositivo.php");
			$tipoDisp = new TipoDeDispositivo($_SESSION['bd']);
			// $tipoDisp->bd->setNombreBD($_SESSION['bd']);
			
			$tipoDisp->borrarTipoDisp($_POST['idTipoDisp']);
			
			echo "<script>" . resultadoDeConsulta($tipoDisp->getMsj(), "recargar", "tipoDispositivo", "") . "</script>";
			
			$tipoDisp->bd->cerrarBD();
		break;
		
		/********* DISPOSITIVOS *********/
		
		case "listaPerifericos":
			include_once ("cDispositivos.php");
			$periferico = new Dispositivos($_SESSION['bd']);
			// $periferico->bd->setNombreBD($_SESSION['bd']);
			
			$listaPerifericos = $periferico->listaPerifericos($_GET['idCte']);
			
			$data = array();
			if ($listaPerifericos->rowCount() !=0) {
				while ($row = $listaPerifericos->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			echo $json_info = json_encode($data);
			$periferico->bd->cerrarBD();
		break;
		
		case "guardarNvoPeriferico":
			include_once ("cDispositivos.php");
			// echo $_SESSION['bd'];
			$periferico = new Dispositivos($_SESSION['bd']);
			// $periferico->bd->setNombreBD($_SESSION['bd']);
			
			$periferico->setIdSucursalCte($_POST['idSucursalCte']);
			$periferico->setIdTipoDisp($_POST['idTipoDisp']);
			$periferico->setNombre($_POST['nombre']);
			$periferico->setMarca($_POST['marca']);
			$periferico->setModelo($_POST['modelo']);
			$periferico->setSerie($_POST['serie']);
			$periferico->setParte($_POST['parte']);
			$periferico->setObservaciones($_POST['observaciones']);
			$periferico->setFechaRegistro($_POST['fechaRegistro']);
			$periferico->guardarNvoPeriferico($_POST['idCliente']);
			
			echo "<script>" . resultadoDeConsulta($periferico->getMsj(), "recargar", "msjInv", "") . "</script>";
			
			$periferico->bd->cerrarBD();
		break;
		
		case "guardarPeriferico":
			include_once ("cDispositivos.php");
			$periferico = new Dispositivos($_SESSION['bd']);
			// $periferico->bd->setNombreBD($_SESSION['bd']);
			
			$periferico->setIdSucursalCte($_POST['idSucursalCte']);
			$periferico->setIdTipoDisp($_POST['idTipoDisp']);
			$periferico->setNombre($_POST['nombre']);
			$periferico->setMarca($_POST['marca']);
			$periferico->setModelo($_POST['modelo']);
			$periferico->setSerie($_POST['serie']);
			$periferico->setParte($_POST['parte']);
			$periferico->setObservaciones($_POST['observaciones']);
			$periferico->setFechaRegistro($_POST['fechaRegistro']);
			$periferico->guardarPeriferico($_POST['idDispositivo']);
			
			echo "<script>" . resultadoDeConsulta($periferico->getMsj(), "recargar", "msjInv", "") . "</script>";
			
			$periferico->bd->cerrarBD();
		break;
		
		case "borrarPeriferico":
			include_once ("cDispositivos.php");
			$periferico = new Dispositivos($_SESSION['bd']);
			// $periferico->bd->setNombreBD($_SESSION['bd']);
			
			$periferico->borrarPeriferico($_POST['idDispositivo']);
			
			echo "<script>" . resultadoDeConsulta($periferico->getMsj(), "recargar", "msjInv", "") . "</script>";
			
			$periferico->bd->cerrarBD();
		break;
		
		/********* REPORTES *********/
		/*case "rptServXCte":
			include_once ("cOrdenDeServicio.php");
			$ods = new OrdenDeServicio($_SESSION['bd']);
			// $ods->bd->setNombreBD($_SESSION['bd']);
			
			$ordDeServ = $ods->rptServxCte($_POST['cveCte'], $_POST['fInicial'], $_POST['fFinal']);
			echo $json_info = json_encode($ordDeServ);
			$ods->bd->cerrarBD();
		break;*/
		
		case "licCaducadasDetalle":
			include_once ("cLicenciasDeSoftware.php");
			$lic = new LicenciasDeSoftware($_SESSION['bd']);
			// $lic->bd->setNombreBD($_SESSION['bd']);
			
			$lic->licCaducadasDetalle(0,0);
			$lic->bd->cerrarBD();
		break;
	}
?>
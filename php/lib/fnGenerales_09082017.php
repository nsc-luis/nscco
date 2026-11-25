<?php
	session_start();
	error_reporting(E_ALL);
	ini_set("display_errors", 0);
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
			$cte = new Clientes();
			$cte->bd->setNombreBD($_SESSION['bd']);
			
			$listaClientes = $cte->clientes();
			echo $json_info = json_encode($listaClientes);
			$cte->bd->cerrarBD();
		break;
		
		case "guardarNvoCte":
			include_once ("cClientes.php");
			$cte = new Clientes();
			$cte->bd->setNombreBD($_SESSION['bd']);
			
			$cte->setClave($_POST['clave']);
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
			
			$cte->nuevoCliente();
			
			echo "<script>" . resultadoDeConsulta($cte->getMsj(), "recargar", "msjCte", "") . "</script>";
			$cte->bd->cerrarBD();
		break;
		
		case "guardarCte":
			include_once ("cClientes.php");
			$cte = new Clientes();
			$cte->bd->setNombreBD($_SESSION['bd']);
			
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
			$cte = new Clientes();
			$cte->bd->setNombreBD($_SESSION['bd']);
			
			$cte->borraCliente($_POST['clave']);
			
			echo "<script>" . resultadoDeConsulta($cte->getMsj(), "recargar", "msjCte", "") . "</script>";
			$cte->bd->cerrarBD();
		break;
		
		//// SUCURSALES DEL CLIENTE ////
		case "listaOficinas":
			include_once ("cClientes.php");
			$cte = new Clientes();
			$cte->bd->setNombreBD($_SESSION['bd']);
			
			$listaOficinas = $cte->listaOficinas();
	
			$data = array();
			if (mysql_num_rows($listaOficinas) !=0) {
				while ($row = mysql_fetch_assoc($listaOficinas)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$cte->bd->cerrarBD();
		break;
		
		case "getSucPorCte":
			include_once ("cClientes.php");
			$cte = new Clientes();
			$cte->bd->setNombreBD($_SESSION['bd']);
			
			$getSucPorCte = $cte->getSucPorCte($_GET['clave']);
	
			$data = array();
			if (mysql_num_rows($getSucPorCte) !=0) {
				while ($row = mysql_fetch_assoc($getSucPorCte)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$cte->bd->cerrarBD();
		break;
		
		case "guardarSucCte":
			include_once ("cClientes.php");
			$cte = new Clientes();
			$cte->bd->setNombreBD($_SESSION['bd']);
			
			$cte->setNomSucursal($_POST['nombre']);
			if (isset($_POST['direccion'])) { $cte->setDirSucCte($_POST['direccion']); } else { $cte->setDirSucCte(''); }
			$cte->setTel1Suc($_POST['telefono1']);
			
			$cte->editaSucCte($_POST['idSucursalCte']);
			
			echo "<script>" . resultadoDeConsulta($cte->getMsj(), "recargar", "msjCte", "") . "</script>";
			$cte->bd->cerrarBD();
		break;
		
		case "guardarNvaSucCte":
			include_once ("cClientes.php");
			$cte = new Clientes();
			$cte->bd->setNombreBD($_SESSION['bd']);
			
			$cte->setCveCte($_POST['cveCte']);
			$cte->setNomSucursal($_POST['nombre']);
			$cte->setDirSucCte($_POST['direccion']);
			$cte->setTel1Suc($_POST['telefono1']);
			
			$cte->nvaSucCte();
			
			echo "<script>" . resultadoDeConsulta($cte->getMsj(), "recargar", "msjCte", "") . "</script>";
			$cte->bd->cerrarBD();
		break;
		
		case "borraSucCte":
			include_once ("cClientes.php");
			$cte = new Clientes();
			$cte->bd->setNombreBD($_SESSION['bd']);
			
			$cte->setCveCte($_POST['cveCte']);
			$cte->borraSucCte($_POST['idSucursalCte']);
			
			echo "<script>" . resultadoDeConsulta($cte->getMsj(), "recargar", "msjCte", "") . "</script>";
			$cte->bd->cerrarBD();
		break;
	
		/********* PRODUCTOS *********/
		case "listaProductos":
			include_once ("cProductos.php");
			$prod = new Productos();
			$prod->bd->setNombreBD($_SESSION['bd']);
			
			$listaProductos = $prod->productos();
			$data = array();
			if (mysql_num_rows($listaProductos) !=0) {
				while ($row = mysql_fetch_assoc($listaProductos)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$prod->bd->cerrarBD();
		break;
		
		case "getInfoProdBuscado":
			include_once ("cProductos.php");
			$prod = new Productos();
			$prod->bd->setNombreBD($_SESSION['bd']);
			
			$getInfoProdBuscado = $prod->getInfoProdBuscado($_GET['cadena']);
			$data = array();
			if (mysql_num_rows($getInfoProdBuscado) !=0) {
				while ($row = mysql_fetch_assoc($getInfoProdBuscado)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$prod->bd->cerrarBD();
		break;
		
		case "getInfoProd":
			include_once ("cProductos.php");
			$prod = new Productos();
			$prod->bd->setNombreBD($_SESSION['bd']);
			
			$getInfoProd = $prod->getInfoProd($_GET['cveProd']);
			$data = array();
			if (mysql_num_rows($getInfoProd) !=0) {
				while ($row = mysql_fetch_assoc($getInfoProd)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$prod->bd->cerrarBD();
		break;

		case "listaUnidades":
			include_once ("cProductos.php");
			$prod = new Productos();
			$prod->bd->setNombreBD($_SESSION['bd']);
			
			$listaUnidades = $prod->unidades();
			$data = array();
			if (mysql_num_rows($listaUnidades) !=0) {
				while ($row = mysql_fetch_assoc($listaUnidades)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$prod->bd->cerrarBD();
		break;
		
		case "guardarNvoProd":
			include_once ("cProductos.php");
			$prod = new Productos();
			$prod->bd->setNombreBD($_SESSION['bd']);
			
			$prod->setClave($_POST['clave']);
			$prod->setNombre($_POST['nombre']);
			$prod->setDescripcion($_POST['descripcion']);
			$prod->setPrecio($_POST['precio']);
			$prod->setIdUnidad($_POST['idUnidad']);
			$prod->setIdImpuesto($_POST['idImpuesto']);
			$prod->setProveedor($_POST['proveedor']);
			$prod->setSkuProveedor($_POST['skuProveedor']);
			
			$prod->nuevoProducto();
			
			echo "<script>" . resultadoDeConsulta($prod->getMsj(), "recargar", "msjProd", "") . "</script>";
			$prod->bd->cerrarBD();
		break;
		
		case "guardarProd":
			include_once ("cProductos.php");
			$prod = new Productos();
			$prod->bd->setNombreBD($_SESSION['bd']);
			
			$prod->setNombre($_POST['nombre']);
			$prod->setDescripcion($_POST['descripcion']);
			$prod->setPrecio($_POST['precio']);
			$prod->setIdUnidad($_POST['idUnidad']);
			$prod->setIdImpuesto($_POST['idImpuesto']);
			$prod->setProveedor($_POST['proveedor']);
			$prod->setSkuProveedor($_POST['skuProveedor']);
			
			$prod->editaProducto($_POST['clave']);
			
			echo "<script>" . resultadoDeConsulta($prod->getMsj(), "recargar", "msjProd", "") . "</script>";
			$prod->bd->cerrarBD();
		break;
		
		case "borrarProd":
			include_once ("cProductos.php");
			$prod = new Productos();
			$prod->bd->setNombreBD($_SESSION['bd']);
			
			$prod->borraProducto($_POST['clave']);
			
			echo "<script>" . resultadoDeConsulta($prod->getMsj(), "recargar", "msjProd", "") . "</script>";
			$prod->bd->cerrarBD();
		break;
		
		/********* USUARIOS *********/
		case "listaUsuarios":
			include_once ("cUsuarios.php");
			$usr = new Usuarios();
			$usr->bd->setNombreBD($_SESSION['bd']);
			
			$listaUsuarios = $usr->usuarios($_SESSION['usuario']);
			$data = array();
			if (mysql_num_rows($listaUsuarios) !=0) {
				while ($row = mysql_fetch_assoc($listaUsuarios)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$usr->bd->cerrarBD();
		break;
		
		case "guardarNvoUsr":
			include_once ("cUsuarios.php");
			$usr = new Usuarios();
			$usr->bd->setNombreBD($_SESSION['bd']);
			
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
			$usr = new Usuarios();
			$usr->bd->setNombreBD($_SESSION['bd']);
			
			$usr->setNombre($_POST['nombre']);
			$usr->setPassword($_POST['password']);
			$usr->setEmailUsr($_POST['emailUsr']);
			
			$usr->actualizaUsuario($_POST['usuario']);
			
			echo "<script>" . resultadoDeConsulta($usr->getMsj(), "recargar", "msjUsr", "") . "</script>";
			$usr->bd->cerrarBD();
		break;
		
		case "borrarUsr":
			include_once ("cUsuarios.php");
			$usr = new Usuarios();
			$usr->bd->setNombreBD($_SESSION['bd']);
			
			$usr->borraUsuario($_POST['usuario'],$_SESSION['usuario']);
			
			echo "<script>" . resultadoDeConsulta($usr->getMsj(), "recargar", "msjUsr", "") . "</script>";
			$usr->bd->cerrarBD();
		break;
		
		/********* UNIDADES *********/
		case "listaUnidades":
			include_once ("cUnidades.php");
			$uni = new Unidades();
			$uni->bd->setNombreBD($_SESSION['bd']);
			
			$listaUnidades = $uni->unidades($_SESSION['usuario']);
			$data = array();
			if (mysql_num_rows($listaUnidades) !=0) {
				while ($row = mysql_fetch_assoc($listaUnidades)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$uni->bd->cerrarBD();
		break;
		
		case "guardarNvaUni":
			include_once ("cUnidades.php");
			$uni = new Unidades();
			$uni->bd->setNombreBD($_SESSION['bd']);
			
			$uni->setMedida($_POST['medida']);
			$uni->nuevaUni();
			
			echo "<script>" . resultadoDeConsulta($uni->getMsj(), "recargar", "msjUni", "") . "</script>";
			$uni->bd->cerrarBD();
		break;
		
		case "guardarUni":
			include_once ("cUnidades.php");
			$uni = new Unidades();
			$uni->bd->setNombreBD($_SESSION['bd']);
			
			$uni->setMedida($_POST['medida']);
			$uni->editarUni($_POST['idUnidad']);
			
			echo "<script>" . resultadoDeConsulta($uni->getMsj(), "recargar", "msjUni", "") . "</script>";
			$uni->bd->cerrarBD();
		break;
		
		case "borrarUni":
			include_once ("cUnidades.php");
			$uni = new Unidades();
			$uni->bd->setNombreBD($_SESSION['bd']);
			
			$uni->borraUnidad($_POST['idUnidad']);
			
			echo "<script>" . resultadoDeConsulta($uni->getMsj(), "recargar", "msjUni", "") . "</script>";
			$uni->bd->cerrarBD();
		break;
		
		/********* ORDENES DE SERVICIO *********/
		case "nvaOrdServ":
			include_once ("cOrdenDeServicio.php");
			$ods = new OrdenDeServicio();
			$ods->bd->setNombreBD($_SESSION['bd']);
			
			$ods->nvaOrdServ($_SESSION['usuario']);
			$ods->bd->cerrarBD();
		break;
		
		case "tiposStatus":
			include_once ("cStatus.php");
			$stu = new Status();
			$stu->bd->setNombreBD($_SESSION['bd']);
			
			$tiposStatus = $stu->tiposStatus();
			$data = array();
			if (mysql_num_rows($tiposStatus) !=0) {
				while ($row = mysql_fetch_assoc($tiposStatus)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$stu->bd->cerrarBD();
		break;
		
		case "listaDeOrdenes":
			include_once ("cOrdenDeServicio.php");
			$ods = new OrdenDeServicio();
			$ods->bd->setNombreBD($_SESSION['bd']);
			
			$ods->setCveCte($_GET['cveCte']);
			$ods->setIdSucursalCte($_GET['idSucursalCte']);
			$ods->setIdStatus($_GET['idStatus']);
			$usuario = $_GET['usuario'] == "" ? $ods->setUsuario("Todos") : $ods->setUsuario($_GET['usuario']);
			
			$ordenesDeServicio = $ods->ordenesDeServicio($_SESSION['usuario'],$_GET['fechaInicial'],$_GET['fechaFinal']);
			//$ordenesDeServicio = $ods->ordenesDeServicio($_SESSION['usuario'],"10","1");
			$data = array();
			if (mysql_num_rows($ordenesDeServicio) !=0) {
				while ($row = mysql_fetch_assoc($ordenesDeServicio)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$ods->bd->cerrarBD();
			/* include_once ("cOrdenDeServicio.php");
			$ods = new OrdenDeServicio();
			$ods->bd->setNombreBD($_SESSION['bd']);
			
			$ordenesDeServicio = $ods->ordenesDeServicio($_SESSION['usuario']);
			$data = array();
			if (mysql_num_rows($ordenesDeServicio) !=0) {
				while ($row = mysql_fetch_assoc($ordenesDeServicio)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$ods->bd->cerrarBD(); */
		break;
		
		case "guardarOrdServ":
			include_once ("cOrdenDeServicio.php");
			$ods = new OrdenDeServicio();
			$ods->bd->setNombreBD($_SESSION['bd']);
			
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
			$ordServ = new OrdenDeServicio();
			$ordServ->bd->setNombreBD($_SESSION['bd']);
			
			$ordServ->borrarOrdServ($_POST['folio']);
			
			echo "<script>" . resultadoDeConsulta($ordServ->getMsj(), "recargar", "frmOrdServ", "") . "</script>";
			$ordServ->bd->cerrarBD();
		break;
		
		case "guardaStatus":
			include_once ("cOrdenDeServicio.php");
			$ordServ = new OrdenDeServicio();
			$ordServ->bd->setNombreBD($_SESSION['bd']);
			
			$ordServ->setIdStatus($_POST['idStatus']);
			$ordServ->setReferenciaFact($_POST['referenciaFact']);
			$ordServ->guardaStatus($_POST['folio']);
			
			echo "<script>" . resultadoDeConsulta($ordServ->getMsj(), "recargar", "frmOrdServ", "") . "</script>";
			$ordServ->bd->cerrarBD();
		break;
		
		case "cambiarStatusVarios":
			include_once ("cOrdenDeServicio.php");
			$ordServ = new OrdenDeServicio();
			$ordServ->bd->setNombreBD($_SESSION['bd']);
			
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
			$ods = new OrdenDeServicio();
			$ods->bd->setNombreBD($_SESSION['bd']);
			
			$ordenDeServicio = $ods->getInfoOrdServNva($_GET['folio'],$_GET['usuario']);
			$data = array();
			if (mysql_num_rows($ordenDeServicio) !=0) {
				while ($row = mysql_fetch_assoc($ordenDeServicio)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$ods->bd->cerrarBD();
		break;
		
		case "tiposDeServ":
			include_once ("cTipoDeServ.php");
			$tds = new TipoDeServicio();
			$tds->bd->setNombreBD($_SESSION['bd']);
			
			$tiposDeServicio = $tds->tiposDeServ();
			$data = array();
			if (mysql_num_rows($tiposDeServicio) !=0) {
				while ($row = mysql_fetch_assoc($tiposDeServicio)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$tds->bd->cerrarBD();
		break;
		
		/********* MOVTOS DE PARTIDAS *********/
		
		case "movtosPartidasOrdServ":
			include_once ("cMovtosPartidas.php");
			$mvtos = new MovtosPartidas();
			$mvtos->bd->setNombreBD($_SESSION['bd']);
			
			$movtosPartidasOrdServ = $mvtos->movtosPartidasOrdServ($_GET['idTipoDocto'],$_GET['folio'],$_GET['usuario']);
			$data = array();
			if (mysql_num_rows($movtosPartidasOrdServ) !=0) {
				while ($row = mysql_fetch_assoc($movtosPartidasOrdServ)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$mvtos->bd->cerrarBD();
		break;
		
		case "nvaPartidaOrdServ":
			include_once ("cMovtosPartidas.php");
			$mvtos = new MovtosPartidas();
			$mvtos->bd->setNombreBD($_SESSION['bd']);
			
			$mvtos->setIdTipoDocto($_GET['idTipoDocto']);
			$mvtos->setFolioDocto($_GET['folio']);
			$mvtos->setUsuario($_GET['usuario']);
			$mvtos->setCveProd($_GET['clave']);
			$mvtos->setDetalle($_GET['descripcion']);
			$mvtos->setCantidad($_GET['cantidad']);
			$mvtos->setPrecioUnitario($_GET['precio']);
			
			$nvaPartidaOrdServ = $mvtos->nvaPartidaOrdServ();
			
			echo "<script>" . resultadoDeConsulta($mvtos->getMsj(), "recargar", "frmPartidaOrdServ", "") . "</script>";
			
			$mvtos->bd->cerrarBD();
		break;
		
		case "borrarPartida":
			include_once ("cMovtosPartidas.php");
			$mvtos = new MovtosPartidas();
			$mvtos->bd->setNombreBD($_SESSION['bd']);
			
			$mvtos->borrarPartOrdServ($_POST['idMovto']);
			
			echo "<script>" . resultadoDeConsulta($mvtos->getMsj(), "recargar", "frmPartidaOrdServ", "") . "</script>";
			
			$mvtos->bd->cerrarBD();
		break;
		
		case "guardarPartida":
			include_once ("cMovtosPartidas.php");
			$mvtos = new MovtosPartidas();
			$mvtos->bd->setNombreBD($_SESSION['bd']);
			
			$mvtos->setDetalle($_POST['detalle']);
			$mvtos->setCantidad($_POST['cantidad']);
			$mvtos->setPrecioUnitario($_POST['precio']);
			
			$mvtos->edDetallePartOrdServ($_POST['idMovto']);
			
			echo "<script>" . resultadoDeConsulta($mvtos->getMsj(), "recargar", "frmPartidaOrdServ", "") . "</script>";
			
			$mvtos->bd->cerrarBD();
		break;
		
		/********* BITACORA DE RESPALDOS CON CLIENTES *********/
		
		case "listaCtesRespaldos":
			include_once ("cRespaldosCtes.php");
			$rCtes = new RespaldoCtes();
			$rCtes->bd->setNombreBD("respctes");
			$listaCtesRespaldos = $rCtes->listaCtesRespaldos();
			
			$data = array();
			if (mysql_num_rows($listaCtesRespaldos) !=0) {
				while ($row = mysql_fetch_assoc($listaCtesRespaldos)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$rCtes->bd->cerrarBD();
		break;
		case "bitRespCtes":
			include_once ("cRespaldosCtes.php");
			$rCtes = new RespaldoCtes();
			$rCtes->bd->setNombreBD("respctes");
			$bitRespCtes = $rCtes->bitRespCtes($_GET['nombreCte']);
			
			$data = array();
			if (mysql_num_rows($bitRespCtes) !=0) {
				while ($row = mysql_fetch_assoc($bitRespCtes)) {
					$data[] = $row;
				}
			}
			echo $json_info = json_encode($data);
			$rCtes->bd->cerrarBD();
		break;
	}
?>
<?php
	session_start();
	ini_set("display_errors", 1);
	include_once ("cOrdenDeServicio.php");
	include_once ("cClientes.php");
	include_once ("cStatus.php");
	include_once ("cMovtosPartidas.php");
	
	$ods = new OrdenDeServicio($_SESSION['bd']);
	// $ods->bd->setNombreBD($_SESSION['bd']);
	$exportaOrdenesDeServicioExcel = $ods->exportaOrdenesDeServicioExcel($_GET['usr'],$_GET['cte'],$_GET['sucursal'],$_GET['status']);
	$data = array();
	if ($exportaOrdenesDeServicioExcel->rowCount() !=0) {
		while ($row = $exportaOrdenesDeServicioExcel->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $row;
		}
	}
	$ods->bd->cerrarBD();
	
	$cte = new Clientes($_SESSION['bd']);
	// $cte->bd->setNombreBD($_SESSION['bd']);
	// $cte->getInfoCte($_GET['cte']);
	$cte->getInfoCteByCveCte($_GET['cte']);
	$cliente = $cte->getNombre() == "" ? "Todos" : $cte->getNombre();
	if ($_GET['sucursal']) {
		$cte->getInfoSucCte($_GET['sucursal']);
		$sucursalCte = $cte->getNomSucursal();
	} else { 
		$sucursalCte = "Todas";
	}
	
	$tienePoliza = $cte->getPoliza();
	$hrsSerPres = $cte->getHorasServPresencial();
	$hrsSerRemt = $cte->getHorasServRemoto();
	$pHraExtPres = $cte->getPrecioHoraPresencial();
	$pHraExtRemt = $cte->getPrecioHoraRemoto();
	
	if ($cliente != "Todos" && $tienePoliza == 1) {
		$mesActual = date("Y-m"); 
		
		$sql = "select sum(tiempoInvertido) as HrsForaneo from ordendeservicio 
			where fechaOrdServ like '" . $mesActual . "%' and cveCte = '$_GET[cte]' and idTipoDeServ = 3";
		$query = $cte->bd->consulta($sql);
		$HrsForaneo =  $query->fetch(PDO::FETCH_ASSOC);
		
		$sql = "select sum(tiempoInvertido) as HrsExtPresencial from ordendeservicio 
			where fechaOrdServ like '" . $mesActual . "%' and cveCte = '$_GET[cte]' and idTipoDeServ = 1";
		$query = $cte->bd->consulta($sql);
		$response =  $query->fetch(PDO::FETCH_ASSOC);
		$HrsExtPresencial = $response['HrsExtPresencial'] + $HrsForaneo['HrsForaneo'] - $hrsSerPres;
		
		$sql = "select sum(tiempoInvertido) as HrsExtRemoto from ordendeservicio 
			where fechaOrdServ like '" . $mesActual . "%' and cveCte = '$_GET[cte]' and idTipoDeServ = 2";
		$query = $cte->bd->consulta($sql);
		$response =  $query->fetch(PDO::FETCH_ASSOC);
		$HrsExtRemoto = $response['HrsExtRemoto'] - $hrsSerRemt;
		
		$importeHrsExtPresencial = $HrsExtPresencial > 0 ? $HrsExtPresencial * $pHraExtPres : 0;
		$importeHrsExtRemoto = $HrsExtRemoto > 0 ? $HrsExtRemoto * $pHraExtRemt : 0;
	}
	$cte->bd->cerrarBD();
	
	$stu = new Status($_SESSION['bd']);
	// $stu->bd->setNombreBD($_SESSION['bd']);
	$stu->getInfoStatus($_GET['status']);
	$statusOrd = $stu->getStatus() == "" ? "Todos" : $stu->getStatus();
	$stu->bd->cerrarBD();
	
	$usuario = $_GET['usr'] == "" ? "Todos" : $_GET['usr'];
	
	require_once ("PHPExcel.php");
	// PHPExcel_IOFactory
	include ("PHPExcel/IOFactory.php");
	$objPHPExcel = new PHPExcel();
	// Leemos un archivo Excel 2007
	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcel = $objReader->load("expOrdServExcel.xlsx");
	// Indicamos que se pare en la hoja uno del libro
	$objPHPExcel->setActiveSheetIndexByName("ListaOrdServ");
	//Escribimos en la hoja en la celda B1
	$objPHPExcel->getActiveSheet()->SetCellValue('B5', $cliente);
	$objPHPExcel->getActiveSheet()->SetCellValue('B6', $sucursalCte);
	$objPHPExcel->getActiveSheet()->SetCellValue('B7', $statusOrd);
	$objPHPExcel->getActiveSheet()->SetCellValue('B8', $usuario);
	
	$fila = 11;
	for ($i=0; $i<count($data); $i++) {
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila, $data[$i]['folio']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila, $data[$i]['nombreCte']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila, $data[$i]['nombreSucursal']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$fila, $data[$i]['importe']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$fila, $data[$i]['fechaOrdServ']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$fila, $data[$i]['status']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$fila, $data[$i]['usuario']);
		$fila++;
	}
	
	$objPHPExcel->setActiveSheetIndexByName("PartidasParaFactura");
	$fila = 6;
	$movtos = new MovtosPartidas($_SESSION['bd']);
	// $movtos->bd->setNombreBD($_SESSION['bd']);
	$listaDeOrdenes = "SERVICIO PROFESIONAL SEGUN REPORTES: ";
	$sumaServicios = 0;
	for ($i=0; $i<count($data); $i++) {
		$query = $movtos->getPartidasPorOrdServ($data[$i]['folio']);
		$partidas = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$partidas[] = $row;
		}
		foreach ($partidas as $partida) {
			if ($partida['idUnidad'] == "1") {
				$folio = explode("-",$partida['folioDocto']);
				$listaDeOrdenes .= $folio[0] . "-" . $folio[1] . " ";
				$sumaServicios += $partida['importePart'];
			} else {
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila, $partida['cantidad']);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila, $partida['nombre']);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila, $partida['precioUnitario']);
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$fila, $partida['precioUnitario']*$partida['cantidad']);
				$fila++;
			}
		}
	}
	if ($sumaServicios > 0) {
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila, 1);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila, $listaDeOrdenes);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila, $sumaServicios);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$fila, $sumaServicios);
	}
	/* if ($importeHrsExtPresencial > 0){
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila, $HrsExtPresencial);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila, "Horas extra de servicio presencial.");
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila, $pHraExtPres);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$fila, $importeHrsExtPresencial);
	}
	if ($importeHrsExtPresencial > 0){
		$fila++;
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila, $HrsExtRemoto);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila, "Horas extra de servicio remoto.");
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila, $pHraExtRemt);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$fila, $importeHrsExtRemoto);
	} */
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('ExportaOrdServExcel.xlsx');
	header("Location: http://www.nscco.com.mx/php/lib/ExportaOrdServExcel.xlsx");
?>
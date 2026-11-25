<?php
	session_start();
	include_once ("cOrdenDeServicio.php");
	include_once ("cClientes.php");
	include_once ("cStatus.php");
	include_once ("cMovtosPartidas.php");
	
	$ods = new OrdenDeServicio();
	$ods->bd->setNombreBD($_SESSION['bd']);
	$exportaOrdenesDeServicioExcel = $ods->exportaOrdenesDeServicioExcel($_GET['usr'],$_GET['cte'],$_GET['sucursal'],$_GET['status']);
	$data = array();
	if (mysql_num_rows($exportaOrdenesDeServicioExcel) !=0) {
		while ($row = mysql_fetch_assoc($exportaOrdenesDeServicioExcel)) {
			$data[] = $row;
		}
	}
	$ods->bd->cerrarBD();
	
	$cte = new Clientes();
	$cte->bd->setNombreBD($_SESSION['bd']);
	$cte->getInfoCte($_GET['cte']);
	$cliente = $cte->getNombre() == "" ? "Todos" : $cte->getNombre();
	$cte->getInfoSucCte($_GET['sucursal']);
	$sucursalCte = $cte->getNomSucursal() == "" ? "Todas" : $cte->getNomSucursal();
	$cte->bd->cerrarBD();
	
	$stu = new Status();
	$stu->bd->setNombreBD($_SESSION['bd']);
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
	$movtos = new MovtosPartidas();
	$movtos->bd->setNombreBD($_SESSION['bd']);
	$listaDeOrdenes = "SERVICIO PROFESIONAL SEGUN REPORTES: ";
	$sumaServicios = 0;
	for ($i=0; $i<count($data); $i++) {
		$query = $movtos->getPartidasPorOrdServ($data[$i]['folio']);
		$partidas = array();
		while ($row = mysql_fetch_assoc($query)) {
			$partidas[] = $row;
		}
		foreach ($partidas as $partida) {
			if ($partida['idUnidad'] == "1") {
				$folio = split("-",$partida['folioDocto']);
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
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('ExportaOrdServExcel.xlsx');
	header("Location: http://www.nscco.com.mx/php/lib/ExportaOrdServExcel.xlsx");
?>
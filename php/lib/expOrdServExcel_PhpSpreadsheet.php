<?php
	session_start();
	ini_set("display_errors", 1);
	include_once ("cOrdenDeServicio.php");
	include_once ("cClientes.php");
	include_once ("cStatus.php");
	include_once ("cMovtosPartidas.php");
	require '../../vendor/autoload.php';
	date_default_timezone_set('America/Mexico_City');
	
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
	
	//echo "<pre>";print_r($data);echo "</pre>";
	
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
	
	//echo $cliente;
	
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
	
	use PhpOffice\PhpSpreadsheet\IOFactory;
	// 1. Cargar la plantilla existente (.xlsx)
	$spreadsheet = IOFactory::load('expOrdServExcel_PhpSpreadsheet.xlsx');
	
	// 2. Obtener la hoja activa (o por nombre)
	$spreadsheet->setActiveSheetIndexByName("ListaOrdServ");
	//Escribimos en la hoja en la celda B1
	$spreadsheet->getActiveSheet()->SetCellValue('B5', $cliente);
	$spreadsheet->getActiveSheet()->SetCellValue('B6', $sucursalCte);
	$spreadsheet->getActiveSheet()->SetCellValue('B7', $statusOrd);
	$spreadsheet->getActiveSheet()->SetCellValue('B8', $usuario);
	
	$fila = 11;
	for ($i=0; $i<count($data); $i++) {
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$fila, $data[$i]['folio']);
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$fila, $data[$i]['nombreCte']);
		$spreadsheet->getActiveSheet()->SetCellValue('C'.$fila, $data[$i]['nombreSucursal']);
		$spreadsheet->getActiveSheet()->SetCellValue('D'.$fila, $data[$i]['importe']);
		$spreadsheet->getActiveSheet()->SetCellValue('E'.$fila, $data[$i]['fechaOrdServ']);
		$spreadsheet->getActiveSheet()->SetCellValue('F'.$fila, $data[$i]['status']);
		$spreadsheet->getActiveSheet()->SetCellValue('G'.$fila, $data[$i]['usuario']);
		$fila++;
	}
	
	$spreadsheet->setActiveSheetIndexByName("PartidasParaFactura");
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
				$spreadsheet->getActiveSheet()->SetCellValue('A'.$fila, $partida['cantidad']);
				$spreadsheet->getActiveSheet()->SetCellValue('B'.$fila, $partida['nombre']);
				$spreadsheet->getActiveSheet()->SetCellValue('C'.$fila, $partida['precioUnitario']);
				$spreadsheet->getActiveSheet()->SetCellValue('D'.$fila, $partida['precioUnitario']*$partida['cantidad']);
				$fila++;
			}
		}
	}
	if ($sumaServicios > 0) {
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$fila, 1);
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$fila, $listaDeOrdenes);
		$spreadsheet->getActiveSheet()->SetCellValue('C'.$fila, $sumaServicios);
		$spreadsheet->getActiveSheet()->SetCellValue('D'.$fila, $sumaServicios);
	}
	/** if ($importeHrsExtPresencial > 0){
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$fila, $HrsExtPresencial);
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$fila, "Horas extra de servicio presencial.");
		$spreadsheet->getActiveSheet()->SetCellValue('C'.$fila, $pHraExtPres);
		$spreadsheet->getActiveSheet()->SetCellValue('D'.$fila, $importeHrsExtPresencial);
	}
	if ($importeHrsExtPresencial > 0){
		$fila++;
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$fila, $HrsExtRemoto);
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$fila, "Horas extra de servicio remoto.");
		$spreadsheet->getActiveSheet()->SetCellValue('C'.$fila, $pHraExtRemt);
		$spreadsheet->getActiveSheet()->SetCellValue('D'.$fila, $importeHrsExtRemoto);
	} **/
	
	// 4. Guardar como un nuevo archivo XLSX
	$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
	$fileName = 'ExportaOrdServExcel_' . $_GET['cte'] . '_' . date("Y-m-d") . '.xlsx';
	$writer->save('../../tmp/' . $fileName);
	
	header("Location: ../../tmp/$fileName");
?>
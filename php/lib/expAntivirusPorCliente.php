<?php
	try
	{
		session_start();
		include_once("cInventarios.php");
		include_once("cClientes.php");
		$inv = new Inventarios($_SESSION['bd']);
		// $inv->bd->setNombreBD($_SESSION['bd']);
		
		date_default_timezone_set('America/Mexico_City');
		$ahora = date('Y-m-d_H-i-s');
		
		$inv->setIdCliente($_GET['idCte']);
		$datos = $inv->ConsultaCaducidadAVPorCliente();
		$inv->bd->cerrarBD();
		
		$cte = new Clientes($_SESSION['bd']);
		// $cte->bd->setNombreBD($_SESSION['bd']);
		
		$datosCte = $cte->getInfoCte($_GET['idCte']);
		$cte->bd->cerrarBD();
		
		/* echo "<pre>";
		print_r($datosCte);
		echo "</pre>";
		
		echo "<pre>";
		print_r($datos);
		echo "</pre>"; */
		
		require_once ("PHPExcel.php");
		// PHPExcel_IOFactory
		include ("PHPExcel/IOFactory.php");
		$objPHPExcel = new PHPExcel();
		// Leemos un archivo Excel 2007
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load("expAntivirusPorCliente.xlsx");
		// Indicamos que se pare en la hoja uno del libro
		$objPHPExcel->setActiveSheetIndexByName("AntivirusPorCliente");
		
		$objPHPExcel->getActiveSheet()->SetCellValue('B3', $datosCte[0]['nombre']);
		
		$fila = 6;
		$contador = 1;
		for ($i=0; $i < count($datos); $i++)
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila, $contador);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila, $datos[$i]['id']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila, $datos[$i]['Host']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$fila, $datos[$i]['Fabricante']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$fila, $datos[$i]['usuario']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$fila, $datos[$i]['fechacaducidad']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$fila, $datos[$i]['Sucursal']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$fila, $datos[$i]['departamento']);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$fila, $datos[$i]['observaciones']);
			$fila++;
			$contador++;
		}
		
		$nombreArchivo = $datosCte[0]['clave'] . "_Antivius_" . $ahora . ".xlsx";
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('../../tmp/' . $nombreArchivo);
		header("Location: http://www.nscco.com.mx/tmp/" . $nombreArchivo);
	}
	catch (Exception $e)
	{
		echo 'Error: ',  $e->getMessage(), "\n";
	}
	
?>
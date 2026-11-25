<?php
	include_once ("cConectaBD.php");
	include_once ("PHPExcel.php");
	
	# Declarando variables
	$objPHPExcel = new PHPExcel();
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	$bd = new ConectaBD();
	$bd->setNombreBD("pagj690609hv7");
	$y = 1;
	$fileName = "ExportaCFDiExcel.xls";
	//$fechaInicial = $_POST('fechaInicial');
	//$fechaFinal = $_POST('fechaFinal');
	
	$fechaInicial = "2015-07-01";
	$fechaFinal = "2015-12-31";

	# Propiedades del achivo de Excel
	$objPHPExcel->getProperties()->setCreator("nscco.com.mx")
		->setLastModifiedBy("Portal NSCCO")
		->setTitle("Reporte Excel")
		->setSubject("Exportado desde PHP")
		->setDescription("")
		->setKeywords("")
		->setCategory("");
		
	# Propiedades por default de las celdas
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(11);
	
	# Cabecera de la hoja
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue("A".$y, "Fecha")
		->setCellValue("B".$y, "Folio")
		->setCellValue("C".$y, "Razon Social")
		->setCellValue("D".$y, "RFC")
		->setCellValue("E".$y, "SubTotal")
		->setCellValue("F".$y, "Impuestos")
		->setCellValue("G".$y, "Total")
		->setCellValue("H".$y, "UUID");
	
	$objPHPExcel->getActiveSheet()
		->getStyle('A1:H1')
		->getFill()
		->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		->getStartColor()->setARGB('FFEEEEEE');
	
	$sql = "SELECT fechaXML,folioXML,razonSocialXML,rfcXML,subTotalXML,impuestoXML,totalXML,uuid 
		FROM xmls WHERE fechaXML BETWEEN '$fechaInicial' AND '$fechaFinal'";
	$query = $bd->consulta($sql);
	
	while ($row = mysql_fetch_array($query)) {
		$y++;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".$y, $row['fechaXML'])
			->setCellValue("B".$y, $row['folioXML'])
			->setCellValue("C".$y, $row['razonSocialXML'])
			->setCellValue("D".$y, $row['rfcXML'])
			->setCellValue("E".$y, $row['subTotalXML'])
			->setCellValue("F".$y, $row['impuestoXML'])
			->setCellValue("G".$y, $row['totalXML'])
			->setCellValue("H".$y, $row['uuid']);
	}
	
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename:$fileName");
	header("Cache-Control: max-age=0");
	print $objWriter->save("php://output");
	exit;
?>
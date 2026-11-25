<?php
	session_start();
	include_once ("cDoctoCFDI.php");
	$doctoPDF = new DoctoCFDI();
	$doctoPDF->bd->setNombreBD($_SESSION['bd']);
	
	$uuid = $_GET['uuid'];
	$filePDF = mysql_fetch_assoc($doctoPDF->getInfoXML($uuid));
	
	$mimeArchivoPDF = $filePDF['mimearchivopdf'];
	$sizeArchivoPDF = $filePDF['sizearchivopdf'];
	$nameArchivoPDF = $filePDF['uuid'] . ".pdf";
	$content = $filePDF['archivopdf'];
	
	// echo "<pre>";
	// print_r($filePDF['archivopdf']);
	// echo "</pre>";
	
	header("Content-Type: $mimeArchivoPDF");
	header("Content-Disposition: attachment; filename=$nameArchivoPDF");
	//header("Content-transfer-encoding: binary");
    
	print base64_decode($content);
	// print $content;
?>
<?php
	session_start();
	include_once ("cDoctoCFDI.php");
	$doctoXML = new DoctoCFDI($_SESSION['bd']);
	//$doctoXML->bd->setNombreBD($_SESSION['bd']);
	
	$uuid = $_GET['uuid'];
	// $fileXML = mysql_fetch_assoc($doctoXML->getInfoXML($uuid));
	$fileXML = $doctoXML->getInfoXML($uuid)->fetch(PDO::FETCH_ASSOC);
	
	$typeArchivoXML = $fileXML['typeArchivoXML'];
	$sizeArchivoXML = $fileXML['sizeArchivoXML'];
	$nameArchivoXML = $fileXML['uuid'] . ".xml";
	$content = $fileXML['archivoXML'];
	
	// echo "<pre>";
	// print_r($fileXML);
	// echo "</pre>";
	
	header("Content-Type: $typeArchivoXML");
	header("Content-Disposition: attachment; filename=$nameArchivoXML");
	header("Content-transfer-encoding: binary");
    
	print $content;
?>
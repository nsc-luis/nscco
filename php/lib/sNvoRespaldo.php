<?php
	session_start();
	include_once ("cConectaBD.php");
	
	chdir ("../../");
	$conectaBD = new ConectaBD();
	$zip = new ZipArchive();
	$dirBackUP = './respBD/';
	$user = $conectaBD->getBdUsr();
	$pass = $conectaBD->getBdPass();
	$host = $conectaBD->getBdHost();
	$bd = $_SESSION['bd'];
	
	$archivoZip = $dirBackUP . $_POST['nomBackup'] . "_" . date('Y-m-d') . "T"  . date('H-i-s') . ".zip";
	$bkpBD = $dirBackUP . $bd . ".sql";
	$bkpPrincipalBD = $dirBackUP . "principalbd.sql";
	
	$dump = "mysqldump -h$host -u$user -p$pass $bd > $bkpBD";
	system($dump,$result);
	$dump1 = "mysqldump -h$host -u$user -p$pass principalbd > $bkpPrincipalBD";
	system($dump1,$result1);
	
	if ($zip->open($archivoZip,ZipArchive::CREATE)) {
		$zip->addFile($bkpBD);
		$zip->addFile($bkpPrincipalBD);
		$zip->close();
	}
	unlink($bkpBD);
	unlink($bkpPrincipalBD);
	
	if ($result == 0 && $result1 == 0) {
		echo "El respaldo se ha generado correctamente :¬)";
		$url = "http://" . $_SERVER['SERVER_NAME'] . "/system.php?mod=respaldar";
		echo "<SCRIPT>window.location='$url';</SCRIPT>"; 
	} else {
		echo "Parece que algo ha fallado con el respaldo :¬( <br />
			Considera cambiar al programador.";
	}
?>

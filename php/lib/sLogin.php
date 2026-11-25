<?php
	include_once ("cConectaBD.php");
	
	$cveUsrFrm = $_POST['cveUsrFrm'];
	$passUsrFrm = $_POST['passUsrFrm'];
	$cveUsrFrm = stripslashes($cveUsrFrm);
	$passUsrFrm = stripslashes($passUsrFrm);
	//$cveUsrFrm = mysqli_real_escape_string($cveUsrFrm);
	//$passUsrFrm = mysqli_real_escape_string($passUsrFrm);
	
	$bd = new ConectaBD("principalbd");
	
	// consultas a base de datos
	$sql = "SELECT * FROM usuarios WHERE usuario = '$cveUsrFrm' AND password = '$passUsrFrm'";
	$consulta = $bd->consulta($sql);
	// while ($row = mysqli_fetch_array($consulta)) {
	while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
		$idUsuario = $row['idUsuario'];
		//$passUsr = $row['passUsr'];
		$nombre = $row['nombre'];
		//$eMail = $row['eMail'];
		//$idPerfil = $row['idPerfil'];
		//$cveEmpresas = $row['cveEmpresas'];
	}
	// $count = mysqli_num_rows($consulta);
	$count = $consulta->rowCount();
	if($count==1){
		// Register $myusername, $mypassword and redirect to file "login_success.php"
		session_start();
		// session_register($TUser_iIdUsuario);
		$_SESSION['idUsuario'] = $idUsuario;
		$_SESSION['usuario'] = strtoupper($cveUsrFrm);
		$_SESSION['nombre'] = $nombre;
		$_SESSION['empresa'] = "JOSUE PALACIOS GRIMALDO";
		$_SESSION['bd'] = "pagj690609hv7";
		$_SESSION['rfcEmpresa'] = 'pagj690609hv7';
		//$_SESSION['eMail'] = $eMail;
		//$_SESSION['idPerfil'] = $idPerfil;
		//$_SESSION['cveEmpresas'] = $cveEmpresas;
		$url = "http://" . $_SERVER['SERVER_NAME'] . "/system.php";
		echo "<SCRIPT>window.location='$url';</SCRIPT>"; 
	}
	else {
		echo "
			<style>#msjIngreso { color: red; }</style>
			* Error de inicio de sesion. Verifique su usuario y contrase&ntilde;a.
		";
	}
	$bd->cerrarBD();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>:: Sistema de reportes NSC ::</title>
	<link type="text/css" href="css/jquery-ui.min.css" rel="stylesheet">
	<script src="js/jquery.js" language="javascript"></script>
	<script src="js/jquery-ui.min.js" language="javascript"></script>
	<script src="js/angular.min.js" language="javascript"></script>
	<script src="js/fnGenerales.js" language="javascript"></script>
	<style>
		body { font-size: 12px; font-family: arial }
		fieldset { width: 250px; margin: auto; padding: 20px; }
		label { display: block; width: 80px; float: left; background-color: #F2F2F2; line-height: 20px; margin-right: 10px; margin-top: 5px;}
		input { width: 120px; margin-top: 5px; }
		legend { padding: 5px; color: white; background-color: black; text-transform: uppercase; }
		.centrado { text-align: center; }
	</style>
</head>
<body>
	<div id="loginForm">
		<fieldset>
			<legend>Ingreso</legend>
			<label>Usuario:</label>
			<input type="text" id="cveUsrFrm" /> <br />
			<label>Contrase&ntilde;a:</label>
			<input type="password" id="passUsrFrm" /> <br />
			<p class="centrado"><button type="button" id="ingresoBtn">Ingresar</button></p>
			<p class="centrado"><a href="#">&#191;Olvidaste la contrase&ntilde;a?</a></p>
		</fieldset>
		<div id="msjIngreso" class="centrado">
		</div>
	</div>
</body>
</html>
$(function() {
	$( "#loginForm" ).keydown(function(event) {
		if (event.which==13) { login(); }
	});
	$( "#ingresoBtn" ).click (function(){
		if ( $( "#cveUsrFrm" ).val() == "" 
		|| $( "#passUsrFrm" ).val() == "" )
		{
			alert ("Debe proporcionar un usuario y/o contraseña.")
		} else {
			login();
		}
	});
	$( "#cerrarSesion" ).click (function(){
		var host = window.location.origin;
		window.location = host + '/php/lib/sCerrarSesion.php';
	});
	
	$("#dlgListaCtesRespaldos, #dlgDetalleResp, #dlgListaCtesInventario, #dlgRptServXCte, #dlgRptCadAV, #dlgRptCadOffice, #dlgRptCadOfficeAvPorFecha").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	
	$("#btnVerBitRespCte,#btnVerInvCte, #btnRptServXCte, #btnRptCadAV, #btnRptCadOfficeAvPorFecha")
	.button({
		icons: { primary: "ui-icon-clipboard" }
	}).css("float","right");
	
	$( ".calendario" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});
	
	$("#statusInventario").tabs();
});

/* LLAMA PROCESO LOGIN */
function login(){
	parametros = {
		"cveUsrFrm"	: $( "#cveUsrFrm" ).val(),
		"passUsrFrm"	: $( "#passUsrFrm" ).val()
	};
	$.ajax({
		data	: parametros,
		url		: "php/lib/sLogin.php",
		type	: "post",
		beforeSend: function() {
			$("#msjIngreso").html("Cargando, por favor espere . . .");
		},
		success:  function (response) {
			$("#msjIngreso").html(response);
		}
	});
}

/* VALIDACION DE CAMPOS */
function validar(){
	numero = /^[0-9]+$/;
	email = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+\.([a-zA-Z]{2}|[a-zA-Z]{3})$/;
	fecha = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/;
	rfc = /^[a-zA-Z]{3,4}[0-9]{6}[a-zA-Z0-9]{3}$/;
	password = /^[a-zA-Z0-9]{6,15}$/;
	telefono = /[0-9]{2,3}-[0-9]{7,8}$/;
	clave = /^[a-zA-Z]+[a-zA-Z0-9-_.]{2,15}$/;
	msj = "";
	valido = 1;
	$( "input" ).each(function(){
		
		if ( $(this).attr('validar')=="numero" && isNaN($(this).val())
				&& $(this).val() != "" ) { 
			msj += "Ingrese una cantidad valida. Ej: 1.00\r\n";
			$(this).css({"border":"1px solid red"});
			$(this).focus();
			valido = 0;
		}
		if ( $(this).attr('validar')=="descripcion" && $(this).val() == "" ) { 
			msj += "Ingrese un nombre descriptivo. \r\n";
			$(this).css({"border":"1px solid red"});
			$(this).focus();
			valido = 0;
		}
		if ( $(this).attr('validar')=="email" && !$(this).val().match(email) 
				&& $(this).val() != "" ) {
			msj += "Ingrese un email valido. Ej: buzon@dominio.com\r\n";
			$(this).css({"border":"1px solid red"});
			$(this).focus();
			valido = 0;
		}
		if ( $(this).attr('validar')=="fecha" && !$(this).val().match(fecha)
				&& $(this).val() != "" ) {
			msj += "Ingrese una fecha valida. Ej: 2015-01-01\r\n";
			$(this).css({"border":"1px solid red"});
			$(this).focus();
			valido = 0;
		}
		if ( $(this).attr('validar')=="telefono" && !$(this).val().match(telefono) 
				&& $(this).val() != "" ) {
			msj += "Ingrese un numero telefonico valido. Ej: 81-83670772 (LADA-NoLocal)\r\n";
			$(this).css({"border":"1px solid red"});
			$(this).focus();
			valido = 0;
		}
		if ( $(this).attr('validar')=="rfc" && !$(this).val().match(rfc) 
				&& $(this).val() != "" ) {
			msj += "Ingrese un RFC valido. Ej: XAXX010101000\r\n";
			$(this).css({"border":"1px solid red"});
			$(this).focus();
			valido = 0;
		}
		if ( $(this).attr('validar')=="password" && !$(this).val().match(password) 
				|| $(this).attr('validar')=="password" && $(this).val() == "" ) {
			msj += "El password debe contener de 6 a 15 caracteres\r\n";
			$(this).css({"border":"1px solid red"});
			$(this).focus();
			valido = 0;
		}
		if ( $(this).attr('validar')=="clave" && !$(this).val().match(clave) ) {
			msj += "El campo debe comenzar con una letra y debe contener de 4 a 15 caracteres\r\n";
			$(this).css({"border":"1px solid red"});
			$(this).focus();
			valido = 0;
		}
		
	});
	if ( $("#password").val() != $("#confirmacion").val() ) {
		msj += "El password y la confirmacion no son iguales.\r\n";
		$(this).css({"border":"1px solid red"});
		$(this).focus();
		valido = 0;
	}
	if ( $("#cveCte").val() == 0 ) {
		msj += "Debe seleccionar un cliente.\r\n";
		$("#cveCte").css({"border":"1px solid red"});
		$("#cveCte").focus();
		valido = 0;
	}
	if (valido==0) {
		alert (msj);
		return valido;
	}
}

var mainModule = angular.module('mainModule',[]);

mainModule.controller('menuPrincipal',function($scope,$http){
	$scope.dlgListaCtesRespaldos = function(){
		$scope.displayFiltroFechaCaducidad = "none";
		$http.get("php/lib/fnGenerales.php?proceso=listaCtesRespaldos")
		.then(function(response){$scope.listaCtesRespaldos = response.data;});
		$("#dlgListaCtesRespaldos").dialog("open");
	}

	$scope.chFiltroFechaCaducidad = function() {
		$scope.optFiltroFechaCaducidad == "mayorIgualFecha" ? $scope.displayFiltroFechaCaducidad = "block" : $scope.displayFiltroFechaCaducidad = "none";
	}
	
	$scope.iraBitRespCte = function(info) {
		var url = window.location.origin.toString();
		if (info == undefined) { alert ("ERROR: Debes seleccionar algún cliente."); return false; }
		window.location = url + '/system.php?mod=bitacoraDeRespaldosCte&nombreCte=' + info;
	}
	
	$scope.defaultListaCFDIProv = function(){
		var fecha = new Date();
		var mes = (fecha.getMonth() + 1) < 10 ? "0" + (fecha.getMonth() + 1) : (fecha.getMonth() + 1);
		var currentYearMonth = fecha.getFullYear() + "-" + mes;
		var url = window.location.origin.toString();
		// window.location = url + '/system.php?mod=verCFDIs&rfcProveedor=0&fecha=' + currentYearMonth + '&tipoDocto=4';
		window.location = url + '/system.php?mod=verCFDIs&filtro=default&fecha=' + currentYearMonth;
	}
	
	$scope.dlgInventarioCte = function(){
		$http.get("php/lib/fnGenerales.php?proceso=listaCtesInventario")
		.then(function(response){$scope.listaCtesInventario = response.data;});
		$("#dlgListaCtesInventario").dialog("open");
	};
	
	$scope.inventarioCte = function() {
		var idCte = $("#idCteInventario").val();
		var url = window.location.origin.toString();
		window.location = url + '/system.php?mod=inventarioCte&id=' + idCte;
	}
	
	$scope.dlgRptServXCte = function(){
		$http.get("php/lib/fnGenerales.php?proceso=listaClientes")
		.then(function(response){$scope.listaClientes = response.data;});
		$("#dlgRptServXCte").dialog("open");
	}
	
	$scope.dlgRptCadOfficeAvPorFecha = function(){
		$http.get("php/lib/fnGenerales.php?proceso=listaClientes")
		.then(function(response){$scope.listaClientes = response.data;});
		$("#dlgRptCadOfficeAvPorFecha").dialog("open");
	}
	
	$scope.btnRptCadOfficeAvPorFecha = function(){
		window.open(
			"rptCadOfficeAvPorFecha.php?idCliente=" + $scope.rptCadOfficeAvPorFechaIdCliente + "&fecha=" + $scope.rptCadOfficeAvPorFecha
			,"Reporte de caducidad de Office y AV por cliente y por fecha."
			,"directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800"
		);
	}
	
	$scope.btnRptServXCte = function(){
		
		var msg = "";
		if ($scope.fIniRtpxCte == undefined || $scope.fIniRtpxCte == "") {
			msg += "Debes seleccionar una fecha inicial\n";
		}
		if ($scope.fFinRtpxCte == undefined || $scope.fFinRtpxCte == "") {
			msg += "Debes seleccionar una fecha final\n";
		}
		if (msg != "") {
			alert (msg);
			return false;
		}
		$("#dlgRptServXCte").dialog("close");
		
		var data = {
			"proceso" : "rptServXCte",
			"cveCte" : $scope.cveCteRptxCte,
			"fInicial" : $scope.fIniRtpxCte,
			"fFinal" : $scope.fFinRtpxCte
		};
		
		window.open(
			"rptServXCte.php?cveCte=" + $scope.cveCteRptxCte + "&fInicial=" + $scope.fIniRtpxCte + "&fFinal=" + $scope.fFinRtpxCte
			,"Reporte de servicios x cliente"
			,"directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800"
		);
	}
	$scope.rptCaducidadAvUnaSemana = function(){
		window.open(
			"rptCaducidadAV.php"
			,"Reporte de servicios x cliente"
			,"directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800"
		);
	}
	
	$scope.dlgRptCadAV = function() {
		$http.get("php/lib/fnGenerales.php?proceso=listaCtesInventario")
		.then(function(response){$scope.listaCtesInventario = response.data;});
		$("#dlgRptCadAV").dialog("open");
	}
	
	$scope.btnRptCadAV = function() {
		window.open(
			"rptCaducidadAV.php?idCte=" + $scope.rptCadAVIdCte
			,"Reporte caducidad de AV x cliente"
			,"directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800"
		);
		$("#dlgRptCadAV").dialog("close");
	}
	
	$scope.dlgRptCadOffice = function() {
		$http.get("php/lib/fnGenerales.php?proceso=listaCtesInventario")
		.then(function(response){$scope.listaCtesInventario = response.data;});
		$("#dlgRptCadOffice").dialog("open");
	}
	
	$scope.btnRptCadOffice = function() {
		var filtroFecha = $scope.displayFiltroFechaCaducidad == "block" ? $scope.filtroFechaCaducidad : "completo";
		window.open(
			"rptCaducidadOffice.php?idCte=" + $scope.rptCadOfficeIdCte + "&filtroFecha=" + filtroFecha
			,"Reporte caducidad de Office x cliente"
			,"directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800"
		);
		$("#dlgRptCadOffice").dialog("close");
	}
});

mainModule.controller('ultimos3Respaldos',function($scope,$http){
	$http.get("php/lib/fnGenerales.php?proceso=ultimos3Respaldos")
	.then(function(response){$scope.ultimos3Respaldos = response.data;});
	
	$scope.detalleBitResp = function(info) {
		$scope.currentRegistro = info;
		$("#dlgDetalleResp").dialog("open");
		//alert (currentRegistro.toSource());
	}
});

mainModule.controller('statusLicencias', function($scope,$http) {
	$http.get("php/lib/fnGenerales.php?proceso=licenciasCaducidad15dias")
	.then(function(response){$scope.licenciasCaducidad15dias = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=officeCaducados")
	.then(function(response){$scope.officeCaducados = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=avCaducados")
	.then(function(response){$scope.avCaducados = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=inventarioSinAsignacion")
	.then(function(response){$scope.inventarioSinAsignacion = response.data;});
});

mainModule.filter('statusBitRespFilter', function (){
	return function(strDetalle) {
		var statusResp = strDetalle == "" ? "Incompleto" : "OK"
		return statusResp;
	};
});
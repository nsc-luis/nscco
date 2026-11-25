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
	
	$("#dlgListaCtesRespaldos").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	
	$("#btnVerBitRespCte")
	.button({
		icons: { primary: "ui-icon-clipboard" }
	}).css("float","right");
});

/* LLAMA PROCESO LOGIN */
function login(){
	parametros = {
		"cveUsrFrm"	: $( "#cveUsrFrm" ).val(),
		"passUsrFrm"	: $( "#passUsrFrm" ).val()
	};
	$.ajax({
		data	: parametros,
		url		: "php/lib/slogin.php",
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
		$http.get("php/lib/fnGenerales.php?proceso=listaCtesRespaldos")
		.then(function(response){$scope.listaCtesRespaldos = response.data;});
		$("#dlgListaCtesRespaldos").dialog("open");
	}
	
	$scope.iraBitRespCte = function(info) {if (info == undefined) { alert ("ERROR: Debes seleccionar algún cliente."); return false; }
		var url = window.location.origin.toString();
		// alert (url);
		window.location = url + '/system.php?mod=bitacoraDeRespaldosCte&nombreCte=' + info;
	}
});
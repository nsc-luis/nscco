$(function() {
	/* MODULO USUARIOS */
	
	$("#dlgFrmUsr, #dlgBorrarUsr").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	
	$("#btnGuardarNvoUsr, #btnGuardarUsr")
	.button({icons: { primary: "ui-icon-disk" }})
	.css({ float:"right" });
	
	$("#btnNvoUsr").button({icons: { primary: "ui-icon-plus" }});
	
	$("#confirmaBorrar")
	.button({icons: { primary: "ui-icon-trash" }})
	.css({ float:"right" });
});

// var modUsr = angular.module('modUsr',[]);
mainModule.controller('catUsuarios',function($scope,$http){
	$http.get("php/lib/fnGenerales.php?proceso=listaUsuarios")
	.then(function(response){$scope.listaUsuarios = response.data;});
	
	//// EDITAR USUARIO ////
	$scope.dlgInfoUsr = function(info){
		$scope.currentUsr = info;
		$("#btnGuardarUsr").show();
		$("#btnGuardarNvoUsr").hide();
		$("#dlgFrmUsr").dialog("open");
	}
	$scope.guardarUsr = function (info) {
		if (info.password != info.confirmarPass) {
			alert ("Las contraseñas no son iguales.");
			return false;
		}
		info.proceso = "guardarUsr";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("msjUsr").html(response);
			}
		});
		$("#dlgFrmUsr").dialog("close");
	}
	
	//// NUEVO USUARIO ////
	$scope.dlgNvoUsr = function(){
		$scope.currentUsr = "";
		$("#btnGuardarUsr").hide();
		$("#btnGuardarNvoUsr").show();
		$("#dlgFrmUsr").dialog("open");
	}
	$scope.guardarNvoUsr = function (info) {
		if (info.password != info.confirmarPass) {
			alert ("Las contraseñas no son iguales.");
			return false;
		}
		info.proceso = "guardarNvoUsr";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjUsr").html(response);
			}
		});
	}
	
	//// BORRAR USUARIO ////
	$scope.dlgBorrarUsr = function(info) {
		$scope.currentUsrBorrar = info;
		$("#dlgBorrarUsr").dialog("open");
	}
	$scope.borrarUsr = function(info) {
		info.proceso = "borrarUsr";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjUsr").html(response);
			}
		});
	}
});

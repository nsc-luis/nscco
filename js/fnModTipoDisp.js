$(function() {
	/* MODULO TIPOS DE DSIPOSITIVOS */
	
	$("#dlgFrmTipoDisp, #dlgBorrarDisp").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	
	$("#btnGuardarNvoTipoDisp, #btnGuardarTipoDisp")
	.button({icons: { primary: "ui-icon-disk" }})
	.css({ float:"right" });
	
	$("#btnNvoTipoDisp").button({icons: { primary: "ui-icon-plus" }});
	
	$("#confirmaBorrar")
	.button({icons: { primary: "ui-icon-trash" }})
	.css({ float:"right" });
});

mainModule.controller('catTipoDispositivo',function($scope,$http){
	$http.get("php/lib/fnGenerales.php?proceso=listaTipoDispositivo")
	.then(function(response){$scope.listaTipoDispositivo = response.data;});
	
	//// EDITAR TIPO DE DISPOSITIVO ////
	$scope.dlgInfoTipoDisp = function(info){
		$scope.currentDisp = info;
		$("#btnGuardarTipoDisp").show();
		$("#btnGuardarNvoTipoDisp").hide();
		$("#dlgFrmTipoDisp").dialog("open");
	}
	$scope.guardarTipoDisp = function (info) {
		info.proceso = "guardarTipoDisp";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("msjTipoDisp").html(response);
			}
		});
		$("#dlgFrmTipoDisp").dialog("close");
	} 
	
	//// NUEVO TIPO DE DISPOSITIVO ////
	$scope.dlgNvoTipoDisp = function(){
		$scope.currentDisp = "";
		$("#btnGuardarTipoDisp").hide();
		$("#btnGuardarNvoTipoDisp").show();
		$("#dlgFrmTipoDisp").dialog("open");
	}
	$scope.guardarNvoTipoDisp = function (info) {
		info.proceso = "guardarNvoTipoDisp";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjTipoDisp").html(response);
			}
		});
	}
	
	//// BORRAR TIPO DE DISPOSITIVO ////
	$scope.dlgBorrarDisp = function(info) {
		$scope.currentDispBorrar = info;
		$("#dlgBorrarDisp").dialog("open");
	}
	$scope.borrarTipoDisp = function(info) {
		info.proceso = "borrarTipoDisp";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjTipoDisp").html(response);
			}
		});
	}
});

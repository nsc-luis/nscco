$(function() {
	/* MODULO UNIDADES */
	
	$("#dlgFrmUni, #dlgBorrarUni").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	
	$("#btnGuardarNvaUni, #btnGuardarUni")
	.button({icons: { primary: "ui-icon-disk" }})
	.css({ float:"right" });
	
	$("#btnNvaUni").button({icons: { primary: "ui-icon-plus" }});
	
	$("#confirmaBorrar")
	.button({icons: { primary: "ui-icon-trash" }})
	.css({ float:"right" });
});

// var modUni = angular.module('modUni',[]);
mainModule.controller('catUnidades',function($scope,$http){
	$http.get("php/lib/fnGenerales.php?proceso=listaUnidades")
	.then(function(response){$scope.listaUnidades = response.data;});
	
	//// EDITAR UNIDAD ////
	$scope.dlgInfoUni = function(info){
		$scope.currentUni = info;
		$("#btnGuardarUni").show();
		$("#btnGuardarNvaUni").hide();
		$("#dlgFrmUni").dialog("open");
	}
	$scope.guardarUni = function (info) {
		info.proceso = "guardarUni";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("msjUsr").html(response);
			}
		});
		$("#dlgFrmUni").dialog("close");
	}
	
	//// NUEVO UNIDAD ////
	$scope.dlgNvaUni = function(){
		$scope.currentUni = "";
		$("#btnGuardarUni").hide();
		$("#btnGuardarNvaUni").show();
		$("#dlgFrmUni").dialog("open");
	}
	$scope.guardarNvaUni = function (info) {
		info.proceso = "guardarNvaUni";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjUni").html(response);
			}
		});
	}
	
	//// BORRAR UNIDAD ////
	$scope.dlgBorrarUni = function(info) {
		$scope.currentUniBorrar = info;
		$("#dlgBorrarUni").dialog("open");
	}
	$scope.borrarUni = function(info) {
		info.proceso = "borrarUni";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjUni").html(response);
			}
		});
	}
});

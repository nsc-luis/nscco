$(function() {
	/* MODULO TIPOS DE LICENCIA */
	
	$("#dlgFrmTipoLic, #dlgBorrarLic").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	
	$("#btnGuardarNvoTipoLic, #btnGuardarTipoLic")
	.button({icons: { primary: "ui-icon-disk" }})
	.css({ float:"right" });
	
	$("#btnNvoTipoLic").button({icons: { primary: "ui-icon-plus" }});
	
	$("#confirmaBorrar")
	.button({icons: { primary: "ui-icon-trash" }})
	.css({ float:"right" });
});

mainModule.controller('catTipoLicencia',function($scope,$http){
	$http.get("php/lib/fnGenerales.php?proceso=listaTipoLicencia")
	.then(function(response){$scope.listaTipoLicencia = response.data;});
	
	//// EDITAR TIPO DE LICENCIA ////
	$scope.dlgInfoTipoLic = function(info){
		$scope.currentLic = info;
		$("#btnGuardarTipoLic").show();
		$("#btnGuardarNvoTipoLic").hide();
		$("#dlgFrmTipoLic").dialog("open");
	}
	$scope.guardarTipoLic = function (info) {
		info.proceso = "guardarTipoLic";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("msjTipoLic").html(response);
			}
		});
		$("#dlgFrmTipoLic").dialog("close");
	} 
	
	//// NUEVO TIPO DE LICENCIA ////
	$scope.dlgNvoTipoLic = function(){
		$scope.currentLic = "";
		$("#btnGuardarTipoLic").hide();
		$("#btnGuardarNvoTipoLic").show();
		$("#dlgFrmTipoLic").dialog("open");
	}
	$scope.guardarNvoTipoLic = function (info) {
		info.proceso = "guardarNvoTipoLic";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjTipoLic").html(response);
			}
		});
	}
	
	//// BORRAR TIPO DE LICENCIA ////
	$scope.dlgBorrarLic = function(info) {
		$scope.currentLicBorrar = info;
		$("#dlgBorrarLic").dialog("open");
	}
	$scope.borrarTipoLic = function(info) {
		info.proceso = "borrarTipoLic";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjTipoLic").html(response);
			}
		});
	}
});

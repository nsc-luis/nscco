$(function() {
	/* MODULO DE LICENCIAS */
	
	$("#dlgFrmLic, #dlgBorrarLic, #dlgDispAsignados").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	
	$("#btnGuardarNvaLic, #btnGuardarLic")
	.button({icons: { primary: "ui-icon-disk" }})
	.css({ float:"right" });
	
	$("#btnNvaLic").button({icons: { primary: "ui-icon-plus" }});
	
	$("#confirmaBorrar")
	.button({icons: { primary: "ui-icon-trash" }})
	.css({ float:"right" });
});

mainModule.controller('catLicenciasSoftware',function($scope,$http){
	$http.get("php/lib/fnGenerales.php?proceso=listaLicenciaSoftware")
	.then(function(response){$scope.listaLicenciaSoftware = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaCteConLicencias")
	.then(function(response){$scope.listaCteConLicencias = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaCtesParaLic")
	.then(function(response){$scope.listaCtesParaLic = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaTipoLic")
	.then(function(response){$scope.listaTipoLic = response.data;});
	
	//// EDITAR LICENCIA ////
	$scope.dlgInfoLic = function(info){
		$scope.currentLic = info;
		$("#btnGuardarLic").show();
		$("#btnGuardarNvaLic").hide();
		$("#dlgFrmLic").dialog("open");
	}
	$scope.guardarLic = function (info) {
		info.idTipoLicencia = $("#idTipoLicencia").val();
		info.idCliente = $("#idCliente").val();
		info.proceso = "guardarLic";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("msjLic").html(response);
			}
		});
		$("#dlgFrmLic").dialog("close");
	}
	
	//// NUEVA LICENCIA ////
	$scope.dlgNvaLic = function(){
		$scope.currentLic = "";
		$("#btnGuardarLic").hide();
		$("#btnGuardarNvaLic").show();
		$("#dlgFrmLic").dialog("open");
	}
	$scope.guardarNvaLic = function (info) {
		info.idTipoLicencia = $("#idTipoLicencia").val();
		info.idCliente = $("#idCliente").val();
		info.proceso = "guardarNvaLic";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjLic").html(response);
			}
		});
	}
	
	//// BORRAR LICENCIA ////
	$scope.dlgBorrarLic = function(info) {
		$scope.currentLicBorrar = info;
		$("#dlgBorrarLic").dialog("open");
	}
	$scope.borrarLic = function(info) {
		info.proceso = "borrarLic";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjLic").html(response);
			}
		});
	}
	
	$scope.dlgDispAsignados = function(info){
		$scope.currentLicAsig = info;
		console.log(info);
		$http.get("php/lib/fnGenerales.php?proceso=dispAsignados&idLic=" + info.idlicencia)
		.then(function(response){$scope.dispAsignados = response.data;});
		$("#dlgDispAsignados").dialog("open");
	}
});

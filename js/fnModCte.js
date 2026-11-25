$(function() {
	$("#dlgFrmCte, #dlgSucursalesCte, #dlgFrmSucursalCte, #dlgBorrarCte").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	
	$("#btnGuardarNvoCte, #btnGuardarCte, #btnGuardarNvaSucCte, #btnGuardarSucCte")
	.button({icons: { primary: "ui-icon-disk" }})
	.css({ float:"right" });
	
	$("#btnNvoCte2, #btnNvaSucCte2").button({icons: { primary: "ui-icon-plus" }});
	$("#btnNvaSucCte2").button({icons: { primary: "ui-icon-home" }});
	$("#confirmaBorrar")
	.button({icons: { primary: "ui-icon-trash" }})
	.css({ float:"right" });
	
	$("#chkBoxPoliza").change(function(){
		if ($("#chkBoxPoliza").prop("checked")) { 
			$("#datosDeLaPoliza").css("display","block");
		} else { 
			$("#datosDeLaPoliza").css("display","none");
		}
	});
});

// var modCte = angular.module('modCte',[]);
mainModule.controller('catClientes',function($scope,$http){
	$http.get("php/lib/fnGenerales.php?proceso=listaClientes")
	.then(function(response){$scope.listaClientes = response.data;});
	
	//// EDITAR CLIENTE ////
	$scope.dlgInfoCte = function(info){
		//console.log(info);
		$("#btnGuardarCte").show();
		$("#btnGuardarNvoCte").hide();
		$scope.currentCte = info;
		if (info.poliza == "1") { 
			$("#datosDeLaPoliza").css("display","block");
		} else { 
			$("#datosDeLaPoliza").css("display","none");
		}
		$("#dlgFrmCte").dialog("open");
	}
	$scope.guardarCte = function (info) {
		info.proceso = "guardarCte";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("msjCte").html(response);
			}
		});
		$("#dlgFrmCte").dialog("close");
		location.reload();
	}
	
	//// NUEVO CLIENTE ////
	$scope.dlgNvoCte = function(){
		$scope.currentCte = "";
		$("#btnGuardarCte").hide();
		$("#btnGuardarNvoCte").show();
		$("#dlgFrmCte").dialog("open");
	}
	$scope.guardarNvoCte = function (info) {
		info.proceso = "guardarNvoCte";
		if (!info.nombre || !info.clave) {
			alert ("Error!.\nDebe ingresar una clave y nombre del cliente.");
			return false;
		}
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjCte").html(response);
			}
		});
	}
	
	//// BORRAR CLIENTE ////
	$scope.dlgBorrarCte = function(info) {
		$scope.currentCteBorrar = info;
		$("#dlgBorrarCte").dialog("open");
	}
	$scope.borrarCte = function(info) {
		info.proceso = "borrarCte";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjCte").html(response);
			}
		});
	}
	
	//// EDITAR SUCURSALES DEL CLIENTE ////
	$scope.dlgSucCte = function(info) {
		$scope.currentCteSuc = info;
		$scope.currentCveCte = info.clave;
		
		$http.get("php/lib/fnGenerales.php?proceso=getSucPorCte&clave=" + info.clave)
		.then(function(response){$scope.listaSucursalesCte = response.data;});
		$("#dlgSucursalesCte").dialog("open");
	}
	$scope.infoSucCte = function(info){
		$scope.currentSucCte = info;
		$("#btnGuardarNvaSucCte").hide();
		$("#btnGuardarSucCte").show();
		$("#dlgFrmSucursalCte").dialog("open");
	}
	$scope.guardarSucCte = function (info) {
		info.proceso = "guardarSucCte";
		if (!info.nombre) { 
			alert ("Error!\nDebe capturar al menos el nombre de la sucursal"); 
			return false; 
		}
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjCte").html(response);
			}
		});
	}
	
	//// NUEVA SUCURSALES DEL CLIENTE ////
	$scope.dlgNvaSucCte = function(info) {
		// console.log(info);
		$scope.currentSucCte = "";
		$("#btnGuardarNvaSucCte").show();
		$("#btnGuardarSucCte").hide();
		$("#dlgFrmSucursalCte").dialog("open");
	}
	$scope.guardarNvaSucCte = function (info) {
		info.proceso = "guardarNvaSucCte";
		info.idCliente = $scope.currentCteSuc.idCliente;
		info.cveCte = $scope.currentCveCte;
		if (!info.nombre) { 
			alert ("Error!\nDebe capturar al menos el nombre de la sucursal"); 
			return false; 
		}
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjCte").html(response);
			}
		});
	}
	$scope.dlgBorrarSucCte = function(info) {
		$scope.currentCteBorrar = info;
		$("#dlgBorrarSucCte").dialog("open");
	}
	
	//// BORRAR SUCURSAL DEL CLIENTE ////
	$scope.borraSucCte = function(info) {
		info.proceso = "borraSucCte";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjCte").html(response);
			}
		});
	}
});

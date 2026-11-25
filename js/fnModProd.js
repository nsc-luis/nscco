$(function() {
	/* MODULO PRODUCTOS */
	
	$("#dlgFrmProd, #dlgBorrarProd").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	
	$("#btnGuardarNvoProd, #btnGuardarProd")
	.button({icons: { primary: "ui-icon-disk" }})
	.css({ float:"right" });
	
	$("#btnNvoProd").button({icons: { primary: "ui-icon-plus" }});
	
	$("#confirmaBorrar")
	.button({icons: { primary: "ui-icon-trash" }})
	.css({ float:"right" });
});

// var modProd = angular.module('modProd',[]);
mainModule.controller('catProductos',function($scope,$http){
	$http.get("php/lib/fnGenerales.php?proceso=listaProductos")
	.then(function(response){$scope.listaProductos = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaUnidades")
	.then(function(response){$scope.listaUnidades = response.data;});
	
	//// EDITAR PRODUCTO ////
	$scope.dlgInfoProd = function(info){
		$scope.currentProd = info;
		//alert(JSON.stringify(info));
		$("#btnGuardarProd").show();
		$("#btnGuardarNvoProd").hide();
		$("#dlgFrmProd").dialog("open");
	}
	$scope.guardarProd = function (info) {
		if (!info.nombre) {
			alert ("Error!\nDebe capturar el nombre del producto.");
			return false;
		}
		info.proceso = "guardarProd";
		//alert(JSON.stringify(info));
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("msjProd").html(response);
				// location.reload();
			}
		});
		$("#dlgFrmProd").dialog("close");
	}
	
	//// NUEVO PRODUCTO ////
	$scope.dlgNvoProd = function(){
		$scope.currentProd = "";
		$("#btnGuardarProd").hide();
		$("#btnGuardarNvoProd").show();
		$("#dlgFrmProd").dialog("open");
	}
	$scope.guardarNvoProd = function (info) {
		if (!info.clave || !info.nombre) {
			alert ("Error!\nDebe capturar la clave y nombre del producto.");
			return false;
		}
		info.proceso = "guardarNvoProd";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjProd").html(response);
			}
		});
	}
	
	//// BORRAR PRODUCTO ////
	$scope.dlgBorrarProd = function(info) {
		$scope.currentProdBorrar = info;
		$("#dlgBorrarProd").dialog("open");
	}
	$scope.borrarProd = function(info) {
		info.proceso = "borrarProd";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#msjProd").html(response);
			}
		});
	}
});

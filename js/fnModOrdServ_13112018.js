/* MODULO ORDEN DE SERVICIO */
$(function() {
	$( "#descargarOrdServ" )
		.button({
			icons : {primary : "ui-icon-circle-arrow-s"}
		})
		.click(function(){
			var folios = "";
			$(':checkbox').each(function() {
				if ( this.checked ) {
					var str = $( this ).attr('class');
					var folio = str.split(" ");
					if (folio[0] != "none") {
						folios += folio[0] + "|";
					}
				}
				
			});
			var seleccionados = folios.split("|");
			if (folios == "" || (seleccionados.length - 1) > 10) {
				alert ("Se debe seleccionar al menos 1 documento y no mas de 10");
			} else {
				window.open(
					"php/lib/sDescargarOrdServ.php?folios=" + folios
					,"Descargar ordenes de servicio"
					,"directories=no,location=no,menubar=no,scrollbars=yes,statusbar=no,tittlebar=no,width=100,height=50"
				);
			}
	});
	
	$( "#btnCambiarStatus" )
		.button({
			icons : {primary : "ui-icon-circle-arrow-s"}
		})
		.click(function(){
			var folios = "";
			$(':checkbox').each(function() {
				if ( this.checked ) {
					var str = $( this ).attr('class');
					var folio = str.split(" ");
					if (folio[0] != "none") {
						folios += folio[0] + "|";
					}
				}
				
			});
			var seleccionados = folios.split("|");
			seleccionados.splice(-1,1);
			if (folios == "") {
				alert ("Se debe seleccionar al menos 1 documento");
			} else {
				$.each(seleccionados, function(index, value){
					value = value.split("-");
					$("#variosFolios").append(value[0] + "-" + value[1] + " ").css("fontWeight","bold");
				});
				$("#cambioStatusVarios").dialog({
					buttons: {
						"Guardar": function(){
							var params = { 
								"proceso" : "cambiarStatusVarios", 
								"folios" : seleccionados,
								"idStatusParaVarios" : $("#idStatusParaVarios").val(),
								"referenciaFactVarios" : $("#referenciaFactVarios").val()
							};
							$.ajax({
								data : params,
								url : "php/lib/fnGenerales.php",
								type : "post",
								success : function (response) {
									$("#frmOrdServ").html(response);
								}
							});
						}
					}
				});
				$("#cambioStatusVarios").dialog("open");
			}
		});
	
	$( "#btnNvaOrdServ" ).button({
		icons: {primary: "ui-icon-document"}
	});
	
	$( "#guardarOrdServ" ).button({
		icons: { primary: "ui-icon-disk" }
	});
	$("#cambioStatus, #dlgBorrarOrdServ, #cambioStatusVarios, #dlgProductoSeleccionado,#dlgBorrarPartida").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	$( "#btnCambiarStatus" ).button({
		icons: {primary: "ui-icon-refresh"}
	});
	
	$( "#btnExpExcel" ).button({
		icons: {primary: "ui-icon-closethick"}
	});
	
	$( "#btnFijarFiltro" )
	.button({
		icons: {primary: "ui-icon-pin-s"}
	})
	.css({
		float : "right",
		marginRight : "10px"
	});
	$( "#btnRemoverFiltro" )
	.button({
		icons: {primary: "ui-icon-pin-w"}
	})
	.css({
		float : "right"
	});
	
	$( ".btnBorrarOrdServ,#btnBorrarPartida" )
	.button({ icons: {primary: "ui-icon-trash" } })
	.css({ float:"right" });
	
	$( ".btnCambiarStatus" )
	.button({ icons: {primary: "ui-icon-disk" } })
	.css({ float:"right" });
	
	$( "#btnAgregarPartida, #btnGuardarPartida" )
	.button({ icons: {primary: "ui-icon-plus" } })
	.css({ float:"right" });
	
	$( "#btnVolver" )
	.button({ icons: {primary: "ui-icon-arrowreturnthick-1-w" } })
	.click(function(){ window.history.back(); });
	
	$( ".calendario" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});
	
	$("#filtro").accordion({
		collapsible: true,
		active: false
    });
});

// var modOrdServ = angular.module('modOrdServ',[]);
mainModule.controller('listaDeOrdenes',function($scope,$http){
	
	var fechaActual = new Date();
	function subtractDays(days) {
		var date = new Date();
		date.setDate(date.getDate() - days);
		return date;
	}
	
	if (document.cookie.indexOf('cveCte') == -1) {
		$.cookie("cveCte","");
		$scope.cveCte = $.cookie("cveCte");
	} else {
		$scope.cveCte = $.cookie("cveCte");
	}
	if (document.cookie.indexOf('idSucursalCte') == -1) {
		$.cookie("idSucursalCte","");
		$scope.idSucursalCte = $.cookie("idSucursalCte");
	} else {
		$scope.idSucursalCte = $.cookie("idSucursalCte");
	}
	if (document.cookie.indexOf('idStatus') == -1) {
		$.cookie("idStatus","");
		$scope.idStatus = $.cookie("idStatus");
	} else {
		$scope.idStatus = $.cookie("idStatus");
	}
	if (document.cookie.indexOf('usuario') == -1) {
		$.cookie("usuario","");
		$scope.idStatus = $.cookie("usuario");
	} else {
		$scope.usuario = $.cookie("usuario");
	}
	if (document.cookie.indexOf('fechaInicial') == -1) {
		var fInicial = subtractDays(90);
		$.cookie("fechaInicial",fInicial.getFullYear() + "-" + (fInicial.getMonth()+1) + "-" + fInicial.getDate());
		$scope.fechaInicial = $.cookie("fechaInicial");
	} else {
		$scope.fechaInicial = $.cookie("fechaInicial");
	}
	if (document.cookie.indexOf('fechaFinal') == -1) {
		$.cookie("fechaFinal",fechaActual.getFullYear() + "-" + (fechaActual.getMonth()+1) + "-" + fechaActual.getDate());
		$scope.fechaFinal = $.cookie("fechaFinal");
	} else {
		$scope.fechaFinal = $.cookie("fechaFinal");
	}
	
	var datos = $.param({
		proceso: "listaDeOrdenes",
		cveCte: $.cookie("cveCte"),
		idSucursalCte: $.cookie("idSucursalCte"),
		usuario: $.cookie("usuario"),
		idStatus: $.cookie("idStatus"),
		fechaInicial: $.cookie("fechaInicial"),
		fechaFinal: $.cookie("fechaFinal")
	});
	
	$http({
		method : "GET",
		url: "php/lib/fnGenerales.php?" + datos
	}).then(function(response){$scope.listaDeOrdenes = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaClientes")
	.then(function(response){$scope.listaClientes = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaOficinas")
	.then(function(response){$scope.listaOficinas = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaOficinas")
	.then(function(response){$scope.listaOficinas = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=tiposStatus")
	.then(function(response){$scope.tiposStatus = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaUsuarios")
	.then(function(response){$scope.listaUsuarios = response.data;});
	
	$scope.btnFijarFiltro = function () {
		$.cookie("cveCte",$scope.cveCte);
		$.cookie("idSucursalCte",$scope.idSucursalCte);
		$.cookie("idStatus",$scope.idStatus);
		$.cookie("usuario",$scope.usuario);
		$.cookie("fechaInicial",$scope.fechaInicial);
		$.cookie("fechaFinal",$scope.fechaFinal);
		location.reload();
	}
	$scope.btnRemoverFiltro = function () {
		$.removeCookie("cveCte");
		$.removeCookie("idSucursalCte");
		$.removeCookie("idStatus");
		$.removeCookie("usuario");
		$.removeCookie("fechaInicial");
		$.removeCookie("fechaFinal");
		location.reload();
	}
	
	$scope.nvaOrdServ = function(info){
		var params = { "proceso" : "nvaOrdServ" };
		$.ajax({
			data : params,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success:  function (response) {
				$("#result").html(response);
			}
		});
		setTimeout(function(){
			url = window.location.href.split("?");
			window.location.assign(url[0] + "?mod=ordServ&folio=0&tipoDocto=1&usuario=" + info), 2000
		})
	}
	
	$scope.editarOrdServ = function (info) {
		url = window.location.href.split("?");
		document.location = url[0] + "?mod=ordServ&folio=" + info.folio + "&tipoDocto=" + info.idTipoDocto + "&usuario=" + info.usuario;
	}
	
	$scope.crearPDFOrdServ = function(info){
		window.open(
			"php/lib/sCreaPDFOrdServ.php?folio=" + info.folio
			,"Imprimir documento"
			,"directories=no,location=no,menubar=no,scrollbars=yes,statusbar=no,tittlebar=no,width=50,height=50"
		);
	}
	
	$scope.imprimirOrdServ = function(info){
		window.open(
			"imprimirDocto.php?folio=" + info.folio
			,"Imprimir documento"
			,"directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800"
		);
	}
	
	$scope.dlgBorrarOrdServ = function(info){
		$scope.ordenABorrar = info;
		$("#dlgBorrarOrdServ").dialog("open");
	}
	
	$scope.borrarOrdServ = function(info){
		info.proceso = "borrarOrdServ";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#frmOrdServ").html(response);
			}
		});
	}
	
	$scope.dlgCambiarStatus = function(info){
		$scope.ordenACambiarStatus = info;
		$("#cambioStatus").dialog("open");
	}
	
	$scope.guardaStatus = function(info){
		info.proceso = "guardaStatus";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#frmOrdServ").html(response);
			}
		});
	}
	
	$scope.expOrdServExcel = function(usr,cte,sucursal,status){
		var usr = usr == undefined ? "" : usr;
		var cte = cte == undefined ? "" : cte;
		var sucursal = sucursal == undefined ? "" : sucursal;
		var status = status == undefined ? "" : status;
		window.open(
			"php/lib/expOrdServExcel.php?usr=" + usr + "&cte=" + cte + "&sucursal=" + sucursal + "&status=" + status
			,"Exporta ordenes a Excel"
			,"directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=50, height=50"
		);
	}
});

mainModule.controller('ordenDeServicio',function($scope,$http){
	var gets = location.search.split("&");
	var folio = gets[1].split("=");
	var idTipoDocto = gets[2].split("=");
	var usuario = gets[3].split("=");
	$scope.folio = folio[1];
	$scope.usuario = usuario[1];
	$scope.idTipoDocto = idTipoDocto[1];
	
	$http.get("php/lib/fnGenerales.php?proceso=currentOrdenDeServ&folio=" + $scope.folio + "&usuario=" + $scope.usuario)
	.then(function(response){$scope.currentOrdenDeServ = response.data[0];});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaClientes")
	.then(function(response){$scope.listaClientes = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaOficinas")
	.then(function(response){$scope.listaOficinas = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=movtosPartidasOrdServ&idTipoDocto=" + idTipoDocto[1] + "&folio=" + folio[1] + "&usuario=" + usuario[1])
	.then(function(response){$scope.partidas = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=tiposDeServ")
	.then(function(response){$scope.tiposDeServicio = response.data;});
	
	$scope.dlgAgregarPartida = function(info) {
		if ($scope.currentOrdenDeServ.idStatus != "1") {
			alert ("Solo se pueden modificar ordenes de servicio con status: \"activo\"");
			return false;
		}
		$http.get("php/lib/fnGenerales.php?proceso=getInfoProd&cveProd=" + info)
		.then(function(response){$scope.partidaActual = response.data[0];});
		$("#btnAgregarPartida").show();
		$("#btnGuardarPartida").hide();
		$("#partidaDescripcion").show();
		$("#partidaDetalle").hide();
		$("#dlgProductoSeleccionado").dialog("open");
	}
	
	$scope.agregaPartida = function(info){
		$.apply();
		info.proceso = "nvaPartidaOrdServ";
		info.folio = $scope.folio;
		info.idTipoDocto = $scope.idTipoDocto;
		info.usuario = $scope.usuario;
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "get",
			success : function (response) {
				$("#frmPartidaOrdServ").html(response);
			}
		});
	}
	$scope.dlgBorrarPartida = function(info) {
		if ($scope.currentOrdenDeServ.idStatus != "1") {
			alert ("Solo se pueden modificar ordenes de servicio con status: \"activo\"");
			return false;
		}
		$scope.borrarPartida = info;
		$("#dlgBorrarPartida").dialog("open");
	}
	$scope.btnBorrarPartida = function(info) {
		info.proceso = "borrarPartida";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#frmPartidaOrdServ").html(response);
			}
		});
	}
	$scope.dlgPartidaSeleccionada = function (info) {
		if ($scope.currentOrdenDeServ.idStatus != "1") {
			alert ("Solo se pueden modificar ordenes de servicio con status: \"activo\"");
			return false;
		}
		$scope.partidaActual = info;
		$("#btnAgregarPartida").hide();
		$("#btnGuardarPartida").show();
		$("#partidaDescripcion").hide();
		$("#partidaDetalle").show();
		$("#dlgProductoSeleccionado").dialog("open");
	}
	$scope.guardarPartida = function(info){
		info.proceso = "guardarPartida";
		info.folio = $scope.folio;
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#frmPartidaOrdServ").html(response);
			}
		});
	}
	$scope.guardarOrdServ = function(info){
		var esNumero = /[1-9]/;
		var formatoFecha = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;
		if (info.cveCte == "empresa.novalid"
			|| info.idSucursalCte == null
			|| info.idSucursalCte == "101"
			|| info.fechaOrdServ == ""
			|| formatoFecha.test(info.fechaOrdServ) == false
			|| esNumero.test(info.tiempoInvertido) == false
			|| info.recibidoPor == ""
			|| info.idTipoDeServ == "0")
		{
			alert ("Error: Los datos ingresados no son correctos.\nFavor de verificar los campos marcados en ROJO.");
			return false;
		}
		info.proceso = "guardarOrdServ";
		$.ajax({
			data : info,
			url : "php/lib/fnGenerales.php",
			type : "post",
			success : function (response) {
				$("#frmPartidaOrdServ").html(response);
			}
		});
	}
});

mainModule.factory("AutoCompleteService", ["$http", function ($http) {
    return {
        search: function (term) {
            return $http.get("php/lib/fnGenerales.php?proceso=getInfoProdBuscado&cadena=" + term).then(function (response) {
                return response.data;
            });
        }
    };
}]);

mainModule.directive("autocomplete", ["AutoCompleteService", function (AutoCompleteService) {
    return {
        restrict: "A",
        link: function (scope, elem, attr, ctrl) {
            elem.autocomplete({
                source: function (searchTerm, response) {
                    AutoCompleteService.search(searchTerm.term).then(function (autocompleteResults) {
                        response($.map(autocompleteResults, function (autocompleteResult) {
                            return {
                                label: autocompleteResult.nombre + " ( " + autocompleteResult.clave + " ) ",
                                value: autocompleteResult.clave
                            }
                        }))
                    });
                },
                minLength: 1,
                select: function (event, selectedItem) {
                    // Do something with the selected item, e.g.
                    scope.cveProducto = selectedItem.item.value;
                    scope.$apply();
                    event.preventDefault();
                }
            });
        }
    };
}]);


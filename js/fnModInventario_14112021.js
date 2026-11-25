$(function() {	
	$("#dlgFrmDisp, #dlgBorrarDisp, #dlgEditAV").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	
	$("#btnGuardarNvoDisp, #btnGuardarDisp, #btnActLicAV")
	.button({ icons: { primary: "ui-icon-disk" } })
	.css("float","right");
	
	/* $("#btnNvoDisp")
	.button({ icons: { primary: "ui-icon-plus" } }); */
	
	$("#btnGuardarNvoDisp, #btnGuardarDisp")
	.button({ icons: { primary: "ui-icon-clipboard" } });
	
	$("#confirmaBorrar")
	.button({ icons: { primary: "ui-icon-trash" } })
	.css({ float:"right" });
	
	$("#capaBtnImpInvCte").click(function(){
		window.print();
	});
});

mainModule.controller('catInventario',function($scope,$http){
	
	var gets = location.search.split("&");
	var idCte = gets[1].split("=");
	
	$http.get("php/lib/fnGenerales.php?proceso=listaDispositivos&id=" + idCte[1])
	.then(function(response){$scope.listaDispositivos = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=getSuCteXidCte&idCte=" + idCte[1])
	.then(function(response){$scope.listaOficinas = response.data;});
	
	/* $http.get("php/lib/fnGenerales.php?proceso=listaLicenciaSoftware")
	.then(function(response){$scope.listaLicenciaSoftware = response.data;}); */
	
	$http.get("php/lib/fnGenerales.php?proceso=listaDeAV")
	.then(function(response){$scope.listaDeAV = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaDeOffice")
	.then(function(response){$scope.listaDeOffice = response.data;});
	
	$scope.licAsignAInventario = [];
	
	$("#btnNvoDisp")
	.button({ icons: { primary: "ui-icon-plus" } });
	
	$scope.dlgInfoDisp = function(info){
		$scope.currentDisp = info;
		$("#btnGuardarNvoDisp").hide();
		$("#btnGuardarDisp").show();
		$("#dlgFrmDisp").dialog("open");
	};
	
	$scope.dlgNvoDisp = function() {
		alert ("Advertencia!. \nPor ahora solo se pueden agregar computadoras al inventario \natraves de la aplicacion de escritorio");
	}
	
	$scope.guardarNvoDisp = function (info){
		alert ("Advertencia!. \nPor ahora solo se pueden agregar computadoras al inventario \natraves de la aplicacion de escritorio");
	}
	
	$scope.guardarDisp = function (info){
		// alert ("Error: \nLa funcion de guardar cambios aun no se ha implementado.");
		info.idLicOffice = $("#idLicOffice").val();
		info.idLicAV = $("#idLicAV").val();
		info.idSucursalCte = $("#idSucursalCte").val();
		info.proceso = "guardarDisp";
		$.ajax({
			data	: info,
			url		: "php/lib/fnGenerales.php",
			type	: "post",
			beforeSend: function() {
				$("#msjInv").html("Cargando, por favor espere . . .");
			},
			success:  function (response) {
				$("#msjInv").html(response);
			}
		});
		$("#dlgFrmDisp").dialog("close");
	}
	
	$scope.dlgBorrarDisp = function(info) {
		$scope.currentDispBorrar = info;
		$("#dlgBorrarDisp").dialog("open");
	}
	
	$scope.borrarDisp = function (info){
		var parametros = info;
		parametros.proceso = "borrarDisp";
		$.ajax({
			data	: parametros,
			url		: "php/lib/fnGenerales.php",
			type	: "post",
			beforeSend: function() {
				$("#msjInv").html("Cargando, por favor espere . . .");
			},
			success:  function (response) {
				$("#msjInv").html(response);
			}
		});
	}
	
	$scope.dlgAV = function(idAV) {
		
		$http.get("php/lib/fnGenerales.php?proceso=infoLicencia&idAV=" + idAV)
		.then(function(response){$scope.infoLicencia = response.data;});
		
		$("#dlgEditAV").dialog("open");
	}
	
	$scope.guardarCambiosAV = function(obj){
		$http.get("php/lib/fnGenerales.php?proceso=guardarCambiosAV&observaciones=" + obj.observaciones + "&fechacaducidad=" + obj.fechacaducidad + "&idlicencia=" + obj.idlicencia);
		$("#dlgEditAV").dialog("close");
		location.reload();
	}
	
	$scope.btnAddLic = function(idLic, infoDisp){
		var agregado = {"idLic" : idLic, "idInv" : infoDisp.idInventario};
		$scope.licAsignAInventario.push({"idLic" : idLic, "idInv" : infoDisp.idInventario});
	};
	
	$scope.borrarLicAsign = function(info){
		console.log($scope.licAsignAInventario);
		$.each($scope.licAsignAInventario, function(i,val){
			if(val.idLic == info.idLic)
			{
				delete $scope.licAsignAInventario[i];
				$("#" + val.idLic).remove();
			}
		});
		console.log($scope.licAsignAInventario);
	}
});


/* , "idInv" : infoDisp.idInventario});
	};
	
	$scope.borrarLicAsign = function(info){
		console.log($scope.licAsignAInventario);
		$.each($scope.licAsignAInventario, function(i,val){
			if(val.idLic == info.idLic)
			{
				delete $scope.licAsignAInventario[i];
				$("#" + val.idLic).remove();
			}
		});
		console.log($scope.licAsignAInventario);
	}
}); */


/* l.idLic).remove();
			}
		});
		console.log($scope.licAsignAInventario);
	} */
	
	/***** DISPOSITIVOS PERIFERICOS *****/
/* 	$scope.dlgNvoDisp = function() {
		$scope.currentPeriferico = "";
		$("#btnGuardarNvoPeriferico").show();
		$("#btnGuardarPeriferico").hide();
		$("#dlgFrmPeriferico").dialog("open");
	}
	
	$scope.guardarNvoPeriferico = function(info) {
		info.idCliente = idCte[1];
		info.proceso = "guardarNvoPeriferico";
		
		$.ajax({
			data: info,
			url		: "php/lib/fnGenerales.php",
			type	: "post",
			beforeSend: function() {
				$("#msjInv").html("Cargando, por favor espere . . .");
			},
			success:  function (response) {
				$("#msjInv").html(response);
			}
		});
	}
	
	$scope.dlgInfoPeriferico = function(info) {
		$scope.currentPeriferico = info;
		$("#btnGuardarNvoPeriferico").hide();
		$("#btnGuardarPeriferico").show();
		$("#dlgFrmPeriferico").dialog("open");
	}
	
	$scope.guardarPeriferico = function(info) {
		info.proceso = "guardarPeriferico";
		
		$.ajax({
			data: info,
			url		: "php/lib/fnGenerales.php",
			type	: "post",
			beforeSend: function() {
				$("#msjInv").html("Cargando, por favor espere . . .");
			},
			success:  function (response) {
				$("#msjInv").html(response);
			}
		});
	}
	
	$scope.dlgBorrarPeriferico = function(info) {
		$scope.currentPerifericoBorrar = info;
		$("#dlgBorrarPeriferico").dialog("open");
	}
	
	$scope.borrarPeriferico = function(info){
		info.proceso = "borrarPeriferico";
		
		$.ajax({
			data: info,
			url		: "php/lib/fnGenerales.php",
			type	: "post",
			beforeSend: function() {
				$("#msjInv").html("Cargando, por favor espere . . .");
			},
			success:  function (response) {
				$("#msjInv").html(response);
			}
		});
	}
});
 */
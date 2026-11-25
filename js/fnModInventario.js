$(function() {	
	$("#dlgFrmDisp, #dlgBorrarDisp, #dlgEditAV, #dlgBorrarPeriferico, #dlgInfoPeriferico, #dlgFrmPeriferico").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	
	$( "#CompusDisp" ).tabs();
	
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
	
	$("#fechaActReg").datepicker({ dateFormat: 'yy-mm-dd' });
});

mainModule.controller('catInventario',function($scope,$http){
	
	var gets = location.search.split("&");
	var idCte = gets[1].split("=");
	
	$http.get("php/lib/fnGenerales.php?proceso=listaDispositivos&id=" + idCte[1])
	.then(function(response){$scope.listaDispositivos = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaPerifericos&idCte=" + idCte[1])
	.then(function(response){$scope.listaPerifericos = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=getSuCteXidCte&idCte=" + idCte[1])
	.then(function(response){$scope.listaOficinas = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaTipoDispositivo")
	.then(function(response){$scope.listaTipoDispositivo = response.data;});
	
	/* $http.get("php/lib/fnGenerales.php?proceso=listaLicenciaSoftware")
	.then(function(response){$scope.listaLicenciaSoftware = response.data;}); */
	
	$http.get("php/lib/fnGenerales.php?proceso=listaDeAV")
	.then(function(response){$scope.listaDeAV = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=listaDeOffice")
	.then(function(response){$scope.listaDeOffice = response.data;});
	
	$scope.licAsignAInventario = [];
	
	$("#btnNvoDisp")
	.button({ icons: { primary: "ui-icon-plus" } });

	$("#btnComoObtenerSerie")
	.button({ icons: { primary: " ui-icon-help" } })
	.css({ "padding": ".2em" });

	$scope.comoObtenerSerie = function(){
		alert("powershell: \nGet-WmiObject win32_bios | select Serialnumber")
	};

	$scope.ayudaObtenerSerie = function() {
		var textoComando = "Get-WmiObject Win32_Bios | Select SerialNumber";
		navigator.clipboard.writeText(textoComando)
      		.then(() => console.log('Copied: ' + textoComando))
      		.catch(err => console.error('Error copying text: ', err));
	}
	
	$scope.dlgInfoDisp = function(info){
		for(i=0; i<info.licencias[0].length; i++){
			if (info.licencias[0][i].idTipoLicencia == 1) {
				$scope.licAV = info.licencias[0][i].idLicencia;
			} else {
				$scope.licOffice = info.licencias[0][i].idLicencia;
			}
		}
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
		data = 
		{
			idInventario: info.idInventario,
			idLicOffice: $("#idLicOffice").val().match(/^\?\s.+:\d+\s\?$/) ? 3 : $("#idLicOffice").val(),
			idLicAV: $("#idLicAV").val().match(/^\?\s.+:\d+\s\?$/) ? 2 : $("#idLicAV").val(),
			idSucursalCte: $("#idSucursalCte").val(),
			usuario: $("#usuario").val(),
			departamento: $("#departamento").val(),
			Observaciones: info.Observaciones,
			serie: info.serie,
			fecharegistro: info.fecharegistro,
			proceso: "guardarDisp"
		}
		// VALIDACIONES
		//alert(JSON.stringify(data));
		if
		(
			(data.usuario.match(/^\?\s.+:\d+\s\?$/) || data.usuario == "") ||
			(data.departamento.match(/^\?\s.+:\d+\s\?$/) || data.departamento == "") ||
			(data.serie.match(/^\?\s.+:\d+\s\?$/) || data.serie == "" || data.serie == null)
		)
		{
			alert("Error: Debe ingresar los datos macados con (*).")
			return false;
		}

		$.ajax({
			data	: data,
			url		: "php/lib/fnGenerales.php",
			type	: "post",
			beforeSend: function() {
				$("#msjInv").html("Cargando, por favor espere . . .");
			},
			success:  function (response) {
				$("#msjInv").html(response);
			}
		});
		//$("#dlgFrmDisp").dialog("close");
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
	
	/***** DISPOSITIVOS PERIFERICOS *****/
	$scope.dlgNvoDisp = function() {
		$scope.currentPeriferico = "";
		$("#btnGuardarNvoPeriferico").show();
		$("#btnGuardarPeriferico").hide();
		$("#dlgFrmPeriferico").dialog("open");
	}
	
	$scope.guardarNvoPeriferico = function(info) {
		info.idCliente = idCte[1];
		info.proceso = "guardarNvoPeriferico";
		
		if
		(
			(typeof info.idSucursalCte == "undefined" || info.idSucursalCte == "") ||
			(typeof info.idTipoDisp == "undefined" || info.idTipoDisp == "") ||
			(typeof info.nombre == "undefined" || info.nombre == "") ||
			(typeof info.marca == "undefined" || info.marca == "") ||
			(typeof info.modelo == "undefined" || info.modelo == "") ||
			(typeof info.fechaRegistro == "undefined" || info.fechaRegistro == "")
		)
		{
			alert ("Error: Favor de capturar los datos marcados con *."); 
			return false;
		}

		//console.log(JSON.stringify(info));

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
		//alert(JSON.stringify(info));
		
		$.ajax({
			data: info,
			url	: "php/lib/fnGenerales.php",
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
	} 
	
	
}); */

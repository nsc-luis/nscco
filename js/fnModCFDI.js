$(function() {	
	/******* NUEVOS ELEMENTOS *******/
	$("#accoFiltroCargaXml").accordion({
		collapsible: true,
		active: false
	});
	
	$( "#btnCargaXML2" )
	.button({ icons: {primary: "ui-icon-document"} })
	.css("float","right")
	.click(function(){
		var data = new FormData();
		var msj = "";
		jQuery.each($('#archivoXML')[0].files, function(i, file) {
			if (file.type == "text/xml") { data.append('file-'+i, file); }
			else { msj += "El archivo: " + file.name + " no es un documento XML\n"; }
		});
		if (msj != "") { alert ("Los siguientes documentos no seran procesados:\n" + msj); }
		
		$.ajax({
			data:  data,
			url:   'php/lib/sNvoRegistroCFDI_1.0.php',
			type:  'post',
			cache: false,
			processData: false,		// tell jQuery not to process the data
			contentType: false,		// tell jQuery not to set contentType
			beforeSend: function () {
				$("#result").html("Cargando, por favor espere . . .");
			},
			success:  function (response) {
				$("#result").html(response);
			}
		});
	});
	
	$( "#btnFiltroCFDI2" )
	.button({ icons: {primary: "ui-icon-pin-s"} })
	.css({
		float:"right",
		marginRight: "10px"
	});
	
	$( "#btnValidarCFDIs" )
	.button({ icons: {primary: "ui-icon ui-icon-check"} })
	.css({
		float:"right",
		marginRight: "10px"
	});
	
	$( "#btnExpExcelCFDI2" )
	.button({ icons: {primary: "ui-icon-close"} })
	.css("float","right");
	
	$( "#btnGuardarPolizaOCPDF" )
	.button({ icons: {primary: "ui-icon-disk"} })
	.css("float","right");
	
	$("#dlgPolizaOCPDF").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	$("#modalValidaCFDI").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		},
		closeOnEscape: false
	});
	$("#modalValidaCFDI").parent().find(".ui-dialog-titlebar-close").hide()
});

jQuery.extend({
	getQueryParameters : function(str) {
		return (str || document.location.search).replace(/(^\?)/,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
	}
});

mainModule.controller('modCFDI',function($scope,$http){
	
	var paramsGET = $.getQueryParameters();
	
	if (paramsGET.filtro == "default") {
		$http.get("php/lib/fnGenerales.php?proceso=cfdis&filtro=" + paramsGET.filtro + "&fecha=" + paramsGET.fecha)
		.then(function(response){$scope.cfdis = response.data;});
	} else {
		delete paramsGET.mod;
		$http.get("php/lib/fnGenerales.php?proceso=cfdis&" + $.param(paramsGET))
		.then(function(response){$scope.cfdis = response.data;});
	}
	
	$http.get("php/lib/fnGenerales.php?proceso=yearsList")
	.then(function(response){$scope.yearsList = response.data;});
	
	$scope.filtrarCFDIs = function(){
		if ($scope.year == undefined) {
			alert ("Error: Debes seleccionar un año.");
			return false;
		}
		if ($scope.mes == undefined) {
			alert ("Error: Debes seleccionar un mes ó TODOS.");
			return false;
		}
		var fecha = $scope.mes == "todos" ? $scope.year : $scope.year + "-" + $scope.mes;
		var url = window.location.origin.toString();
		// window.location = url + '/factProveedor/system.php?mod=verCFDIs&filtro=default&fecha=' + fecha;
		window.location = url + '/system.php?mod=verCFDIs&filtro=default&fecha=' + fecha;
	}
	
	$scope.representacionCFDI = function(info){
		switch (info.versionXML) {
			case "3.2":
				switch (info.tipoDeDoctoXML) {
					case "ingreso":
						window.open("PlantillaIngreso3.2.php?uuid=" + info.uuid, ":: Representacion CFDI (NO VALIDA) ::", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800");
					break;
					case "nomina":
					break;
					default:
						alert ("Documento no identificado 3.2.");
					break;
				}
			break;
			case "3.3":
				switch (info.tipoDeDoctoXML) {
					case "I":
						// alert ("Plantilla 3.3 para Ingreso.");
						window.open("PlantillaIngreso3.3.php?uuid=" + info.uuid, ":: Representacion CFDI (NO VALIDA) ::", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800");
					break;
					case "E":
						// alert ("Plantilla 3.3 para Egreso.");
						window.open("PlantillaEgreso3.3.php?uuid=" + info.uuid, ":: Representacion CFDI (NO VALIDA) ::", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800");
					break;
					case "N":
						// alert ("Plantilla 3.3 para Nomina.");
						window.open("PlantillaNomina1.2.php?uuid=" + info.uuid, ":: Representacion CFDI (NO VALIDA) ::", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800");
					break;
					case "P":
						// alert ("Plantilla para REP1.0");
						window.open("PlantillaREP1.0.php?uuid=" + info.uuid, ":: Representacion CFDI (NO VALIDA) ::", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800");
					break;
					default:
						alert ("Documento no identificado 3.3.");
					break;
				}
			break;
			default:
				alert ("Documento no identificado.");
			break;
		}

	}
	
	$scope.archivoXML = function(info) {
		window.open(
			"php/lib/sDownloadXML.php?uuid=" + info.uuid
			,"Descargar xml."
			,"directories=no,location=no,menubar=no,scrollbars=yes,statusbar=no,tittlebar=no,width=50,height=50"
		);
	}
	$scope.archivoPDF = function(info) {
		if (info.mimeArchivoPDF != "application/pdf") {
			alert ("***Alerta***\nAun no se ha cargado un archivo PDF para este CFDI.\nPuedes hacerlo desde el boton \"Cargar datos extra\"");
			return false;
		}
		window.open(
			"php/lib/sDownloadPDF.php?uuid=" + info.uuid
			,"Descargar PDF."
			,"directories=no,location=no,menubar=no,scrollbars=yes,statusbar=no,tittlebar=no,width=50,height=50"
		);
	}
	
	$scope.exportaListaCFDIExcel = function() {
		var rfcProvFiltro = $scope.rfcProveedor;
		var fecha = $scope.year + "-" + $scope.mes;
		var fTipoDocto = "4";
		var filtro = "rfcProvFiltro=" + rfcProvFiltro + "&fecha=" + fecha + "&fTipoDocto=" + fTipoDocto;
		location.href = "./listadoCFDIExcel.php?"+filtro;
	}
	
	$scope.dlgPolizaOCPDF = function (info) {
		$scope.currentCFDI = info;
		$("#dlgPolizaOCPDF").dialog("open");
	}
	
	$scope.guardarPolizaOCPDF = function (info) {
		info.proceso = "guardarPolizaOrdenCompra";
		$.ajax({
			data	: info,
			url		: "php/lib/fnGenerales.php",
			type	: "post",
			beforeSend: function(){
				$("#result").html("Procesando, por favor espere . . .");
			},
			success: function(response){
				$("#result").html(response);
			}
		});
		var doctoPDF = $('#archivoPDF')[0].files[0];
		if (doctoPDF != undefined) {
			if (doctoPDF.type == "application/pdf") {
				var data = new FormData();
				
				data.append("proceso", "guardarPDF");
				data.append("archivoPDF", doctoPDF);
				data.append("idXML", info.idXML);
				
				$.ajax({
					data:  data,
					url:   'php/lib/fnGenerales.php',
					type:  'post',
					cache: false,
					processData: false,		// tell jQuery not to process the data
					contentType: false,		// tell jQuery not to set contentType
					beforeSend: function () {
						$("#resutlGuardarPolizaOCPDF")
						.css({
							"color" : "red"
							,"font-weight" : "bold"
							,"font-size" : "16px"
							,"width" : "100%"
							,"text-align" : "center"
						}).html("Procesando, por favor espere . . .");
						
					},
					success:  function (response) {
						$("#result").html(response);
						location.reload();
					}
				})
			} else {
				alert ("Debe seleccionar un archivo PDF.");
				return false;
			}
		} else {
			$("#dlgPolizaOCPDF").dialog("close");
		}
	}
	
	$scope.validaCFDI = function(info) {
		info.proceso = "validaCFDI";
		$.ajax({
			data	: info,
			url		: "php/lib/fnGenerales.php",
			type	: "post",
			beforeSend: function(){
				$("#modalValidaCFDI")
				.css({
					"color" : "red"
					,"font-weight" : "bold"
					,"font-size" : "16px"
					,"width" : "100%"
					,"text-align" : "center"
				}).html("Procesando validacion, por favor espere . . .");
				info.estadoValidaCFDI = "Procesando...";
				$("#modalValidaCFDI").dialog("open");
			},
			success: function(response){
				$("#modalValidaCFDI").dialog("close");
				info.estadoValidaCFDI = response;
				// location.reload();
			}
		});
	}
});
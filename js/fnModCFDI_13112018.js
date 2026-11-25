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
			url:   'php/lib/sNvoRegistroCFDI.php',
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
	
	$( "#btnExpExcelCFDI2" )
	.button({ icons: {primary: "ui-icon-close"} })
	.css("float","right");
	
});

mainModule.controller('modCFDI',function($scope,$http){
	
	var gets = location.search.split("&");
	var rfcProveedor = gets[1].split("=");
	var getFecha = gets[2].split("=");
	var YearMonth = getFecha[1].split("-");
	$scope.rfcProveedor = rfcProveedor[1];
	$scope.year = YearMonth[0];
	if (YearMonth[1] == undefined) {
		$scope.mes = "todos";
		var fecha = YearMonth[0];
	} else {
		$scope.mes = YearMonth[1];
		var fecha = YearMonth[0] + "-" + YearMonth[1];
	}
	
	$http.get("php/lib/fnGenerales.php?proceso=cfdis&rfcProveedor=" + $scope.rfcProveedor + "&fecha=" + fecha + "&tipoDocto=4")
	.then(function(response){$scope.cfdis = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=proveedores")
	.then(function(response){$scope.proveedores = response.data;});
	
	$http.get("php/lib/fnGenerales.php?proceso=yearsList")
	.then(function(response){$scope.yearsList = response.data;});
	
	$scope.filtrarCFDIs = function(){
		if ($scope.year == undefined) {
			alert ("Error: Debes seleccionar un año.");
			return false;
		}
		if ($scope.rfcProveedor == undefined) {
			alert ("Error: Debes seleccionar un proveedor ó TODOS.");
			return false;
		}
		if ($scope.mes == undefined) {
			alert ("Error: Debes seleccionar un mes ó TODOS.");
			return false;
		}
		var fecha = $scope.mes == "todos" ? $scope.year : $scope.year + "-" + $scope.mes;
		var url = window.location.origin.toString();
		window.location = url + '/system.php?mod=verCFDIs&rfcProveedor=' + $scope.rfcProveedor + '&fecha=' + fecha + '&tipoDocto=4';
	}
	
	$scope.representacionCFDI = function(info){
		window.open("representacionCFDI.php?uuid=" + info.uuid, ":: Representacion CFDI (NO VALIDA) ::", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800");
	}
	
	$scope.archivoXML = function(info) {
		window.open(
			"php/lib/sDownloadXML.php?uuid=" + info.uuid
			,"Descargar xml."
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
	
});
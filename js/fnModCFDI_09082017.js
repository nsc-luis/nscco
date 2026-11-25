$(function() {
	$( "#frmCargaXML" ).dialog({
		autoOpen: false,
		width: 450,
		position: { my:"center", at: "top"},
		modal: true
	});
	$( ".borrarFiltro" ).button({
		icons: {primary: "ui-icon-refresh"}
	});
	$( "#btnImprimirRepresentacionCFDI" )
		.button({
			icons : { primary : "ui-icon-print" }
		})
		.click(function(){
			window.print();
	});
	$( ".representacionCFDI" ).click(function(){
		var nomClase = $( this ).attr('class');
		var elementos = nomClase.split(' ');
		var uuid = elementos[0];
		window.open("representacionCFDI.php?uuid=" + uuid, ":: Representacion CFDI (NO VALIDA) ::", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1024, height=800");
	});
	$( ".archivoXML" )
		.button({
			icons: {primary: "ui-icon-extlink"},
			text: false
		})
		.click(function(){
			var nomClase = $( this ).attr('class');
			var elementos = nomClase.split(' ');
			parametros = { "uuid" : elementos[0] }
			$.ajax({
				data:  parametros,
				url:   'php/lib/sDownloadXML.php',
				type:  'get',
				beforeSend: function () {
					$("#result").html("Cargando, por favor espere . . .");
				},
				success:  function (response) {
					$("#result").html(response);
				}
			});
		});
	$( "#btnCargaXML" ).button({
		icons: {primary: "ui-icon-document"}
	});
	$( "#btnCargaXML" ).click(function(){
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
	$( "#filtroCFDI" )
		.button({
			icons: {primary: "ui-icon-search"}
		})
		.click(function(){
			aplicarFiltro();
		});
	$( "#btnExpExcelCFDI" )
		.button({
			icons: {primary: "ui-icon-arrowthickstop-1-s"}
		})
		.click(function(){
			exportaListaCFDIExcel();
		});
	$( ".calendario" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});
});
function aplicarFiltro(){
	var rfcProvFiltro = $("#rfcProvFiltro").val();
	if ($("#fMes").val() == "todos") {
		var fecha = $("#fYear").val() + "-";
	} else {
		var fecha = $("#fYear").val() + "-" + $("#fMes").val();
	}
	var fUUID = $("#fUUID").val();
	var fTipoDocto = $("#fTipoDocto").val();
	
	var filtro = "rfcProvFiltro=" + rfcProvFiltro + "&fecha=" + fecha + "&fUUID=" + fUUID + "&fTipoDocto=" + fTipoDocto;
	location.href = "./system.php?mod=verCFDIs&"+filtro;
}
function exportaListaCFDIExcel(){
	var rfcProvFiltro = $("#rfcProvFiltro").val();
	var fecha = $("#fYear").val() + "-" + $("#fMes").val();
	var fUUID = $("#fUUID").val();
	var fTipoDocto = $("#fTipoDocto").val();
	
	var filtro = "rfcProvFiltro=" + rfcProvFiltro + "&fecha=" + fecha + "&fUUID=" + fUUID + "&fTipoDocto=" + fTipoDocto;
	location.href = "./listadoCFDIExcel.php?"+filtro;
}
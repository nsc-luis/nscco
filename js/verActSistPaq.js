$(function() {
	$( "#btnCargaRar" )
	.button({ icons: {primary: "ui-icon-document"} })
	.css("float","right")
	.click(function(){
		alert ("hi!");
		var data = new FormData();
		var msj = "";
		jQuery.each($('#archivoRar')[0].files, function(i, file) {
			if (file.type == "application/x-rar-compressed") { data.append('file-'+i, file); }
			else { msj += "El archivo: " + file.name + " no es un archivo RAR\n"; }
		});
		if (msj != "") { alert ("Los siguientes documentos no seran procesados:\n" + msj); }
	});
});
mainModule.controller('verActSistPaq',function($scope,$http){
	$scope.cargarRar = function(){
		alert ("hi!");
	};
	
});
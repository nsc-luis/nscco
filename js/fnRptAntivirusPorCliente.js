$(function() {
	$("#btnExpAntivirusPorCliente")
	.button({icons: { primary: "ui-icon-closethick" }})
	.css({ float:"right" });

	$("#btnImprimir")
	.button({icons: { primary: "ui-icon-print" }})
	.css({ float:"right" });
});

var mainModule = angular.module('mainModuleExpAntivirusPorCliente',[]);
mainModule.controller('exportaCaducidadPorCliente',function($scope,$http){
	
	$scope.expAntivirusPorCliente = function(idCte){
		var idCte = idCte == undefined ? "" : idCte;
		
		//alert(idCte);
		window.open(
			"php/lib/expAntivirusPorCliente.php?idCte=" + idCte 
			,"Exporta estado de antivirus por cliente"
			,"directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=50, height=50"
		);
	}

});

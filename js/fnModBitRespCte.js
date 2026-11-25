$(function() {
	$("#dlgDetalleResp").dialog({
		height: "auto",
		width: "auto",
		modal: true,
		autoOpen: false,
		position: {
			my: "center",
			at: "top"
		}
	});
	$("#btnImprimirBitCte").click(function(){
		window.print();
	});
});

mainModule.controller('bitRespCte',function($scope,$http){
	
	var gets = location.search.split("=");
	$scope.cliente = gets[2];
	
	$http.get("php/lib/fnGenerales.php?proceso=bitRespCtes&nombreCte=" + $scope.cliente)
	.then(function(response){$scope.bitRespCtes = response.data;});
	
	$scope.detalleBitResp = function (info) {
		$scope.currentRegistro = info;
		$("#dlgDetalleResp").dialog("open");
	}
});

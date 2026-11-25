$(function() {
	$( "#btnImprimirRepresentacionCFDI" )
		.button({
			icons : { primary : "ui-icon-print" }
		})
		.click(function(){
			window.print();
	});
});
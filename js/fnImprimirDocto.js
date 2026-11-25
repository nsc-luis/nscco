$(function() {
	$( "#btnImprimirDocto" ).button({
		icons : { primary : "ui-icon-print" }
	});
	$( "#btnImprimirDocto" ).click(function(){
		window.print();
	});

});

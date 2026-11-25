$(function() {
	/* BOTONES */
	$( "#btnNvoRespaldo" )
	.button()
	.click(function(){
		$( "#frmBackup" ).dialog({ 
			buttons: {
				Procesar: function() {
					validar();
					if ( valido == 1 ) {
						parametros = { "nomBackup" : $("#nomBackup").val() }
						$.ajax({
							data	:  parametros,
							url		: "php/lib/sNvoRespaldo.php",
							type	: "post",
							beforeSend: function() {
								$("#result").html("Cargando, por favor espere . . .");
							},
							success:  function (response) {
								$("#result").html(response);
							}
						});
						$( "#frmBackup" ).dialog( "close" );
					}
				}
			}
		});
		$( "#frmBackup" ).dialog("open");
	});
	$( ".btnRestauraBkp" )
	.button({
		icons: {primary: "ui-icon-wrench"},
		text: false
	})
	.click(function(){
		var nomClase = $( this ).attr('class');
		var elementos = nomClase.split(' ');
		alert ("No se ha liberado el proceso para respaurar el respaldo: \r\n\n" + elementos[0]);
	});
		
	/* CAPAS DE DIALOGO */
	$( "#frmBackup" ).dialog({ 
		autoOpen: false,
		width: 550,
		position: { my:"center", at: "top"},
		modal: true
	});
});
<?php
	/**
	 * Archivo de configuración para nuestra aplicación modularizada.
	 * Definimos valores por defecto y datos para cada uno de nuestros módulos.
	 */
	define('MODULO_DEFECTO', 'frase');
	define('LAYOUT_DEFECTO', 'default.php');
	define('MODULO_PATH', realpath('./php/modulos/'));
	define('LAYOUT_PATH', realpath('./php/layouts/'));
	
	$conf['frase'] = array(
		'archivo' => 'fraseDelDia.php',
		'layout' => LAYOUT_DEFECTO
	);
	$conf['empresas'] = array (
		'archivo' => 'modEmp.php'
	);
	$conf['usuarios'] = array (
		'archivo' => 'modUsr.php'
	);
	$conf['clientes'] = array (
		'archivo' => 'modCte.php'
	);
	$conf['respaldar'] = array (
		'archivo' => 'modBackup.php'
	);
	$conf['productos'] = array (
		'archivo' => 'modProd.php'
	);
	$conf['unidades'] = array (
		'archivo' => 'modUnidad.php'
	);
	$conf['impuestos'] = array (
		'archivo' => 'modImpuesto.php'
	);
	$conf['ordenDeServicio'] = array (
		'archivo' => 'modOrdServ.php'
	);
	$conf['ordServ'] = array (
		'archivo' => 'frmOrdServ.php'
	);
	$conf['cotizaciones'] = array (
		'archivo' => 'modCotiza.php'
	);
	$conf['tareas'] = array (
		'archivo' => 'modTareas.php'
	);
	$conf['reporteOrdenServicio'] = array (
		'archivo' => 'modRepOrdSrv.php'
	);
	$conf['reporteCotizacion'] = array (
		'archivo' => 'modRepCotiza.php'
	);
	$conf['verCFDIs'] = array (
		'archivo' => 'modCFDI.php'
	);
	$conf['cargarCFDI'] = array (
		'archivo' => 'modCargarXML.php'
	);
	$conf['bitacoraDeRespaldosCte'] = array (
		'archivo' => 'modBitacoraRespCte.php'
	);
	$conf['inventarioCte'] = array (
		'archivo' => 'modInventarioCte.php'
	);
	$conf['verActPaq'] = array (
		'archivo' => 'currentVersionPaqueteria.php'
	);
	$conf['tipoLicencia'] = array (
		'archivo' => 'modTipoLicencia.php'
	);
	$conf['licenciaDeSoftware'] = array (
		'archivo' => 'modLicenciaSoftware.php'
	);
	$conf['tipoDispositivo'] = array (
		'archivo' => 'modTipoDispositivo.php'
	);
	/*$conf['imp_art'] = array(
		   'archivo' => $conf['articulo']['archivo'],
		   'layout' => 'imprimir.php'
	);*/
?>
<?php
	include_once ("cMovtosPartidas.php");
	$movtos = new MovtosPartidas();
	$movtos->bd->setNombreBD('pagj690609hv7');
			
	$sql = "SELECT m.idMovto,m.cveProd,p.idUnidad
		FROM movimientos m
		JOIN productos p ON clave = cveProd";
	$query = $movtos->bd->consulta($sql);
	$data = array();
	while ($row = mysql_fetch_array($query)){
		$data[] = $row;
	}
	// echo "<pre>"; print_r($data); echo "</pre>";
	foreach ($data as $dato) {
		$sql = "UPDATE movimientos SET idUnidad = '$dato[idUnidad]' WHERE idMovto = '$dato[idMovto]'";
		$movtos->bd->consulta($sql);
		$sql = "SELECT idUnidad FROM movimientos WHERE idMovto = '$dato[idMovto]'";
		$query = $movtos->bd->consulta($sql);
		$idUnidad = mysql_fetch_assoc($query);
		echo "El Id del movto es: " . $dato['idMovto'] . " el producto de este movto es: " . $dato['cveProd'] . " y su unidad de medida es: " . $idUnidad['idUnidad'] . "<br />";
	}
?>
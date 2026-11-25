<?php
/* function valida_en_sat() {
    global $data;
    error_reporting(E_ALL);
    $url = "https://consultaqr.facturaelectronica.sat.gob.mx/consultacfdiservice.svc?wsdl";
    $opt = array("http"=>array("timeout"=>1));
    $context = stream_context_create($opt);
    $options=array('trace'=>true,
                   'stream_context'=>$context
                    );
    $rfc_emisor = htmlentities(utf8_encode($data['rfc']));
    $rfc_receptor = htmlentities(utf8_encode($data['rfc_receptor']));
    $impo = (double)$data['total'];
    $impo=sprintf("%.6f", $impo);
    $impo = str_pad($impo,17,"0",STR_PAD_LEFT);
    $uuid = strtoupper($data['uuid']);
    $factura = "?re=$rfc_emisor&rr=$rfc_receptor&tt=$impo&id=$uuid";
    echo "<h3>$factura</h3>";
    $prm = array('expresionImpresa'=>$factura);

    try {
        $soapclient = new SoapClient($url,$options);
        $buscar=$soapclient->Consulta($prm);
        echo "<h3>El portal del SAT reporta</h3>";
        // echo "<pre>";
        // echo htmlspecialchars($soapclient->__getLastRequestHeaders()."\n".$soapclient->__getLastRequest()."\n");
        // echo "</pre>";
        echo "El codigo: ".$buscar->ConsultaResult->CodigoEstatus."<br>";
        echo "El estado: ".$buscar->ConsultaResult->Estado."<br>";
    } catch (Exception $e) {
        echo "<h3>No se pudo accesar el portal del SAT</h3>";
        echo $e;
    }
} */
	error_reporting(E_ALL);
    $url = "https://consultaqr.facturaelectronica.sat.gob.mx/consultacfdiservice.svc?wsdl";
	$factura = "?re=TME840315KT6&rr=PAGJ690609HV7&tt=599.000000&id=A89A07BD-7BE8-44A1-BF97-43A48942E495";
	echo "<h3>$factura</h3>";
    $prm = array('expresionImpresa'=>$factura);

    try {
        $soapclient = new SoapClient($url);
        $buscar=$soapclient->Consulta($prm);
        echo "<h3>El portal del SAT reporta</h3>";
        echo "El codigo: ".$buscar->ConsultaResult->CodigoEstatus."<br>";
        echo "El estado: ".$buscar->ConsultaResult->Estado."<br>";
    } catch (Exception $e) {
        echo "<h3>No se pudo accesar el portal del SAT</h3>";
        echo $e;
    }
?>
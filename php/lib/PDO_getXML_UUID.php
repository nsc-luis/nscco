<?php
	ini_set("display_errors", 1);
	$db = new PDO('mysql:host=localhost;dbname=pagj690609hv7', 'nscluis', 'Lgro1982');
	// $db = new PDO("sqlsrv:server=.\sqlexpress; Database=factProveedor_ALV961213V22", "sa", "P455w0rd");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
	$uuid = $_GET['uuid'];
	
	function selectBlob($conn, $uuid) {
        // $sql = "SELECT typeArchivoXML,archivoXML,sizeArchivoXML FROM xmls WHERE uuid = :uuid";
        $sql = "SELECT typeArchivoXML,archivoXML,sizeArchivoXML FROM xmls WHERE uuid = :uuid";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(":uuid" => $uuid));
        $stmt->bindColumn(1, $mime);
        $stmt->bindColumn(2, $data, PDO::PARAM_LOB);
		$stmt->bindColumn(3, $size);
 
        $stmt->fetch(PDO::FETCH_BOUND);
 
        return array("mime" => $mime,
            "data" => $data,
			"size" => $size);
    }
	
	try {
		$file = selectBlob($db, $uuid);
		header("Content-Type:" . $file['mime']);
		// print hex2bin($file['data']);
		print $file['data'];
	} catch (PDOException $ex) {
		echo $ex;
	}
?>
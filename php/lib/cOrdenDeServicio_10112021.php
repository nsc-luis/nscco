<?php
	include_once ("cConectaBD.php");
	
	class OrdenDeServicio {
		# Declaracion de variables
		private $folio;
		private $idTipoDocto;
		private $cveCte;
		private $idSucursalCte;
		private $fechaOrdServ;
		private $tiempoInvertido;
		private $idTipoDeServ;
		private $usuario;
		private $recibidoPor;
		private $observacion;
		private $referenciaFact;
		private $idStatus;
		public $bd;
		private $nombreBD;
		private $resultado;
		private $msj;
		
		#Metodos
		public function __construct($bd){
			$this->bd = new ConectaBD($bd);
			return $this->bd;
		}
		public function ordenesDeServicio($usr,$fechaInicial,$fechaFinal) {
			
			$adminUsr = $usr == "ADMIN" || $usr == "JPALACIOS" ? true : false;
			$str = $adminUsr == true && $this->usuario == "Todos" ? "%" : $this->usuario;
			if ($adminUsr == false) { $str = $usr; }
			
			$filtroCveCte = $this->cveCte == "" ? "" : "AND o.cveCte = '$this->cveCte'";
			$filtroIdSucursalCte = $this->idSucursalCte == "" ? "" : "AND o.idSucursalCte = '$this->idSucursalCte'";
			$filtroIdStatus = $this->idStatus == "" ? "" : "AND o.idStatus = '$this->idStatus'";
			
			$sql = "SELECT o.folio, o.importe, o.fechaOrdServ, o.cveCte, o.usuario, o.referenciaFact, o.idTipoDocto, c.idCliente, c.clave, c.nombre nombreCte, st.status, st.idStatus, s.idSucursalCte, s.nombre nombreSucursal
				FROM ordendeservicio o
				JOIN clientes c ON c.clave = o.cveCte
				JOIN sucursalcte s ON s.idSucursalCte = o.idSucursalCte
				JOIN status st ON st.idStatus = o.idStatus
				WHERE usuario LIKE '$str' AND o.folio <> '0'
				$filtroCveCte $filtroIdSucursalCte $filtroIdStatus 
				AND fechaOrdServ BETWEEN '$fechaInicial' AND '$fechaFinal'
				ORDER BY idOrdDeServ DESC";
				// LIMIT $registroInicial,$salto
			$data = $this->bd->consulta($sql);
			// echo $sql;
			return $data;
		}
		public function exportaOrdenesDeServicioExcel($usr, $cte, $sucursal, $status){
			$sql = "SELECT o.folio, o.importe, o.fechaOrdServ, o.cveCte, o.usuario, o.referenciaFact, c.idCliente, c.clave, c.nombre nombreCte, st.status, st.idStatus, s.idSucursalCte, s.nombre nombreSucursal
				FROM ordendeservicio o
				JOIN clientes c ON c.clave = o.cveCte
				JOIN sucursalcte s ON s.idSucursalCte = o.idSucursalCte
				JOIN status st ON st.idStatus = o.idStatus
				WHERE o.usuario LIKE '%$usr' AND o.cveCte LIKE '%$cte' AND o.idSucursalCte LIKE '%$sucursal' AND o.idStatus LIKE '%$status'";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		public function rptServxCte($cveCte, $fInicial, $fFinal){
			$sql = "SELECT o.folio, o.importe, o.fechaOrdServ, o.cveCte, o.usuario, o.referenciaFact,
				c.poliza, c.precioHoraPresencial, c.horasServPresencial, c.horasServRemoto, c.precioHoraRemoto,
				c.idCliente, c.clave, c.nombre nombreCte, 
				s.idSucursalCte, s.nombre nombreSucursal,
				st.idStatus, st.status
				FROM ordendeservicio o
				JOIN clientes c ON c.clave = o.cveCte
				JOIN sucursalcte s ON s.idSucursalCte = o.idSucursalCte
				JOIN status st ON st.idStatus = o.idStatus
				WHERE o.cveCte = '$cveCte' AND o.fechaOrdServ BETWEEN '$fInicial' AND '$fFinal'";
			$response = $this->bd->consulta($sql);
			$data = array();
			if ($response->rowCount() !=0) {
				while ($row = $response->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			$listaDeOrdenes = "SERVICIO PROFESIONAL SEGUN REPORTES: ";
			$sumaServicios = 0;
			$data['partConMercancia'] = array();
			
			for ($i=0; $i<count($data); $i++) {
				$sql = "SELECT * FROM movimientos 
					INNER JOIN productos ON cveProd = clave
					WHERE folioDocto = '" . $data[$i]['folio'] . "'";
				$query = $this->bd->consulta($sql);
				
				$partidas = array();
				while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					$partidas[] = $row;
				}
				foreach ($partidas as $partida) {
					if ($partida['idUnidad'] == "1") {
						$folio = explode("-",$partida['folioDocto']);
						$listaDeOrdenes .= $folio[0] . "-" . $folio[1] . " ";
						$sumaServicios += $partida['importePart'];
					} else {
						$data['partConMercancia'][] = array(
							'cantidad' => $partida['cantidad'],
							'nombre' => $partida['nombre'],
							'precioUnitario' => $partida['precioUnitario'],
							'importe' => $partida['precioUnitario']*$partida['cantidad']
						);
					}
				}
			}
			
			$tienePoliza = $data[0]['poliza'];
			$hrsSerPres = $data[0]['horasServPresencial'];
			$hrsSerRemt = $data[0]['horasServRemoto'];
			$pHraExtPres = $data[0]['precioHoraPresencial'];
			$pHraExtRemt = $data[0]['precioHoraRemoto'];
			
			if ($tienePoliza == 1) {
				$sql = "select sum(tiempoInvertido) as HrsForaneo from ordendeservicio 
					where fechaOrdServ between '$fInicial' and '$fFinal' and cveCte = '$cveCte' and idTipoDeServ = 3";
				$query = $this->bd->consulta($sql);
				$HrsForaneo =  $query->fetch(PDO::FETCH_ASSOC);
				
				$sql = "select sum(tiempoInvertido) as HrsExtPresencial from ordendeservicio 
					where fechaOrdServ between '$fInicial' and '$fFinal' and cveCte = '$cveCte' and idTipoDeServ = 1";
				$query = $this->bd->consulta($sql);
				$response =  $query->fetch(PDO::FETCH_ASSOC);
				$HrsExtPresencial = $response['HrsExtPresencial'] + $HrsForaneo['HrsForaneo'] - $hrsSerPres;
				
				$sql = "select sum(tiempoInvertido) as HrsExtRemoto from ordendeservicio 
					where fechaOrdServ between '$fInicial' and '$fFinal' and cveCte = '$cveCte' and idTipoDeServ = 2";
				$query = $this->bd->consulta($sql);
				$response =  $query->fetch(PDO::FETCH_ASSOC);
				$HrsExtRemoto = $response['HrsExtRemoto'] - $hrsSerRemt;
				
				$importeHrsExtPresencial = $HrsExtPresencial > 0 ? $HrsExtPresencial * $pHraExtPres : 0;
				$importeHrsExtRemoto = $HrsExtRemoto > 0 ? $HrsExtRemoto * $pHraExtRemt : 0;
			}
			$data['extras'] = array();
			$data['extras']['HrsExtPresencial'] = $HrsExtPresencial;
			$data['extras']['HrsExtRemoto'] = $HrsExtRemoto;
			$data['extras']['importeHrsExtPresencial'] = $importeHrsExtPresencial;
			$data['extras']['importeHrsExtRemoto'] = $importeHrsExtRemoto;
			
			$data['listaDeOrdenes'] = $listaDeOrdenes;
			$data['sumaServicios'] = $sumaServicios;
			
			return $data;
		}
		
		public function getInfoOrdServNva($str,$usr){
			$usuario = $str == "0" ? "AND o.usuario = '$usr'" : "";
			$sql = "SELECT o.idOrdDeServ, o.folio, o.idTipoDocto, o.cveCte, o.idSucursalCte, o.fechaOrdServ, o.tiempoInvertido, 
				o.idTipoDeServ, o.usuario, o.recibidoPor, o.observacion, o.referenciaFact, o.idStatus, status.status,
				(SELECT SUM(importePart) FROM movimientos WHERE folioDocto = '$str' AND usuario = '$usr') AS importe
				FROM ordendeservicio o
				INNER JOIN clientes ON o.cveCte = clientes.clave
				INNER JOIN tipodeservicio ON o.idTipoDeServ = tipodeservicio.idTipoDeServ
				INNER JOIN status ON o.idStatus = status.idStatus
				WHERE o.folio = '$str' $usuario";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		public function getInfoOrdServ($str){
			$sql = "SELECT * FROM ordendeservicio
				INNER JOIN clientes ON ordendeservicio.cveCte = clientes.clave
				INNER JOIN tipodeservicio ON ordendeservicio.idTipoDeServ = tipodeservicio.idTipoDeServ
				INNER JOIN status ON ordendeservicio.idStatus = status.idStatus
				WHERE folio = '$str'";
			$datos = $this->bd->consulta($sql)->fetch(PDO::FETCH_BOTH);
			$this->idTipoDocto = $datos['idTipoDocto'];
			$this->usuario = $datos['usuario'];
			$this->folio = $datos['folio'];
			$this->idStatus = $datos['idStatus'];
			$this->fechaOrdServ = $datos['fechaOrdServ'];
			$this->cveCte = $datos['cveCte'];
			$this->idSucursalCte = $datos['idSucursalCte'];
			$this->referenciaFact = $datos['referenciaFact'];
			$this->tiempoInvertido = $datos['tiempoInvertido'];
			$this->idTipoDeServ = $datos['idTipoDeServ'];
			$this->observacion = $datos['observacion'];
			$this->idStatus = $datos['idStatus'];
			$this->recibidoPor = $datos['recibidoPor'];
		}
		public function editarOrdServ($str){
			$sql = "SELECT SUM(importePart) AS totalOrdServ FROM movimientos
				WHERE folioDocto = '$str' AND usuario = '$this->usuario' AND idTipoDocto = 1";
			$data = $this->bd->consulta($sql)->fetch();
			$importeOrdServ = $data['totalOrdServ'];
			$sql = "UPDATE ordendeservicio SET cveCte = '$this->cveCte'
				,idSucursalCte = '$this->idSucursalCte'
				,fechaOrdServ = '$this->fechaOrdServ'
				,tiempoInvertido = '$this->tiempoInvertido'
				,recibidoPor = '$this->recibidoPor'
				,observacion = '$this->observacion'
				,referenciaFact = '$this->referenciaFact'
				,idTipoDeServ = '$this->idTipoDeServ'
				,importe = '$importeOrdServ'
				WHERE folio = '$str'";
			$this->bd->consulta($sql);
		}
		public function borraProducto($str){
			$sql = "DELETE FROM productos WHERE clave = '$str'";
			$this->bd->consulta($sql);
		}
		public function nvaOrdServ($usr) {
			date_default_timezone_set('America/Mexico_City');
			$fecha = date("Y-m-d");
			$sql = "SELECT folio FROM ordendeservicio
				WHERE folio = '0' AND usuario = '$usr'";
			$query = $this->bd->consulta($sql);
			$registro = $query->fetch(PDO::FETCH_ASSOC);
			if (is_null($registro['folio'])) {
				$sql = "INSERT INTO ordendeservicio (
					folio,idTipoDocto,cveCte,idSucursalCte,fechaOrdServ,tiempoInvertido
					,idTipoDeServ,usuario,recibidoPor,observacion,referenciaFact,idStatus,importe)
					VALUES ('0','1','empresa.novalid','101','$fecha','0','1','$usr','','','','1','0.00')";
				$this->bd->consulta($sql);
			} else {
				$sql = "UPDATE ordendeservicio SET fechaOrdServ = '$fecha' WHERE folio = '0' AND usuario = '$usr'";
				$this->bd->consulta($sql);
			}
		}
		public function guardarNvaOrdServ(){
			$sql = "SELECT folio FROM ordendeservicio WHERE folio != '0' ORDER BY idOrdDeServ DESC LIMIT 1";
			$result = $this->bd->consulta($sql)->fetch();
			$rFolio = explode("-",$result['folio']);
			
			$fechaFolio = explode('-',$this->fechaOrdServ);
			$fechaFolio2 = $fechaFolio[0] . $fechaFolio[1] . $fechaFolio[2];
			$folio = "R-" . ($rFolio[1] + 1) . "-" . $fechaFolio2;
			$idStatus = 1;
			$sql = "SELECT SUM(importePart) AS totalOrdServ FROM movimientos
				WHERE folioDocto = '0' AND usuario = '$this->usuario' AND idTipoDocto = 1";
			$data = $this->bd->consulta($sql)->fetch();
			$importeOrdServ = $data['totalOrdServ'];
			
			$sql = "INSERT INTO ordendeservicio (folio,idTipoDocto,
				cveCte,idSucursalCte,fechaOrdServ,tiempoInvertido,
				idTipoDeServ, observacion,usuario,recibidoPor,idStatus,importe) 
				VALUES ('$folio'
				,'1'
				,'$this->cveCte'
				,'$this->idSucursalCte'
				,'$this->fechaOrdServ'
				,'$this->tiempoInvertido'
				,'$this->idTipoDeServ'
				,'$this->observacion'
				,'$this->usuario'
				,'$this->recibidoPor'
				,'$idStatus'
				,'$importeOrdServ')";
			$this->bd->consulta($sql);

			$sql = "UPDATE movimientos SET folioDocto = '$folio'
				WHERE folioDocto = '0' AND usuario = '$this->usuario' AND idTipoDocto = 1";
			$this->bd->consulta($sql);
		}
		public function borrarOrdServ($str) {
			$sql = "SELECT * FROM ordendeservicio WHERE folio = '$str'";
			$query = $this->bd->consulta($sql);
			$registro = $query->fetch(PDO::FETCH_ASSOC);
			if ($registro['idStatus'] != "1") {
				$this->msj = "ERROR: Solo se pueden borrar ordenes de servicio con STATUS ACTIVO.";
			} else {
				$sql = "DELETE FROM ordendeservicio WHERE folio = '$str'";
				$this->bd->consulta($sql);
				$sql = "DELETE FROM movimientos WHERE folioDocto = '$str'";
				$this->bd->consulta($sql);
			}
		}
		public function guardaStatus($str){
			$sql = "UPDATE ordendeservicio 
				SET idStatus = '$this->idStatus', referenciaFact = '$this->referenciaFact'
				WHERE folio = '$str'";
			$this->bd->consulta($sql);
		}
		
		# GET´s
		public function getFolio(){ return $this->folio; }
		public function getIdTipoDocto(){ return $this->idTipoDocto; }
		public function getCveCte(){ return $this->cveCte; }
		public function getIdSucursalCte(){ return $this->idSucursalCte; }
		public function getFechaOrdServ(){ return $this->fechaOrdServ; }
		public function getTiempoInvertido(){ return $this->tiempoInvertido; }
		public function getIdTipoDeServ(){ return $this->idTipoDeServ; }
		public function getRecibidoPor(){ return $this->recibidoPor; }
		public function getObservacion(){ return $this->observacion; }
		public function getReferenciaFact(){ return $this->referenciaFact; }
		public function getIdStatus(){ return $this->idStatus; }
		public function getUsuario(){ return $this->usuario; }
		public function getMsj(){ return $this->msj; }
		public function getResultado(){ return $this->resultado; }
		
		# SET´s
		public function setFolio($str){ $this->folio = $str; }
		public function setIdTipoDocto($str){ $this->idTipoDocto = $str; }
		public function setCveCte($str){ $this->cveCte = $str; }
		public function setIdSucursalCte($str){ $this->idSucursalCte = $str; }
		public function setFechaOrdServ($str){ $this->fechaOrdServ = $str; }
		public function setTiempoInvertido($str){ $this->tiempoInvertido = $str; }
		public function setIdTipoDeServ($str){ $this->idTipoDeServ = $str; }
		public function setUsuario($str){ $this->usuario = $str; }
		public function setRecibidoPor($str){ $this->recibidoPor = $str; }
		public function setObservacion($str){ $this->observacion = $str; }
		public function setReferenciaFact($str){ $this->referenciaFact = $str; }
		public function setIdStatus($str){ $this->idStatus = $str; }
		public function setMsj($str){ $this->msj = $str; }
	}
?>
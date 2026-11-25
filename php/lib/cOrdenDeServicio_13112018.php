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
		public function __construct(){
			$this->bd = new ConectaBD();
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
			$datos = mysql_fetch_array($this->bd->consulta($sql));
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
			$data = mysql_fetch_array($this->bd->consulta($sql));
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
			$registro = mysql_fetch_assoc($query);
			if (is_null($registro['folio'])) {
				$sql = "INSERT INTO ordendeservicio (
					folio,idTipoDocto,cveCte,idSucursalCte,fechaOrdServ,tiempoInvertido
					,idTipoDeServ,usuario,recibidoPor,observacion,referenciaFact,idStatus,importe)
					VALUES ('0','1','empresa.novalid','101','$fecha','0','1','$usr','','','','1','0.00')";
				$this->bd->consulta($sql);
			}
		}
		public function guardarNvaOrdServ(){
			$sql = "SELECT folio FROM ordendeservicio WHERE folio != '0' ORDER BY idOrdDeServ DESC LIMIT 1";
			$result = mysql_fetch_array($this->bd->consulta($sql));
			$rFolio = split("-",$result['folio']);
			
			$fechaFolio = split('-',$this->fechaOrdServ);
			$fechaFolio2 = $fechaFolio[0] . $fechaFolio[1] . $fechaFolio[2];
			$folio = "R-" . ($rFolio[1] + 1) . "-" . $fechaFolio2;
			$idStatus = 1;
			$sql = "SELECT SUM(importePart) AS totalOrdServ FROM movimientos
				WHERE folioDocto = '0' AND usuario = '$this->usuario' AND idTipoDocto = 1";
			$data = mysql_fetch_array($this->bd->consulta($sql));
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
			$registro = mysql_fetch_assoc($query);
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
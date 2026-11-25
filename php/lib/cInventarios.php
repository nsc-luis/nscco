<?php
	include_once ("cConectaBD.php");
	//ini_set("display_errors", 0);
	
	class Inventarios {
		# Declaracion de variables
		private $idCliente;
		private $idTipoDeDispositivo;
		private $SO;
		// private $Arquitectura;
		private $Host;
		// private $IP;
		private $MAC;
		private $HD;
		private $CPU;
		private $RAM;
		private $fabricante;
		private $fecharegistro;
		private $Observaciones;
		//private $officeversion;
		//private $licencias;
		private $idsucursalcte;
		private $resultado;
		private $msj;
		//private $obsLicencia;
		private $fechacaducidad;
		private $idLicencia;
		private $usuario;
		private $departamento;
		private $serie;
		public $bd;
		
		#Metodos
		public function __construct($db){
			$this->bd = new ConectaBD($db);
			return $this->bd;
		}
		public function inventario($idCte){
			/* $sql = "SELECT i.idInventario,i.idCliente,i.idTipoDeDispositivo,i.SO,i.Host,i.MAC,i.HD,i.CPU,i.RAM
				,i.Fabricante,i.Observaciones,i.fecharegistro,i.officeversion, i.activo,i.idsucursalcte
				,c.nombre nombreCte, s.nombre nombreSucCte
				FROM inventarios i
				JOIN clientes c ON c.idCliente = i.idCliente
				JOIN sucursalcte s ON s.idSucursalCte = i.idsucursalcte
				WHERE i.idCliente = $idCte
				ORDER BY nombreSucCte"; */
			$sql = "SELECT i.idInventario,i.idCliente,i.idTipoDeDispositivo,i.SO,i.Host,i.MAC,i.HD,i.CPU,i.RAM,i.usuario,i.departamento,i.serie
				,i.Fabricante,i.serie,i.Observaciones,i.fecharegistro, i.activo, i.idsucursalcte
				,c.nombre nombreCte, s.nombre nombreSucCte, i.usuario, i. departamento 
				FROM inventarios i
				JOIN clientes c ON c.idCliente = i.idCliente
				JOIN sucursalcte s ON s.idSucursalCte = i.idsucursalcte
				WHERE i.idCliente = $idCte
				ORDER BY nombreSucCte";
			$result = $this->bd->consulta($sql);
			$data = array();
			if ($result->rowCount() !=0) {
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
			}
			
			for ($i = 0; $i < count($data); $i++) {
				$data2 = [];
				$idInv = $data[$i]['idInventario'];
				$sql = "SELECT * FROM licenciasasignadas WHERE idInventario = $idInv";
				$query = $this->bd->consulta($sql);
				if ($query->rowCount()){
					while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
						$data2[] = $row;
					}
				}
				$data[$i]['licencias'] = array($data2);
			}
			return $data;
			//echo "hi";
		}
		public function listaCtesInventario(){
			$sql = "SELECT DISTINCT(idCliente) FROM inventarios";
			$data = $this->bd->consulta($sql);
			$data2 = array();
			if ($data->rowCount() !=0) {
				while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
					$data2[] = $row;
				}
			}
			
			$data3 = array();
			for ($i = 0; $i < count($data2); $i++) {
				$idCte = $data2[$i]['idCliente'];
				$sql2 = "SELECT idCliente, nombre FROM clientes WHERE idCliente = $idCte";
				$data4 = $this->bd->consulta($sql2);
				if ($data4->rowCount() !=0) {
					while ($row = $data4->fetch(PDO::FETCH_ASSOC)) {
						$data3[] = $row;
					}
				}
			}
			return $data3;
		}
		public function getInfoDisp($str){
			$sql = "SELECT observaciones,idSucursalCte FROM inventarios WHERE idInventario = 29
				WHERE idInventario = '$str'";
			$data = $this->bd->consulta($sql);
			
			return $data;
		}
		
		public function rptCaducidadAvUnaSemana(){
			$fechaReferencia = date("Y-m-d", strtotime("-7 days"));
			$sql = "select l.idlicencia,l.idtipolicencia,l.nombre nomAV, l.fechacaducidad,l.idinventario idInvLic,
				i.idInventario idInvCte,i.idCliente,i.Host,i.MAC,i.idlicenciaAV,
				c.idCliente,c.clave cveCte,c.nombre nomCte
				from licenciasdesoftware l
				left join inventarios i on i.idlicenciaAV = l.idlicencia
				left join clientes c on c.idCliente = i.idCliente
				where l.idtipolicencia = 1 and l.fechacaducidad < '" . $fechaReferencia . "'
			";
			$response = $this->bd->consulta($sql);
			$data = array();
			while ($row = $response->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $row;
			}
			
			for ($i = 0; $i < count($data); $i++) {
				$sucursal = array();
				$sql = "select idsucursalcte, nombre nomSucCte from sucursalcte
					where idCliente = " . $data[$i]['idCliente'] . "
				";
				$response = $this->bd->consulta($sql);
				while ($row = $response->fetch(PDO::FETCH_ASSOC)) {
					$sucursal[] = $row;
				}
				
				$data[$i]['idsucursalcte'] = $sucursal[0]['idsucursalcte'];
				$data[$i]['nomSucCte'] = $sucursal[0]['nomSucCte'];
				unset($sucursal);
			}
			return $data;
		}
		
		public function borrarDisp($str) {
			$sql = "DELETE FROM licenciasasignadas WHERE idInventario = $str";
			$this->bd->consulta($sql);
			$sql = "DELETE FROM inventarios WHERE idInventario = '$str'";
			$this->bd->consulta($sql);
		}
		
		public function getInfoLicencia($idLic) {
			$sql = "SELECT * FROM licenciasdesoftware WHERE idlicencia = $idLic";
			$query = $this->bd->consulta($sql);
			$data = array();
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $row;
			}
			return $data;
		}
		
		public function licenciasCaducidad15dias() {
			$sql = "SELECT l.idlicencia, l.idtipolicencia, l.idcliente, l.nombre, fechacaducidad, c.nombre nombreCte, tl.nombre tipoLicencia
				FROM licenciasdesoftware l
				JOIN clientes c ON c.idcliente = l.idcliente
				JOIN tipodelicencia tl ON tl.idtipolicencia = l.idtipolicencia 
				WHERE fechaCaducidad BETWEEN NOW() AND NOW() + INTERVAL 15 DAY
				ORDER BY nombreCte ASC, tipoLicencia ASC";
			$data = array();
			$query = $this->bd->consulta($sql);
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $row;
			}
			return $data;
		}
		
		public function officeCaducados() {
			$sql = "SELECT l.idlicencia, l.idtipolicencia, l.idcliente, l.nombre, fechacaducidad, c.nombre nombreCte, tl.nombre tipoLicencia 
				FROM licenciasdesoftware l
				JOIN clientes c ON c.idcliente = l.idcliente
				JOIN tipodelicencia tl ON tl.idtipolicencia = l.idtipolicencia 
				WHERE l.fechacaducidad <= NOW() AND l.idTipoLicencia = 3
				ORDER BY nombreCte ASC";
			$data = array();
			$query = $this->bd->consulta($sql);
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $row;
			}
			return $data;
		}
		
		public function avCaducados() {
			$sql = "SELECT l.idlicencia, l.idtipolicencia, l.idcliente, l.nombre, fechacaducidad, c.nombre nombreCte, tl.nombre tipoLicencia 
				FROM licenciasdesoftware l
				JOIN clientes c ON c.idcliente = l.idcliente
				JOIN tipodelicencia tl ON tl.idtipolicencia = l.idtipolicencia 
				WHERE l.fechacaducidad <= NOW() AND l.idTipoLicencia = 1
				ORDER BY nombreCte ASC";
			$data = array();
			$query = $this->bd->consulta($sql);
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $row;
			}
			return $data;
		}
		
		public function inventarioSinAsignacion() {
			$sql = "SELECT i.idInventario,i.idCliente,i.idTipoDeDispositivo,i.SO,i.Host,i.MAC,i.HD,i.CPU,i.RAM
				,i.Fabricante,i.Observaciones,i.fecharegistro, i.activo, i.idsucursalcte
				,c.nombre nombreCte, s.nombre nombreSucCte
				FROM inventarios i
				JOIN clientes c ON c.idCliente = i.idCliente
				JOIN sucursalcte s ON s.idSucursalCte = i.idsucursalcte
				WHERE idInventario NOT IN (SELECT idInventario FROM licenciasasignadas)
				ORDER BY c.nombre ASC, nombreSucCte ASC";
			$data = array();
			$query = $this->bd->consulta($sql);
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $row;
			}
			return $data;
		}
		
		public function licAsingPorDisp($id) {
			$sql ="SELECT * FROM licenciasasignadas WHERE idInventario = $id";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		public function guardarDisp($idInv,$idAV,$idOffice) {
			$sql = "UPDATE inventarios SET 
					Observaciones = '$this->Observaciones', 
					idsucursalcte = '$this->idsucursalcte', 
					fecharegistro = '$this->fecharegistro',
					usuario = '$this->usuario',
					departamento = '$this->departamento',
					serie = '$this->serie'
				WHERE idinventario = $idInv";
			//echo $sql;
			$this->bd->consulta($sql);
			$sql = "SELECT idLicencia FROM licenciasasignadas WHERE idInventario = $idInv";
			$data = $this->bd->consulta($sql);
			if ($data->rowCount() == 0) {
				$sql = "INSERT INTO licenciasasignadas (idInventario,idLicencia,idTipoLicencia) VALUES ($idInv,$idOffice,3)";
				$this->bd->consulta($sql);
				$sql = "INSERT INTO licenciasasignadas (idInventario,idLicencia,idTipoLicencia) VALUES ($idInv,$idAV,1)";
				$this->bd->consulta($sql);
			} else {
				$licencias = [];
				while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
					$licencias[] = $row;
				}
				foreach ($licencias as $l) {
					$sql = "SELECT * FROM licenciasdesoftware WHERE idlicencia = $l[idLicencia]";
					// $query = mysql_fetch_assoc($this->bd->consulta($sql));
					$query = $this->bd->consulta($sql)->fetch(PDO::FETCH_ASSOC);
					if ($query['idtipolicencia'] == 1) { // Actualiza licencia de AV
						$sql = "UPDATE licenciasasignadas SET idLicencia = $idAV WHERE idInventario = $idInv AND idTipoLicencia = $query[idtipolicencia]";
						$this->bd->consulta($sql);
					}
					if ($query['idtipolicencia'] == 3) { // Actualiza licencia de Office
						$sql = "UPDATE licenciasasignadas SET idLicencia = $idOffice WHERE idInventario = $idInv AND idTipoLicencia = $query[idtipolicencia]";
						$this->bd->consulta($sql);
					}
				}
			}
		}
		
		public function ConsultaCaducidadAVPorCliente() {
			$sql = "SELECT i.idInventario id, i.Host, i.fecharegistro, i.CPU, i.RAM, i.HD, 
				i.Fabricante, i.SO, i.observaciones, i.usuario, i.departamento, i.serie, i.idSucursalCte, 
				sc.nombre Sucursal, 
				ls.fechacaducidad, ls.idLicencia, ls.observaciones as lsobs, ls.nombre Descripcion, 
				tl.nombre, tl.idTipoLicencia, tl.nombre TipoLicencia 
				FROM inventarios i 
				JOIN sucursalcte sc on i.idsucursalcte = sc.idsucursalcte 
				JOIN licenciasasignadas la ON la.idInventario = i.idInventario 
				LEFT JOIN licenciasdesoftware ls ON ls.idLicencia = la.idLicencia 
				LEFT JOIN tipodelicencia tl ON tl.idTipoLicencia = ls.idTipoLicencia 
				WHERE la.idTipoLicencia = 1 AND i.idCliente = '$this->idCliente' 
				ORDER BY Sucursal, i.departamento DESC";
			//echo $sql;
			$data = array();
			$query = $this->bd->consulta($sql);
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $row;
			}
			return $data;
		}
		
		public function ConsultaCaducidadOfficePorCliente() {
			if ($this->fecharegistro != "completo") {
				$whereClausule = "WHERE la.idTipoLicencia = 3 AND i.idCliente = '$this->idCliente' AND i.fecharegistro >= '$this->fecharegistro'";
			}
			else {
				$whereClausule = "WHERE la.idTipoLicencia = 3 AND i.idCliente = '$this->idCliente'";
			}
			$sql = "SELECT i.idInventario id, i.Host, i.fecharegistro, tl.nombre TipoLicencia, ls.idLicencia, ls.nombre Descripcion, ls.fechacaducidad, i.observaciones, ls.observaciones as lsobs, sc.nombre as sucursal  
				FROM inventarios i 
				JOIN sucursalcte sc ON i.idsucursalcte = sc.idsucursalcte 
				JOIN licenciasasignadas la ON la.idInventario = i.idInventario 
				LEFT JOIN licenciasdesoftware ls ON ls.idLicencia = la.idLicencia 
				LEFT JOIN tipodelicencia tl ON tl.idTipoLicencia = ls.idTipoLicencia 
				$whereClausule 
				ORDER BY sucursal DESC, ls.idLicencia DESC";
			$data = array();
			$query = $this->bd->consulta($sql);
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $row;
			}
			return $data;
		}
		
		public function ConsultaCaducidadOfficeAVPorClienteyFecha() {
			$sql = "SELECT i.idInventario, i.host, i.Observaciones, tl.nombre tipoLicencia, ls.idlicencia, ls.nombre, ls.fechacaducidad, s.nombre sucursal 
			FROM licenciasasignadas la
				join tipodelicencia tl on la.idTipoLicencia = tl.idtipolicencia
				join inventarios i on la.idInventario = i.idInventario
				join licenciasdesoftware ls on la.idLicencia = ls.idlicencia
				join sucursalcte s on i.idsucursalcte = s.idsucursalcte
				where i.idCliente = $this->idCliente and ls.fechacaducidad < '$this->fechacaducidad'
				order by tl.nombre, s.nombre, ls.fechacaducidad;";
			$data = array();
			$query = $this->bd->consulta($sql);
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $row;
			}
			//echo $sql;
			return $data;
		}
		
		# GET´s 
		public function getMsj() { return $this->msj; }
		
		# SET´s		
		public function setIdCliente($str){ $this->idCliente = $str; }
		public function setIdTipoDeDispositivo($str){ $this->idTipoDeDispositivo = $str; }
		public function setSO($str){ $this->SO = $str; }
		public function setHost($str){ $this->Host = $str; }
		public function setMAC($str){ $this->MAC = $str; }
		public function setHD($str){ $this->HD = $str; }
		public function setCPU($str){ $this->CPU = $str; }
		public function setRAM_utilizable($str){ $this->RAM_utilizable = $str; }
		public function setMarca($str){ $this->Marca = $str; }
		public function setModelo($str){ $this->Modelo = $str; }
		public function setFechaRegistro($str){ $this->fecharegistro = $str; }
		public function setObservaciones($str){ $this->Observaciones = $str; }
		public function setIdLicenciaOffice($str){ $this->idLicenciaOffice = $str; }
		public function setIdLicencia ($str) { $this->idLicencia = $str; }
		public function setIdsucursalcte ($str) { $this->idsucursalcte = $str; }
		public function setFechaCaducidad ($str) { $this->fechacaducidad = $str; }
		public function setUsuario ($str) { $this->usuario = $str; }
		public function setDepartamento ($str) { $this->departamento = $str; }
		public function setSerie ($str) { $this->serie = $str; }
	}
?>
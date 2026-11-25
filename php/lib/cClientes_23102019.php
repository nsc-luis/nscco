<?php
	include_once ("cConectaBD.php");
	
	class Clientes {
		# Declaracion de variables
		private $clave;
		private $nombre;
		private $rfc;
		private $direccion;
		private $calle;
		private $noExt;
		private $colonia;
		private $municipio;
		private $estado;
		private $pais;
		private $cp;
		private $telefono1;
		private $email;
		private $poliza;
		private $horasServPresencial;
		private $precioHoraPresencial;
		private $horasServRemoto;
		private $precioHoraRemoto;
		private $idSucursalCte;
		private $cveCte;
		private $nomSucursal;
		private $dirSucCte;
		private $tel1Suc;
		public $bd;
		// private $nombreBD;
		private $resultado;
		private $msj;
		
		# Metodos para clientes
		public function __construct(){
			$this->bd = new ConectaBD();
			return $this->bd;
		}
		public function clientes(){
			$sql = "SELECT * FROM clientes ORDER BY nombre";
			$query = $this->bd->consulta($sql);
			$ctes = array();
			while ($row = mysql_fetch_assoc($query)){
				$ctes[] = $row;
			}
			for ($i=0; $i<count($ctes); $i++) {
				$cte = $ctes[$i]['clave'];
				$sql = "SELECT SUM(importe) as saldo FROM ordendeservicio
					WHERE cveCte = '$cte' AND idStatus = '1'";
				$query = $this->bd->consulta($sql);
				$saldo =  mysql_fetch_assoc($query);
				$ctes[$i]['saldo'] = $saldo['saldo'];
			}
			return $ctes;
		}
		public function getInfoCte($str){
			$sql = "SELECT * FROM clientes WHERE clave = '$str'";
			$datos = mysql_fetch_array($this->bd->consulta($sql));
			$this->clave = $datos['clave'];
			$this->nombre = $datos['nombre'];
			$this->rfc = $datos['rfc'];
			$this->direccion = $datos['direccion'];
			$this->calle = $datos['calle'];
			$this->noExt = $datos['noExt'];
			$this->colonia = $datos['colonia'];
			$this->municipio = $datos['municipio'];
			$this->estado = $datos['estado'];
			$this->pais = $datos['pais'];
			$this->cp = $datos['cp'];
			$this->telefono1 = $datos['telefono1'];
			$this->email = $datos['email'];
			$this->poliza = $datos['poliza'];
			$this->horasServPresencial = $datos['horasServPresencial'];
			$this->precioHoraPresencial = $datos['precioHoraPresencial'];
			$this->horasServRemoto = $datos['horasServRemoto'];
			$this->precioHoraRemoto = $datos['precioHoraRemoto'];
		}
		public function editaCliente($str){
			/*
			$sql = "UPDATE clientes SET nombre = '$this->nombre'
				,rfc = '$this->rfc'
				,direccion = '$this->direccion'
				,calle = '$this->calle'
				,noExt = '$this->noExt'
				,colonia = '$this->colonia'
				,municipio = '$this->municipio'
				,estado = '$this->estado'
				,pais = '$this->pais'
				,cp = '$this->cp'
				,telefono1 = '$this->telefono1'
				,email = '$this->email'
				WHERE clave = '$str'";
			*/
			$sql = "UPDATE clientes SET nombre = '$this->nombre'
				,rfc = '$this->rfc'
				,direccion = '$this->direccion'
				,telefono1 = '$this->telefono1'
				,email = '$this->email'
				,poliza = '$this->poliza'
				,horasServPresencial = '$this->horasServPresencial'
				,precioHoraPresencial = '$this->precioHoraPresencial'
				,horasServRemoto = '$this->horasServRemoto'
				,precioHoraRemoto = '$this->precioHoraRemoto'
				WHERE clave = '$str'";
			$this->bd->consulta($sql);
		}
		public function borraCliente($str){
			$sql = "SELECT * FROM ordendeservicio WHERE cveCte = '$str'";
			$query = $this->bd->consulta($sql);
			$contador = mysql_num_rows($query);
			if ( $contador > 0 ) {
				$this->msj = "El cliente no se puede borrar porque tiene movimientos.";
			} else {
				$sql = "DELETE FROM clientes WHERE clave = '$str'";
				$this->bd->consulta($sql);
				$sql = "DELETE FROM sucursalcte WHERE cveCte = '$str'";
				$this->bd->consulta($sql);
			}
		}
		public function nuevoCliente(){
			/*
			$sql = "INSERT INTO clientes (clave,nombre,rfc,calle,noExt,colonia,municipio,estado,pais,cp,telefono1,email) 
				VALUES ('$this->clave'
				,'$this->nombre'
				,'$this->rfc'
				,'$this->direccion'
				,'$this->calle'
				,'$this->noExt'
				,'$this->colonia'
				,'$this->municipio'
				,'$this->estado'
				,'$this->pais'
				,'$this->cp'
				,'$this->telefono1'
				,'$this->email')";
			*/
			$sql = "SELECT * FROM clientes WHERE clave = '$this->clave'";
			$query = $this->bd->consulta($sql);
			$contador = mysql_num_rows($query);
			if ( $contador > 0 ) {
				$this->msj = "La clave del cliente ya existe, intente otra.";
			} else {
				$sql = "INSERT INTO clientes (clave
				,nombre
				,rfc
				,direccion
				,telefono1
				,email
				,poliza,horasServPresencial
				,precioHoraPresencial
				,horasServRemoto
				,precioHoraRemoto) 
				VALUES ('$this->clave'
				,'$this->nombre'
				,'$this->rfc'
				,'$this->direccion'
				,'$this->telefono1'
				,'$this->email'
				,'$this->poliza'
				,'$this->horasServPresencial'
				,'$this->precioHoraPresencial'
				,'$this->horasServRemoto'
				,'$this->precioHoraRemoto')";
				$this->bd->consulta($sql);
				
				$ofnaGral = "Oficina general";
				$sql = "INSERT INTO sucursalcte (nombre,cveCte,dirSucCte,telefono1) 
					VALUES ('$ofnaGral'
					,'$this->clave'
					,'$this->direccion'
					,'$this->telefono1')";
				$this->bd->consulta($sql);
			}
		}
		
		# GET큦
		public function getClave(){ return $this->clave; }
		public function getNombre(){ return $this->nombre; }
		public function getRFC(){ return $this->rfc; }
		public function getDireccion(){ return $this->direccion; }
		public function getCalle(){ return $this->calle; }
		public function getNoExt(){ return $this->noExt; }
		public function getColonia(){ return $this->colonia; }
		public function getMunicipio(){ return $this->municipio; }
		public function getEstado(){ return $this->estado; }
		public function getPais(){ return $this->pais; }
		public function getCP(){ return $this->cp; }
		public function getTelefono1(){ return $this->telefono1; }
		public function getEmail(){ return $this->email; }
		public function getPoliza(){ return $this->poliza; }
		public function getHorasServPresencial(){ return $this->horasServPresencial; }
		public function getPrecioHoraPresencial(){ return $this->precioHoraPresencial; }
		public function getHorasServRemoto(){ return $this->horasServRemoto; }
		public function getPrecioHoraRemoto(){ return $this->precioHoraRemoto; }
		public function getMsj(){ return $this->msj; }
		public function getResultado(){ return $this->resultado; }
		
		# SET큦
		public function setClave($str){ $this->clave = $str; }
		public function setNombre($str){ $this->nombre = $str; }
		public function setRFC($str){ $this->rfc = $str; }
		public function setDireccion($str){ $this->direccion = $str; }
		public function setCalle($str){ $this->calle = $str; }
		public function setNoExt($str){ $this->noExt = $str; }
		public function setColonia($str){ $this->colonia = $str; }
		public function setMunicipio($str){ $this->municipio = $str; }
		public function setEstado($str){ $this->estado = $str; }
		public function setPais($str){ $this->pais = $str; }
		public function setCP($str){ $this->cp = $str; }
		public function setTelefono1($str){ $this->telefono1 = $str; }
		public function setEmail($str){ $this->email = $str; }
		public function setPoliza($str){ $this->poliza = $str; }
		public function setHorasServPresencial($str){ $this->horasServPresencial = $str; }
		public function setPrecioHoraPresencial($str){ $this->precioHoraPresencial = $str; }
		public function setHorasServRemoto($str){ $this->horasServRemoto = $str; }
		public function setPrecioHoraRemoto($str){ $this->precioHoraRemoto = $str; }
		
		
		# Metodos para sucursales de clientes
		public function listaOficinas(){
			$sql = "SELECT * FROM sucursalcte";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		public function getSucPorCte($str){
			$sql = "SELECT * FROM sucursalcte 
				WHERE cveCte = '$str'
				ORDER BY nombre";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		public function getInfoSucCte($str){ //JOIN clientes
			$sql = "SELECT idSucursalCte
				,sucursalcte.nombre AS nomSucursal
				,dirSucCte
				,sucursalcte.telefono1 AS tel1SucCte
				FROM sucursalcte 
				INNER JOIN clientes ON cveCte = clave
				WHERE idSucursalCte = $str";
			$datos = mysql_fetch_array($this->bd->consulta($sql));
			$this->idSucursalCte = $datos['idSucursalCte'];
			$this->nomSucursal = $datos['nomSucursal'];
			$this->dirSucCte = $datos['dirSucCte'];
			$this->tel1SucCte = $datos['tel1SucCte'];
			
		}
		public function editaSucCte($str){
			$sql = "UPDATE sucursalcte SET nombre = '$this->nomSucursal'
				,dirSucCte = '$this->dirSucCte'
				,telefono1 = '$this->tel1Suc'
				WHERE idSucursalCte = $str";
			$this->bd->consulta($sql);
		}
		public function nvaSucCte(){
			$sql = "INSERT INTO sucursalcte (nombre,cveCte,dirSucCte,telefono1) 
				VALUES ('$this->nomSucursal'
				,'$this->cveCte'
				,'$this->dirSucCte'
				,'$this->tel1Suc')";
			$this->bd->consulta($sql);
		}
		public function borraSucCte($str){
			$sql = "SELECT * FROM ordendeservicio WHERE idSucursalCte = '$str'";
			$query = $this->bd->consulta($sql);
			$contador = mysql_num_rows($query);
			$sql = "SELECT * FROM sucursalcte WHERE cveCte = '$this->cveCte'";
			$query = $this->bd->consulta($sql);
			$contador2 = mysql_num_rows($query);
			if ( $contador > 0 || $contador2 == 1) {
				$this->msj = "NOTAS: No se puede borrar una sucursal que tiene movimientos. Cada cliente debe tener al menos una sucursal.";
			} else {
				$sql = "DELETE FROM sucursalcte WHERE idSucursalCte = $str";
				$this->bd->consulta($sql);
			}
		}
		
		# GET큦
		public function getIdSucursalCte(){ return $this->idSucursalCte; }
		public function getNomSucursal(){ return $this->nomSucursal; }
		public function getCveCte(){ return $this->cveCte; }
		public function getDirSucCte(){ return $this->dirSucCte; }
		public function getTel1Suc(){ return $this->tel1Suc; }
		
		# SET큦
		public function setIdSucursalCte($str){ $this->idSucursalCte = $str; }
		public function setCveCte($str){ $this->cveCte = $str; }
		public function setNomSucursal($str){ $this->nomSucursal = $str; }
		public function setDirSucCte($str){ $this->dirSucCte = $str; }
		public function setTel1Suc($str){ $this->tel1Suc = $str; }
	}
?>
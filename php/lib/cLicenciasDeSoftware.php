<?php	
	include_once ("cConectaBD.php");
	date_default_timezone_set('America/Mexico_City');
	
	class LicenciasDeSoftware
	{
		#Declaracion de variables
		private $idtipolicencia;
		private $nombre;
		private $fechaRegistro;
		private $fechaCaducidad;
		private $observaciones;
		private $idCliente;
		// private $cveCte;
		private $serie;
		public $bd;
		private $resultado;
		private $msj;
		
		#Metodos de la clase
		public function __construct($bd){
			$this->bd = new ConectaBD($bd);
			return $this->bd;
		}
		public function listaLicenciaSoftware() //Obtener todas las licencias registradas
		{
			$sql = "SELECT ls.idlicencia,ls.idtipolicencia,ls.nombre nombreLic,fechaRegistro
				,fechaCaducidad,observaciones,ls.idCliente, serie, tl.nombre nombreTipoLic, c.nombre nombreCte
				,(SELECT COUNT(idLicencia) FROM licenciasasignadas la WHERE la.idlicencia = ls.idlicencia) instancias
				FROM licenciasDeSoftware ls
				JOIN tipoDeLicencia tl ON tl.idtipolicencia = ls.idtipolicencia
				LEFT JOIN clientes c ON c.idCliente = ls.idCliente
				ORDER BY idlicencia DESC";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		public function listaCteConLicencias() {
			$sql = "SELECT DISTINCT(c.nombre) FROM licenciasdesoftware ls
				JOIN clientes c ON c.idCliente = ls.idCliente
				ORDER BY c.nombre ASC";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		public function listaDeOffice() //Obtener todas las licencias de office
		{
			try
			{
				$fechaActual = date("Y-m-d");
				//$sql = "SELECT ls.idlicencia, CONCAT('Caduca: ', ls.fechaCaducidad, ', Cte: ', c.nombre, ', Descripción: ', ls.nombre) descripcion
				//$sql = "SELECT ls.idlicencia, CONCAT(tl.nombre, ': ', ls.nombre, ', Caduca: ', ls.fechaCaducidad, ', Cte: ', c.nombre) descripcion
				$sql = "SELECT ls.idlicencia, CONCAT('Caduca: ', ls.fechaCaducidad, ', Cte: ', c.nombre, ', Desc: ', ls.nombre) descripcion
					FROM licenciasDeSoftware ls
					JOIN tipoDeLicencia tl ON tl.idtipolicencia = ls.idtipolicencia
					LEFT JOIN clientes c ON c.idCliente = ls.idCliente
					WHERE ls.idtipolicencia = 3 AND ls.fechaCaducidad >= '$fechaActual'
					ORDER BY c.nombre, ls.fechaCaducidad ASC";
				$data = $this->bd->consulta($sql);
				return $data;
			}
			catch (Exception $e)
			{
				echo "<script>alert(Error caught: " . $e->getMessage() . ")</script>";
			}
		}
		
		public function listaDeAV() //Obtener todas las licencias de antivirus
		{
			$fechaActual = date("Y-m-d");
			//$sql = "SELECT ls.idlicencia, CONCAT(tl.nombre, ': ', ls.nombre, ', Caduca: ', ls.fechaCaducidad, ', Cte: ', c.nombre) descripcion
			//$sql = "SELECT ls.idlicencia, CONCAT('Caduca: ', ls.fechaCaducidad, ', Cte: ', c.nombre, ', Desc: ', ls.nombre) descripcion
			$sql = "SELECT ls.idlicencia, CONCAT('Caduca: ', ls.fechaCaducidad, ', Cte: ', c.nombre, ', Desc: ', ls.nombre) descripcion
				FROM licenciasDeSoftware ls
				JOIN tipoDeLicencia tl ON tl.idtipolicencia = ls.idtipolicencia
				LEFT JOIN clientes c ON c.idCliente = ls.idCliente
				WHERE ls.idtipolicencia = 1 AND fechaCaducidad >= '$fechaActual'
				ORDER BY c.nombre, ls.fechaCaducidad ASC";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		public function dispAsignados($idLic) {
			$sql = "SELECT la.idInventario,la.idAsignacion,i.idInventario,i.idCliente,i.idTipoDeDispositivo,i.SO,i.Host,i.MAC
				,i.HD,i.CPU,i.RAM,i.Fabricante,i.Observaciones,i.fecharegistro, i.activo, i.idsucursalcte
				,c.nombre nombreCte, s.nombre nombreSucCte
				FROM licenciasasignadas la
				LEFT JOIN inventarios i ON i.idInventario = la.idInventario
				JOIN clientes c ON c.idCliente = i.idCliente
				JOIN sucursalcte s ON s.idSucursalCte = i.idsucursalcte
				WHERE la.idLicencia = $idLic
				ORDER BY c.nombre ASC, nombreSucCte ASC;";
			$query = $this->bd->consulta($sql);
			$data = array();
			while($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $row;
			}
			return $data;
		}
		
		public function listLicToAssign() {
			$sql = "SELECT idlicencia, CONCAT(tl.nombre, ': ', ls.nombre, ', Caduca: ', ls.fechaCaducidad) descripcion 
				FROM licenciasdesoftware ls
				JOIN tipoDeLicencia tl ON tl.idtipolicencia = ls.idtipolicencia
				ORDER BY tl.nombre ASC";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		public function listaCtesParaLic() {
			$sql = "SELECT idCliente, nombre FROM clientes ORDER BY nombre ASC";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		public function listaTipoLic() {
			$sql = "SELECT * FROM tipoDeLicencia";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		public function licCaducadasDetalle($idCte,$ordenPor) {
			$sql = "SELECT * FROM licenciasDeSoftware WHERE fechaCaducidad < CURRENT_DATE() AND idtipolicencia = 1";
			$query = $this->bd->consulta($sql);
			$licenciasAV = array();
			while($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$licenciasAV[] = $row;
			}
			
			$sql = "SELECT * FROM licenciasDeSoftware WHERE fechaCaducidad < CURRENT_DATE() AND idtipolicencia = 1";
			$query = $this->bd->consulta($sql);
			$licenciasOffice = array();
			while($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$licenciasOffice[] = $row;
			}
			
			echo "<pre>";
			print_r($licenciasAV);
			echo "</pre>";
		}
		
		public function guardarNvaLic() //Guarda un nuevo registro de licencia
		{
			$sql = "SELECT * FROM licenciasDeSoftware WHERE fechaCaducidad = '$this->fechaCaducidad'
				AND idCliente = '$this->idCliente' AND nombre = '$this->nombre'";
			$contador = $this->bd->consulta($sql)->rowCount();
			if ($contador > 0) {
				$this->msj = "Ya existe una licencia con el mismo nombre y fecha de caducidad en el cliente selecionado.";
				return false;
			} else {
				$sql = "INSERT INTO licenciasDeSoftware (idtipolicencia,nombre,fechaRegistro,fechaCaducidad
				,observaciones,idCliente,serie) VALUES ('$this->idtipolicencia','$this->nombre','$this->fechaRegistro'
				,'$this->fechaCaducidad','$this->observaciones','$this->idCliente','$this->serie')";
				$this->bd->consulta($sql);
			}
		}
		
		public function guardarLic($id) //Edita un registro de licencia
		{
			$sql = "SELECT * FROM licenciasDeSoftware WHERE fechaCaducidad = '$this->fechaCaducidad'
				AND idCliente = '$this->idCliente' AND nombre = '$this->nombre' AND idtipolicencia = $id";
			$contador = $this->bd->consulta($sql)->rowCount();
			echo $sql;
			if ($contador > 0) {
				$this->msj = "Ya existe una licencia con el mismo nombre y fecha de caducidad en el cliente selecionado.";
				return false;
			} else {
				$sql = "UPDATE licenciasDeSoftware SET idtipolicencia = '$this->idtipolicencia', nombre = '$this->nombre'
					,fechaRegistro = '$this->fechaRegistro', fechaCaducidad = '$this->fechaCaducidad'
					,observaciones = '$this->observaciones', idCliente = '$this->idCliente', serie = '$this->serie'
					WHERE idlicencia = $id";
				$this->bd->consulta($sql);
				echo $sql;
			}
		}
		
		public function borrarLic($id) { //Borra el registro de la licencia
			$sql = "SELECT * FROM licenciasasignadas WHERE idLicencia = $id";
			$contador = $this->bd->consulta($sql)->rowCount();
			if ($contador > 0) {
				$this->msj = "No se puede borrar una licencia de software relacionada con un dispositivo.";
				return false;
			} else {
				$sql = "DELETE FROM licenciasDeSoftware WHERE idlicencia = $id";
				$this->bd->consulta($sql);
			}
		}
		
		#Metodos GETs
		public function getMsj(){ return $this->msj; }
		
		#Metodos SETs
		public function setIdTipoLicencia($str){ $this->idtipolicencia = $str; }
		public function setNombre($str){ $this->nombre = $str; }
		public function setFechaRegistro($str){ $this->fechaRegistro = $str; }
		public function setFechaCaducidad($str){ $this->fechaCaducidad = $str; }
		public function setObservaciones($str){ $this->observaciones = $str; }
		// public function setCveCte($str){ $this->cveCte = $str; }
		public function setIdCliente($str){ $this->idCliente = $str; }
		public function setSerie($str){ $this->serie = $str; }
	}
?>

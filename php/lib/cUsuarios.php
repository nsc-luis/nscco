<?php	
	include_once ("cConectaBD.php");
	
	class Usuarios
	{
		#Declaracion de variables
		private $idUsuario;
		private $nombre;
		private $usuario;
		private $password;
		private $emailUsr;
		public $bd;
		private $resultado;
		private $msj = "";
		
		#Metodos de la clase Usuario
		public function __construct($bd){
			$this->bd = new ConectaBD($bd);
			return $this->bd;
		}
		function getInfoUsr($str) //Obtener datos del Usuario segun ID
		{
			$sql = "SELECT * FROM principalbd.usuarios 
				WHERE usuario = '$str'";
			$data = $this->bd->consulta($sql)->fetch();
			$this->idUsuario = $data['idUsuario'];
			$this->nombre = $data['nombre'];
			$this->usuario = $data['usuario'];
			$this->password = $data['password'];
			$this->emailUsr = $data['emailUsr'];
		}
		public function usuarios($str) //Muesta todos los Usuario
		{
			if ($str == 'ADMIN' || $str == 'JPALACIOS'){ $str = "%"; }
			$sql = "SELECT * FROM principalbd.usuarios
				WHERE usuario LIKE '$str'";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		function insertaUsuario() //Inserta un nuevo registro en tabla usuarios
		{
			$sql = "SELECT * FROM principalbd.usuarios WHERE usuario = '$this->usuario'";
			$contador = $this->bd->consulta($sql)->rowCount();
			if ($contador > 0) {
				$this->msj = "El usuario ya existe, intente otro.";
			} else {
				$sql = "INSERT INTO principalbd.usuarios (nombre, usuario, password, emailUsr) 
					VALUES ('$this->nombre','$this->usuario','$this->password','$this->emailUsr')";
				$this->bd->consulta($sql);
			}
		}
		function actualizaUsuario($str) //Actualiza un registro en tabla usuarios
		{
			$sql = "UPDATE principalbd.usuarios SET nombre = '$this->nombre', 
				password = '$this->password', emailUsr = '$this->emailUsr'
				WHERE usuario = '$str'";
			$this->bd->consulta($sql);
		}
		function borraUsuario($usr,$currentUsr) //Borra un registro en tabla USERINFO
		{
			$sql = "SELECT * FROM movimientos WHERE usuario = '$usr'";
			$contador = $this->bd->consulta($sql)->rowCount();
			$contador2 = $usr == $currentUsr ? 1 : 0;
			if ($contador > 0 || $contador2 > 0) {
				$this->msj = "El usuario esta activo ó tiene movimientos y no puede ser eliminado.";
			} else {
				$sql = "DELETE FROM principalbd.usuarios WHERE usuario = '$usr'";
				$this->bd->consulta($sql);
			}
		}
		
		#Metodos GETs
		public function getIdUsuario(){ return $this->idUsuario; }
		public function getUsuario(){ return $this->usuario; }
		public function getNombre(){ return $this->nombre; }
		public function getPassword(){ return $this->password; }
		public function getEmailUsr(){ return $this->emailUsr; }
		public function getResultado(){ return $this->resultado; }
		public function getMsj(){ return $this->msj; }
		
		#Metodos SETs
		public function setUsuario($str){ $this->usuario = $str; }
		public function setNombre($str){ $this->nombre = $str; }
		public function setPassword($str){ $this->password = $str; }
		public function setEmailUsr($str){ $this->emailUsr = $str; }
	}
?>

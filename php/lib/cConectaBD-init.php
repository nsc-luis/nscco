<?php
	/* CLASE PARA LA CONEXION DE PHP CON MYSQL */
	class ConectaBD {
		#Declaracion de variables
		private $bdHost = "[HOST]";
		private $puerto = "[PORT]"; #3306
		private $bdUsr = "[USER]";
		private $bdPass = "[PASSWORD]";
		private $conexion;
		
		# Metodos de la clase conectaDB
		public function __construct($db)
		{
			try {
				$this->conexion = new PDO("mysql:host=$this->bdHost;dbname=$db", $this->bdUsr, $this->bdPass);
				$this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->conexion->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
			}
			catch (PDOException $e){
				print "Error!: " . $e->getMessage();
			}
		}
		public function consulta($str) {
			$query = $this->conexion->query($str);
			return $query;
		}
		public function idUltimoRegistro() {
			return $this->conexion->lastInsertId();
		}
		public function cerrarBD()
		{
			$this->conexion = null;
		}
		
		# GET's
		public function getConexion() { return $this->conexion; }
	}//fin clase	
?>

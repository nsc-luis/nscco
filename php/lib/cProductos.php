<?php
	include_once ("cConectaBD.php");
	
	class Productos {
		# Declaracion de variables
		private $clave;
		private $nombre;
		private $descripcion;
		private $precio;
		private $idUnidad;
		private $idImpuesto;
		private $medida;
		private $proveedor;
		private $skuProveedor;
		public $bd;
		private $nombreBD;
		private $resultado;
		private $msj;
		
		#Metodos
		public function __construct($bd){
			$this->bd = new ConectaBD($bd);
			return $this->bd;
		}
		public function productos(){
			$sql = "SELECT p.idProducto, p.clave, p.nombre, p.descripcion, p.precio, p.proveedor, p.skuProveedor, p.idUnidad, u.medida 
				FROM productos p
				JOIN unidades u ON u.idUnidad = p.idUnidad
				ORDER BY p.nombre";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		public function getInfoProd($str){
			$sql = "SELECT idProducto,clave,nombre,descripcion,medida,precio as precioUnitario,proveedor,skuProveedor,productos.idUnidad,idImpuesto FROM productos 
				INNER JOIN unidades ON productos.idUnidad = unidades.idUnidad
				WHERE clave = '$str'";
			$datos = $this->bd->consulta($sql)->fetch();
			$this->clave = $datos['clave'];
			$this->nombre = $datos['nombre'];
			$this->descripcion = $datos['descripcion'];
			$this->precio = $datos['precioUnitario'];
			$this->medida = $datos['medida'];
			$this->idUnidad = $datos['idUnidad'];
			$this->idImpuesto = $datos['idImpuesto'];
			$this->proveedor = $datos['proveedor'];
			$this->skuProveedor = $datos['skuProveedor'];
			$data = $this->bd->consulta($sql);
			return $data;
		}
		public function getInfoProdBuscado($str){
			$sql = "SELECT idProducto,clave,nombre,descripcion,precio as precioUnitario,proveedor,skuProveedor,productos.idUnidad,idImpuesto FROM productos 
				INNER JOIN unidades ON productos.idUnidad = unidades.idUnidad
				WHERE clave LIKE '%$str%' OR nombre LIKE '%$str%'";
			$data = $this->bd->consulta($sql);
			// echo $sql;
			return $data;
		}
		public function editaProducto($str){
			$sql = "UPDATE productos SET nombre = '$this->nombre'
				,descripcion = '$this->descripcion'
				,precio = '$this->precio'
				,idUnidad = '$this->idUnidad'
				,idImpuesto = '$this->idImpuesto'
				,proveedor = '$this->proveedor'
				,skuProveedor = '$this->skuProveedor'
				WHERE clave = '$str'";
			$this->bd->consulta($sql);
		}
		public function borraProducto($str){
			$sql = "SELECT * FROM movimientos WHERE cveProd = '$str'";
			$contador = $this->bd->consulta($sql)->rowCount();
			if ($contador > 0) {
				$this->msj = "No se puede eliminar el producto porque tiene movimientos.";
			} else {
				$sql = "DELETE FROM productos WHERE clave = '$str'";
				$this->bd->consulta($sql);
			}
		}
		public function nuevoProducto(){
			$sql = "SELECT * FROM productos WHERE clave = '$this->clave'";
			$contador = $this->bd->consulta($sql)->rowCount();
			//echo $contador;
			if ($contador > 0) {
				$this->msj = "La clave del producto ya existe, intente otra diferente.";
			} else {
				$sql = "INSERT INTO productos (clave,nombre,descripcion,
				precio,idUnidad,idImpuesto,proveedor,skuProveedor) 
				VALUES ('" . strtoupper($this->clave) . "'
				,'$this->nombre'
				,'$this->descripcion'
				,'$this->precio'
				,'$this->idUnidad'
				,'$this->idImpuesto'
				,'$this->proveedor'
				,'$this->skuProveedor')";
				$this->bd->consulta($sql);
				//echo $sql;
			}
		}

		#Metodos para unidades 
		public function unidades(){
			$sql = "SELECT * FROM unidades";
			$data = $this->bd->consulta($sql);
			return $data;
		}
		
		# GET´s
		public function getClave(){ return $this->clave; }
		public function getNombre(){ return $this->nombre; }
		public function getDescripcion(){ return $this->descripcion; }
		public function getPrecio(){ return $this->precio; }
		public function getIdUnidad(){ return $this->idUnidad; }
		public function getMedida(){ return $this->medida; }
		public function getIdImpuesto(){ return $this->idImpuesto; }
		public function getProveedor(){ return $this->proveedor; }
		public function getSkuProveedor(){ return $this->skuProveedor; }
		public function getMsj(){ return $this->msj; }
		public function getResultado(){ return $this->resultado; }
		
		# SET´s
		public function setClave($str){ $this->clave = $str; }
		public function setNombre($str){ $this->nombre = $str; }
		public function setDescripcion($str){ $this->descripcion = $str; }
		public function setPrecio($str){ $this->precio = $str; }
		public function setIdUnidad($str){ $this->idUnidad = $str; }
		public function setMedida($str){ $this->medida = $str; }
		public function setIdImpuesto($str){ $this->idImpuesto = $str; }
		public function setProveedor($str){ $this->proveedor = $str; }
		public function setSkuProveedor($str){ $this->skuProveedor = $str; }
	}
?>
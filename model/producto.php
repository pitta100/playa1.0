<?php
class producto
{
	private $pdo;

public $id;
public $codigo;
public $id_categoria;
public $producto;
public $marca;
public $modelo;  // Campo que faltaba
public $anio;    // Campo que faltaba
public $version; // Campo que faltaba
public $color;   // Campo que faltaba
public $puertas; // Campo que faltaba
public $combustible; // Campo que faltaba
public $transmision; // Campo que faltaba
public $traccion;    // Campo que faltaba
public $placa;       // Campo que faltaba
public $tipo_vehiculo; // Campo que faltaba
public $vin;         // Campo que faltaba
public $motor;       // Campo que faltaba
public $kilometraje; // Campo que faltaba
public $importado;   // Campo que faltaba
public $pais_origen; // Campo que faltaba
public $fecha_importacion; // Campo que faltaba
public $usado;       // Campo que faltaba
public $dueno_anterior; // Campo que faltaba
public $cedula_rif;  // Campo que faltaba
public $descripcion; // Campo que faltaba
public $precio_costo; // Campo que faltaba
public $precio_minorista; // Campo que faltaba
public $precio_mayorista; // Campo que faltaba
public $stock;       // Campo que faltaba
public $stock_minimo; // Campo que faltaba
public $descuento_max; // Campo que faltaba
public $iva;          // Campo que faltaba
public $sucursal;     // Campo que faltaba
public $anulado;      // Campo que faltaba

// Documentos adjuntos
public $titulo_propiedad;
public $factura_original;
public $revision_tecnica;
public $permiso_circulacion;



	public function __CONSTRUCT()
	{
		try
		{
			$this->pdo = Database::StartUp();     
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Listar()
	{
		try
		{
			$result = array();
            if(!isset($_SESSION['nivel'])){
                session_start();
            }
            if(true){
                $sucursal = "WHERE p.sucursal = ".$_SESSION['sucursal'];
            }else{
                $sucursal = "";
            }
            
            
			$stm = $this->pdo->prepare("SELECT *, p.id, s.sucursal FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id
			      LEFT JOIN sucursales s ON p.sucursal = s.id 
			      JOIN marcas m ON m.id = p.marca  
			      WHERE p.anulado IS NULL
			      ORDER BY CAST(p.codigo AS INT) ASC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarAjax()
	{
		try
		{
            
			$stm = $this->pdo->prepare("SELECT *, p.id, s.sucursal, sub.categoria AS sub_categoria, sub.id_padre FROM productos p LEFT JOIN categorias sub ON p.id_categoria = sub.id LEFT JOIN categorias c ON sub.id_padre = c.id LEFT JOIN sucursales s ON p.sucursal = s.id WHERE p.anulado IS NULL ORDER BY CAST(p.codigo AS INT) ASC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function ObtenerUltimaVenta($id_producto)
	{
	    try
	    {
	        $stm = $this->pdo->prepare("
	            SELECT v.fecha_venta, v.contado, c.nombre, c.ruc, c.telefono, c.correo, c.adressWork, c.phoneWork
	            FROM ventas v
	            LEFT JOIN clientes c ON v.id_cliente = c.id
	            WHERE v.id_producto = ?
	            ORDER BY v.fecha_venta DESC
	            LIMIT 1
	        ");
	        
	        $stm->execute([$id_producto]);

	        return $stm->fetch(PDO::FETCH_OBJ);
	    }
	    catch(Exception $e)
	    {
	        die($e->getMessage());
	    }
	}


	public function ListartodoProductos()
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM productos ORDER BY id DESC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	


	public function ListarVenta($id_venta)
	{
		try
		{
            
			$stm = $this->pdo->prepare("SELECT * FROM productos WHERE id IN (SELECT id_producto FROM ventas WHERE id_venta = ?) AND id NOT IN (SELECT id_producto FROM devoluciones_tmp) ORDER BY id DESC");
			$stm->execute(array($id_venta));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarBuscar($q)
	{
		try
		{
			$result = array();
            if(!isset($_SESSION['nivel'])){
                session_start();
            }
            if($_SESSION['nivel']!=1 ){
                $sucursal = "WHERE p.sucursal = ".$_SESSION['sucursal'];
            }else{
                $sucursal = "";
            }
            
            if($q != ""){
                $sucursal = "AND p.sucursal = ".$q;
            }else{
                $sucursal = "";
            }
            
			$stm = $this->pdo->prepare("SELECT *, p.id, s.sucursal FROM productos p JOIN categorias c ON p.id_categoria = c.id LEFT JOIN sucursales s ON p.sucursal = s.id JOIN marcas m ON m.id = p.marca $sucursal  ORDER BY p.id DESC LIMIT 50");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarTodo()
	{
		try
		{
			$result = array();
          
			$stm = $this->pdo->prepare("SELECT *, p.id, s.sucursal, p.sucursal as id_sucursal FROM productos p JOIN categorias c ON p.id_categoria = c.id LEFT JOIN sucursales s ON p.sucursal = s.id JOIN marcas m ON m.id = p.marca ORDER BY p.id DESC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function Buscar($q)
	{
		try
		{
			 
			$q = '%'.$q.'%';
			$stm = $this->pdo->prepare("SELECT *, (SELECT imagen FROM imagenes WHERE id_producto = p.id limit 1) as imagen FROM productos p WHERE producto LIKE ? ORDER BY id DESC");

			$stm->execute(array($q));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	

	public function Obtener($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT *, (SELECT categoria FROM categorias c WHERE c.id= p.id_categoria) AS categoria FROM productos p WHERE p.id = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function ObtenerLimpio($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM productos p WHERE p.id = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Codigo($codigo)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT *, (SELECT categoria FROM categorias c WHERE c.id= p.id_categoria) AS categoria FROM productos p WHERE p.codigo = ?");
			          

			$stm->execute(array($codigo));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}


	public function Ultimo()
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT MAX(id) as id FROM productos LIMIT 1");
			          

			$stm->execute();
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Eliminar($id)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET anulado = 1 WHERE id = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Restar($data)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function ObtenerSucursal($codigo, $sucursal)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT *, (SELECT categoria FROM categorias c WHERE c.id= p.id_categoria) AS categoria FROM productos p WHERE p.codigo = ? AND sucursal = ?");
			          

			$stm->execute(array($codigo, $sucursal));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
public function RestarId($id_producto, $cantidad)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");			          

			$stm->execute(array($cantidad, $id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function SumarId($id_producto, $cantidad)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");			          

			$stm->execute(array($cantidad, $id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
public function Insertar($data)
	{
		try 
		{
		$sql = "INSERT INTO productos (codigo, id_categoria, producto, marca, descripcion, precio_costo, precio_minorista, precio_mayorista, stock, stock_minimo, descuento_max, importado, iva, sucursal, anulado) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
					$data->codigo,
					$data->id_categoria,
				    $data->producto,
				    $data->marca, 
                    $data->descripcion,
                    $data->precio_costo,                        
                    $data->precio_minorista,
                    $data->precio_mayorista,
                    $data->stock,
                    $data->stock_minimo,
                    $data->descuento_max,
                    $data->importado,
                    $data->iva,
                    $data->sucursal,
                    $data->anulado
                )
			);
		return "Agregado";
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Compra($data)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET stock = stock + ?, precio_costo = ?, precio_minorista = ?, precio_mayorista = ? WHERE id = ?");			          

			$stm->execute(array(
				$data->cantidad,
				$data->precio_compra,
				$data->precio_min,
				$data->precio_may,
				$data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Sumar($data)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET stock = stock + ? WHERE codigo = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function SumarProducto($data)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function Actualizar($data)
	{
	    try 
	    {
	        $sql = "UPDATE productos SET 
	                    codigo               = ?,
	                    id_categoria         = ?,
	                    producto             = ?,
	                    marca                = ?,
	                    marcaVehiculo        = ?,
	                    modelo               = ?,
	                    anio                 = ?,
	                    version              = ?,
	                    color                = ?,
	                    puertas              = ?,
	                    combustible          = ?,
	                    transmision          = ?,
	                    traccion             = ?,
	                    placa                = ?,
	                    tipo_vehiculo        = ?,
	                    vin                  = ?,
	                    motor                = ?,
	                    kilometraje          = ?,
	                    importado            = ?,
	                    pais_origen          = ?,
	                    fecha_importacion    = ?,
	                    usado                = ?,
	                    dueno_anterior       = ?,
	                    cedula_rif           = ?,
	                    descripcion          = ?,
	                    precio_costo         = ?,
	                    precio_minorista     = ?,
	                    precio_mayorista     = ?,
	                    precio_financiado    = ?,
	                    entrega_minima       = ?,
	                    cuotas_minimas       = ?,
	                    cant_refuerzo		 = ?,		
	                    monto_minimo_refuerzo = ?,
	                    stock                = ?,
	                    stock_minimo         = ?,
	                    descuento_max        = ?,
	                    iva                  = ?,
	                    sucursal             = ?,
	                    titulo_propiedad     = ?,
	                    factura_original     = ?,
	                    revision_tecnica     = ?,
	                    permiso_circulacion  = ?,
	                    anulado              = ?
	                WHERE id = ?";

	        $this->pdo->prepare($sql)
	            ->execute(
	                array(
	                    $data->codigo,
	                    $data->id_categoria,
	                    $data->producto,
	                    $data->marca,
	                    $data->marcaVehiculo,
	                    $data->modelo,
	                    $data->anio,
	                    $data->version,
	                    $data->color,
	                    $data->puertas,
	                    $data->combustible,
	                    $data->transmision,
	                    $data->traccion,
	                    $data->placa,
	                    $data->tipo_vehiculo,
	                    $data->vin,
	                    $data->motor,
	                    $data->kilometraje,
	                    $data->importado,
	                    $data->pais_origen,
	                    $data->fecha_importacion,
	                    $data->usado,
	                    $data->dueno_anterior,
	                    $data->cedula_rif,
	                    $data->descripcion,
	                    $data->precio_costo,
	                    $data->precio_minorista,
	                    $data->precio_mayorista,
	                    $data->precio_financiado,
	                    $data->entrega_minima,
	                    $data->cuotas_minimas,
	                    $data->cant_refuerzo,
	                    $data->monto_minimo_refuerzo,
	                    $data->stock,
	                    $data->stock_minimo,
	                    $data->descuento_max,
	                    $data->iva,
	                    $data->sucursal,
	                    $data->titulo_propiedad,
	                    $data->factura_original,
	                    $data->revision_tecnica,
	                    $data->permiso_circulacion,
	                    $data->anulado,
	                    $data->id
	                )
	            );
	        return "Modificado";
	    } catch (Exception $e) {
	        die($e->getMessage());
	    }
	}


	public function Registrar(producto $data)
{
    try 
    {
        $sql = "INSERT INTO productos (
	    codigo, id_categoria, producto, marca, marcaVehiculo, modelo, anio, version, color, puertas, 
	    combustible, transmision, traccion, placa, tipo_vehiculo, vin, motor, kilometraje,
	    importado, pais_origen, fecha_importacion, usado, dueno_anterior, cedula_rif,
	    descripcion, precio_costo, precio_minorista, precio_mayorista, precio_financiado, entrega_minima, cuotas_minimas,cant_refuerzo, monto_minimo_refuerzo, stock, stock_minimo, descuento_max, iva, sucursal, titulo_propiedad, factura_original, revision_tecnica,
	    permiso_circulacion, anulado
	) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


       $this->pdo->prepare($sql)->execute(array(
		    $data->codigo,
		    $data->id_categoria,
		    $data->producto,
		    $data->marca,
		    $data->marcaVehiculo,
		    $data->modelo,
		    $data->anio,
		    $data->version,
		    $data->color,
		    $data->puertas,
		    $data->combustible,
		    $data->transmision,
		    $data->traccion,
		    $data->placa,
		    $data->tipo_vehiculo,
		    $data->vin,
		    $data->motor,
		    $data->kilometraje,
		    $data->importado,
		    $data->pais_origen,
		    $data->fecha_importacion,
		    $data->usado,
		    $data->dueno_anterior,
		    $data->cedula_rif,
		    $data->descripcion,
		    $data->precio_costo,
		    $data->precio_minorista,
		    $data->precio_mayorista,
		    $data->precio_financiado,
		    $data->entrega_minima,
		    $data->cuotas_minimas,
		    $data->cant_refuerzo,
		    $data->monto_minimo_refuerzo,
		    $data->stock,
		    $data->stock_minimo,
		    $data->descuento_max,
		    $data->iva,
		    $data->sucursal,
		    $data->titulo_propiedad,
		    $data->factura_original,
		    $data->revision_tecnica,
		    $data->permiso_circulacion,
		    $data->anulado
		));


        return "Agregado";
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

}
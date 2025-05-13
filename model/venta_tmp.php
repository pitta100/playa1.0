<?php
class venta_tmp
{
	private $pdo;
    
    public $id;                     // ID de la venta temporal
    public $id_venta;               // ID de la venta
    public $id_vendedor;            // ID del vendedor
    public $id_producto;            // ID del producto
    public $precio_venta;           // Precio de venta
    public $cantidad;               // Cantidad de productos
    public $descuento;              // Descuento aplicado
    public $fecha_venta;            // Fecha de la venta

    // Nuevos campos
    public $entrega;                // Valor de la entrega (monto mínimo)
    public $cuota_vehiculo;         // Cuota de financiación
    public $monto_refuerzo;         // Monto de refuerzo para el producto
    public $cantidad_refuerzo;      // Cantidad de refuerzo (si aplica)
    
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
			if(!isset($_SESSION['user_id'])){
				session_start();
			}
			$userId= $_SESSION['user_id'];
			$result = array();

			$stm = $this->pdo->prepare("SELECT v.id, v.id_producto, c.codigo, v.id_vendedor, v.descuento, c.precio_costo, v.precio_venta, c.producto, c.precio_costo, v.cantidad, v.id_venta FROM ventas_tmp v LEFT JOIN productos c ON v.id_producto = c.id WHERE id_vendedor = ? ORDER BY v.id DESC");
			$stm->execute(array($userId));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

		

	public function Obtener()
	{
		try 
		{
		    session_start();
		    $user_id = $_SESSION['user_id'];
			$stm = $this->pdo
			          ->prepare("SELECT *, SUM((precio_venta*cantidad)-descuento) as monto FROM ventas_tmp  WHERE id_vendedor = '$user_id' GROUP BY id_venta");
			          

			$stm->execute();
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
			          ->prepare("SELECT MAX(id_venta) as id_venta FROM ventas_tmp");
			$stm->execute();
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function ObtenerMoneda()
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM monedas WHERE id = 1");
			          

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
			            ->prepare("DELETE FROM ventas_tmp WHERE id = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Vaciar()
	{
		try 
		{
		    session_start();
		    $id_vendedor = $_SESSION['user_id'];
			$stm = $this->pdo
			            ->prepare("DELETE FROM ventas_tmp WHERE id_vendedor = ? ");
			$stm->execute(array($id_vendedor));			          

		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Actualizar($data)
	{
	    try 
	    {
	        // Modificar la consulta SQL para actualizar los nuevos campos
	        $sql = "UPDATE ventas_tmp SET
	                    id_venta         = ?,
	                    id_vendedor      = ?,
	                    id_producto      = ?,
	                    precio_venta     = ?,
	                    cantidad         = ?,
	                    descuento        = ?,
	                    fecha_venta      = ?,
	                    entrega          = ?,           // Nuevo campo
	                    cuota_vehiculo   = ?,           // Nuevo campo
	                    monto_refuerzo   = ?,           // Nuevo campo
	                    cantidad_refuerzo = ?          // Nuevo campo
	                WHERE id = ?";

	        // Preparar y ejecutar la consulta, pasando todos los valores
	        $this->pdo->prepare($sql)
	            ->execute(
	                array(
	                    $data->id_venta,               // ID de la venta
	                    $data->id_vendedor,            // ID del vendedor
	                    $data->id_producto,            // ID del producto
	                    $data->precio_venta,           // Precio de venta
	                    $data->cantidad,               // Cantidad de productos
	                    $data->descuento,              // Descuento
	                    $data->fecha_venta,            // Fecha de la venta
	                    $data->entrega,                // Valor de entrega
	                    $data->cuota_vehiculo,         // Cuota de financiación
	                    $data->monto_refuerzo,         // Monto de refuerzo
	                    $data->cantidad_refuerzo,      // Cantidad de refuerzo
	                    $data->id                      // ID del registro a actualizar
	                )
	            );
	    } catch (Exception $e) 
	    {
	        // Si hay un error, muestra el mensaje de la excepción
	        die($e->getMessage());
	    }
	}


	public function Moneda($data)
	{
		try 
		{
			$sql = "UPDATE monedas SET
						reales     = ?,
						dolares     = ?,
						monto_inicial = ?
						";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $data->reales,
                        $data->dolares,
                        $data->monto_inicial
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Registrar($data)
	{
	    try 
	    {
	        // Modificar la consulta SQL para insertar los nuevos campos
	        $sql = "INSERT INTO ventas_tmp (id_venta, id_vendedor, id_producto, precio_venta, cantidad, descuento, fecha_venta, entrega, cuota_vehiculo, monto_refuerzo, cantidad_refuerzo) 
	                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	        // Preparar y ejecutar la consulta, pasando todos los valores incluyendo los nuevos campos
	        $this->pdo->prepare($sql)
	             ->execute(
	                array(
	                    $data->id_venta,                   // ID de la venta
	                    $data->id_vendedor,                // ID del vendedor
	                    $data->id_producto,                // ID del producto
	                    $data->precio_venta,               // Precio de venta
	                    $data->cantidad,                   // Cantidad de productos
	                    $data->descuento,                  // Descuento
	                    $data->fecha_venta,                // Fecha de la venta
	                    $data->entrega,                    // Valor de entrega
	                    $data->cuota_vehiculo,             // Cuota de financiación
	                    $data->monto_refuerzo,             // Monto de refuerzo
	                    $data->cantidad_refuerzo           // Cantidad de refuerzo
	                )
	            );
	    } catch (Exception $e) 
	    {
	        // Si hay un error, muestra el mensaje de la excepción
	        die($e->getMessage());
	    }
	}
	public function ObtenerDatosParcialesVenta()
	{
	    try 
	    {
	        session_start();
	        $user_id = $_SESSION['user_id'];

	        // Obtener el último registro de ventas_tmp para ese vendedor
	        $stm = $this->pdo->prepare("
	            SELECT entrega, cuota_vehiculo, monto_refuerzo, cantidad_refuerzo 
	            FROM ventas_tmp 
	            WHERE id_vendedor = ? 
	            ORDER BY id DESC 
	            LIMIT 1
	        ");

	        $stm->execute([$user_id]);
	        return $stm->fetch(PDO::FETCH_OBJ);

	    } catch (Exception $e) {
	        die($e->getMessage());
	    }
	}


}
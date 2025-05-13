<?php
class ingreso
{
	private $pdo;
    
    public $id;
    public $id_cliente;
    public $id_usuario;
    public $id_caja;
    public $id_venta;
    public $id_deuda;
    public $fecha;
    public $categoria;
    public $concepto;
    public $comprobante;
    public $monto;
    public $forma_pago;  
    public $sucursal;
    public $anulado;
    public $id_gift;
    public $cuotas;

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

	public function Listar($fecha)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, i.id as id FROM ingresos i LEFT JOIN clientes c ON i.id_cliente = c.id WHERE cast(i.fecha as date) = ? ORDER BY i.id DESC");
			$stm->execute(array($fecha));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	

	public function ListarVenta($id)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, (SELECT v.nro_comprobante FROM ventas v WHERE v.id_venta = i.id_venta LIMIT 1) as nro_comprobante, (SELECT v.banco FROM ventas v WHERE v.id_venta = i.id_venta LIMIT 1) as banco, i.id as id FROM ingresos i LEFT JOIN clientes c ON i.id_cliente = c.id WHERE i.id = ? ORDER BY i.id DESC");
			$stm->execute(array($id));

			return $stm->fetch(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function Listartodoingresos()
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM ingresos ORDER BY id DESC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	
	public function MiLista()
	{
		try
		{
			session_start();

			$stm = $this->pdo->prepare("SELECT *, i.id as id FROM ingresos i LEFT JOIN clientes c ON i.id_cliente = c.id
			    WHERE id_usuario = ? AND anulado IS NULL
			    ORDER BY i.id DESC");
			$stm->execute(array($_SESSION['user_id']));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function MiListaAnulados()
	{
		try
		{
			session_start();

			$stm = $this->pdo->prepare("SELECT *, i.id as id FROM ingresos i LEFT JOIN clientes c ON i.id_cliente = c.id
			    WHERE id_usuario = ? AND anulado = 1
			    ORDER BY i.id DESC");
			$stm->execute(array($_SESSION['user_id']));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarSinAnular()
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, i.id as id FROM ingresos i LEFT JOIN clientes c ON i.id_cliente = c.id WHERE anulado IS NULL ORDER BY i.id DESC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function ListarDeuda($id_deuda)
	{
		try
		{
			$result = array();
			$stm = $this->pdo->prepare("SELECT *, i.id as id FROM ingresos i LEFT JOIN clientes c ON i.id_cliente = c.id WHERE i.id_deuda = ? AND anulado IS NULL ORDER BY i.id DESC");
			$stm->execute(array($id_deuda));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarMes($fecha)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM ingresos WHERE MONTH(fecha) = MONTH(?) AND YEAR(fecha) = YEAR(?) AND categoria <> 'Venta' ");
			          

			$stm->execute(array($fecha, $fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function AgrupadoMes($fecha)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT categoria, SUM(monto) as monto FROM ingresos WHERE MONTH(fecha) = MONTH(?) AND YEAR(fecha) = YEAR(?) AND anulado IS NULL AND categoria <> 'Transferencia' AND categoria <> 'Venta por gift card' GROUP BY categoria ORDER BY id DESC");
			          

			$stm->execute(array($fecha, $fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function Listar_rango($desde,$hasta)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, i.id as id FROM ingresos i LEFT JOIN clientes c ON i.id_cliente = c.id WHERE cast(fecha as date) >= ? AND cast(fecha as date) <= ? ORDER BY i.id DESC");
			$stm->execute(array($desde,$hasta));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarRangoSesion($desde,$hasta,$id_usuario)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, i.id as id FROM ingresos i LEFT JOIN clientes c ON i.id_cliente = c.id WHERE fecha >= ? AND fecha <= ? AND id_usuario = ? AND id_venta = 0 ORDER BY i.id DESC");
			$stm->execute(array($desde,$hasta,$id_usuario));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarSinVenta($fecha)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM ingresos WHERE categoria <> 'Venta' AND Cast(fecha as date) = ? AND anulado IS NULL ORDER BY id DESC");
			$stm->execute(array($fecha));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarSesion($id_usuario)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM ingresos WHERE categoria <> 'Venta' AND fecha >= (SELECT fecha_apertura FROM cierres WHERE id_usuario = ? AND fecha_cierre IS NULL) ORDER BY id DESC");
			$stm->execute(array($id_usuario));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarSinCompraMes($desde, $hasta)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, (SELECT ( ( (SUM(precio_venta*cantidad)-SUM(precio_costo*cantidad)) * 100 )/ SUM(precio_venta*cantidad)) AS ganancia FROM ventas v WHERE v.id_venta = i.id_venta GROUP BY id_venta) AS margen_ganancia FROM ingresos i WHERE id_deuda IS NOT NULL AND CAST(fecha AS date) >= ? AND CAST(fecha AS date) <= ? AND anulado IS NULL ORDER BY id ASC");
			$stm->execute(array($desde, $hasta));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function EditarMonto($id_venta, $monto)
	{
		try 
		{
			$sql = "UPDATE ingresos SET 
						monto    = ?
				    WHERE id_venta = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				    	$monto,
                        $id_venta
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Agrupado_ingreso($mes)
	{
		try
		{
			$result = array();
			if($mes!='0'){
				$stm = $this->pdo->prepare("SELECT concepto, categoria, sum(monto) as monto, fecha  FROM ingresos WHERE MONTH(fecha) = $mes AND anulado IS NULL AND categoria <> 'Transferencia' GROUP BY categoria ORDER BY id DESC");
			}else{
				$stm = $this->pdo->prepare("SELECT concepto, categoria, sum(monto) as monto FROM ingresos WHERE anulado IS NULL AND categoria <> 'Transferencia' GROUP BY categoria ORDER BY id DESC");	
			}
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ObtenerIngreso($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM ingresos WHERE id_gift = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
		public function ObtenerGift($id_venta)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT monto FROM ingresos WHERE id_venta = ? AND forma_pago = 'Gift Card'");
			          

			$stm->execute(array($id_venta));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Obtener($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM ingresos WHERE id = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	
	public function UltimoID()
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT id FROM ingresos ORDER BY id desc LIMIT 1");
			          

			$stm->execute(array());
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
			            ->prepare("UPDATE ingresos SET anulado = 1 WHERE id = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function AnularVenta($id)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE ingresos SET anulado = 1 WHERE id_venta = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function AnularGift($id)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE ingresos SET anulado = 1 WHERE id_gift = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Actualizar($data)
	{
		try 
		{
			$sql = "UPDATE ingresos SET 
			            id_cliente     = ?,
			            id_venta       = ?,
			            fecha      	   = ?,
						categoria      = ?,
						concepto       = ?,
						comprobante    = ?, 
						monto          = ?, 
						forma_pago     = ?,
                        sucursal       = ?,
                        id_gift        = ?
						
				    WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				        $data->id_cliente,
				        $data->id_venta,
				    	$data->fecha,
                        $data->categoria, 
                        $data->concepto, 
                        $data->comprobante,                        
                        $data->monto,
                        $data->forma_pago,
                        $data->sucursal,
                        $data->id_gift,
                        $data->id
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
			//session_start();
			if($data->categoria=="Transferencia"){
				$id_usuario = $data->id_usuario;
			}else{
				$id_usuario = $_SESSION['user_id'];
			}
		$sql = "INSERT INTO ingresos (id_cliente, id_usuario, id_caja, id_venta, id_deuda, fecha, categoria, concepto, comprobante, monto, forma_pago, sucursal, id_gift) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
				    $data->id_cliente,
					$id_usuario,
					$data->id_caja,
					$data->id_venta,
					$data->id_deuda,
					$data->fecha,
                	$data->categoria, 
                	$data->concepto, 
                	$data->comprobante,                        
                	$data->monto,
                	$data->forma_pago,
                	$data->sucursal,
                	$data->id_gift
                   
                )
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}
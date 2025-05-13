<?php
class cierre
{
	private $pdo;
    
    public $id;
    public $fecha_apertura;
    public $fecha_cierre;
    public $id_usuario;
    public $id_caja;
    public $monto_apertura;
    public $monto_cierre;
    public $cot_dolar;
    public $cot_real;

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

	public function Listar($desde, $hasta)
	{
		try
		{
			$result = array();
            $rango = ($desde==0)? "":"AND c.fecha_cierre >= '$desde' AND c.fecha_cierre <= '$hasta'";
			$stm = $this->pdo->prepare("SELECT *, c.id as id, (SELECT SUM(i.monto) FROM ingresos i WHERE i.fecha>=c.fecha_apertura AND i.fecha<=c.fecha_cierre AND i.anulado IS NULL AND i.id_usuario = c.id_usuario AND forma_pago = 'Efectivo') AS monto_sistema FROM cierres c LEFT JOIN usuario u ON c.id_usuario = u.id WHERE c.fecha_cierre IS NOT NULL $rango ORDER BY c.id DESC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarActivas()
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, c.id as id FROM cierres c LEFT JOIN usuario u ON c.id_usuario = u.id WHERE fecha_cierre IS NULL ORDER BY c.id DESC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarMovimientosSesion($id_usuario)
	{
		try
		{
			$result = array();
			/*$query = "
				SELECT i.fecha, (SELECT c.caja FROM cajas c WHERE c.id = i.id_caja) AS caja, id_caja, i.categoria, i.concepto, i.comprobante, (i.monto * 1) as monto, i.forma_pago, i.anulado, (SELECT v.descuento FROM ventas v WHERE v.id_venta = i.id_venta LIMIT 1) as descuento FROM ingresos i WHERE i.fecha >= (SELECT c.fecha_apertura FROM cierres c WHERE c.id_usuario = ? AND c.fecha_cierre IS NULL) AND i.id_usuario = ?
				UNION ALL 
				SELECT fecha, (SELECT c.caja FROM cajas c WHERE c.id = egresos.id_caja) AS caja, id_caja, categoria, concepto, comprobante, (monto * -1) as monto, forma_pago, anulado, (SELECT v.descuento FROM ventas v WHERE v.id_venta = id_compra LIMIT 1) as descuento FROM egresos WHERE fecha >= (SELECT fecha_apertura FROM cierres WHERE id_usuario = ? AND fecha_cierre IS NULL) AND id_usuario = ? ORDER BY fecha";*/
			$query = "
			SELECT i.fecha, (SELECT c.caja FROM cajas c WHERE c.id = i.id_caja) AS caja, id_caja, i.categoria, i.concepto, i.comprobante, (i.monto * 1) as monto, i.forma_pago, i.anulado, (SELECT v.descuento FROM ventas v WHERE v.id_venta = i.id_venta LIMIT 1) as descuento FROM ingresos i WHERE i.fecha >= (SELECT c.fecha_apertura FROM cierres c WHERE c.id_usuario = ? AND c.fecha_cierre IS NULL) AND i.id_usuario = ? ORDER BY i.id DESC";
			$stm = $this->pdo->prepare($query);
			$stm->execute(array($id_usuario ,$id_usuario));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarMovimientosSesionCerrada($id_usuario, $apertura, $cierre)
	{
		try
		{
			$result = array();
			/*$query = "
				SELECT i.fecha, i.categoria, i.concepto, i.comprobante, (i.monto * 1) as monto, i.forma_pago, i.anulado, (SELECT v.descuento FROM ventas v WHERE v.id_venta = i.id_venta LIMIT 1) as descuento FROM ingresos i WHERE i.fecha >= ? AND i.fecha <= ? AND i.id_usuario = ?
				UNION ALL 
				SELECT fecha, categoria, concepto, comprobante, (monto * -1) as monto, forma_pago, anulado, (SELECT v.descuento FROM ventas v WHERE v.id_venta = id_compra LIMIT 1) as descuento FROM egresos WHERE fecha >= ? AND fecha <= ? AND id_usuario = ? ORDER BY fecha";*/
			$query = "
				SELECT i.fecha, i.categoria, i.concepto, i.comprobante, (i.monto ) as monto, i.forma_pago, i.anulado, (SELECT v.descuento FROM ventas v WHERE v.id_venta = i.id_venta LIMIT 1) as descuento FROM ingresos i WHERE i.fecha >= ? AND i.fecha <= ? AND i.id_usuario = ? ORDER BY i.id DESC";
			$stm = $this->pdo->prepare($query);
			$stm->execute(array($apertura, $cierre, $id_usuario));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarMovimientosDia($fecha)
	{
		try
		{
			
			$query = "
				SELECT i.fecha, i.categoria, i.concepto, i.comprobante, (i.monto ) as monto, i.forma_pago, i.anulado, (SELECT v.descuento FROM ventas v WHERE v.id_venta = i.id_venta LIMIT 1) as descuento FROM ingresos i WHERE Cast(i.fecha as date) = ? ORDER BY i.id DESC";
			$stm = $this->pdo->prepare($query);
			$stm->execute(array($fecha));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Ultimo()
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, c.id as id FROM cierres c LEFT JOIN usuario u ON c.id_usuario = u.id ORDER BY c.id DESC LIMIT 1");
			$stm->execute();

			return $stm->fetch(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ConsultarCierre($id, $fecha)
	{
		try 
		{
			
			$stm = $this->pdo
			          ->prepare("SELECT * FROM cierres WHERE id_usuario = ? AND fecha_cierre IS NULL AND CAST(fecha_apertura AS date) < ?");
			        
			$stm->execute(array($id, $fecha));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Consultar($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM cierres WHERE id_usuario = ? AND fecha_cierre IS NULL");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function ConsultarUsuario($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM cierres WHERE id_usuario = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}


	public function ListarAcreedor($id_acreedor)
	{
		try
		{
			$result = array();
			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE e.id_acreedor = ? ORDER BY e.id DESC");
			$stm->execute(array($id_acreedor));

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
			          ->prepare("SELECT * FROM egresos WHERE MONTH(fecha) = MONTH(?) AND YEAR(fecha) = YEAR(?) AND categoria <> 'compra' ");
			          

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

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE cast(fecha as date) >= ? AND cast(fecha as date) <= ? ORDER BY e.id DESC");
			$stm->execute(array($desde,$hasta));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
		public function ListarSincompra($fecha)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM egresos WHERE categoria <> 'compra' AND Cast(fecha as date) = ? ORDER BY id DESC");
			$stm->execute(array($fecha));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarSincompraMes($fecha)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM egresos WHERE categoria <> 'compra' AND MONTH(fecha) = MONTH(?) AND YEAR(fecha) = YEAR(?) ORDER BY id DESC");
			$stm->execute(array($fecha,$fecha));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function EditarMonto($id_compra, $monto)
	{
		try 
		{
			$sql = "UPDATE egresos SET 
						monto    = ?
				    WHERE id_compra = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				    	$monto,
                        $id_compra
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Agrupado_egreso($mes)
	{
		try
		{
			$result = array();
			if($mes!='0'){
				$stm = $this->pdo->prepare("SELECT concepto, categoria, sum(monto) as monto, fecha  FROM egresos WHERE MONTH(fecha) = $mes GROUP BY categoria ORDER BY id DESC");
			}else{
				$stm = $this->pdo->prepare("SELECT concepto, categoria, sum(monto) as monto FROM egresos GROUP BY categoria ORDER BY id DESC");	
			}
			$stm->execute();

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
			          ->prepare("SELECT * FROM cierres WHERE id = ?");
			          

			$stm->execute(array($id));
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
			            ->prepare("DELETE FROM egresos WHERE id = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function Anularcompra($id)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("DELETE FROM egresos WHERE id_compra = ?");			          

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
			$sql = "UPDATE egresos SET 
			            id_cliente     = ?,
			            id_compra     = ?,
			            fecha      	  = ?,
						categoria     = ?,
						concepto      = ?,
						comprobante      = ?, 
						monto         = ?, 
						forma_pago         = ?,
                        sucursal      = ?
						
				    WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				        $data->id_cliente,
				        $data->id_compra,
				    	$data->fecha,
                        $data->categoria, 
                        $data->concepto, 
                        $data->comprobante,                        
                        $data->monto,
                        $data->forma_pago,
                        $data->sucursal,
                        $data->id
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Cierre($data)
	{
		try 
		{
			$sql = "UPDATE cierres SET 

			            fecha_cierre   = ?,
						monto_cierre   = ?
						
				    WHERE id_usuario = ? 
				    AND fecha_cierre IS NULL";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				        $data->fecha_cierre,
				        $data->monto_cierre,
				    	$data->id_usuario
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
		$sql = "INSERT INTO cierres (fecha_apertura, fecha_cierre, id_usuario, id_caja, monto_apertura, monto_cierre, cot_dolar, cot_real) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
				    $data->fecha_apertura,
					$data->fecha_cierre,
					$data->id_usuario,
					$data->id_caja,
					$data->monto_apertura,
                	$data->monto_cierre, 
                	$data->cot_dolar, 
                	$data->cot_real
                   
                )
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}
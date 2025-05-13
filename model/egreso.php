<?php
class egreso
{
	private $pdo;
    
    public $id;
    public $id_cliente;
    public $id_usuario;
    public $id_compra;
    public $id_acreedor;
    public $fecha;
    public $categoria;
    public $concepto;
    public $comprobante;
    public $monto;
    public $forma_pago;  
    public $sucursal;
    public $anulado;
    public $nro_cheque;
    public $plazo;

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

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE anulado IS NULL ORDER BY e.id DESC");
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

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id
			    WHERE id_usuario = ? AND anulado IS NULL
			    ORDER BY e.id DESC");
			$stm->execute(array($_SESSION['user_id']));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListartodoEgresos()
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM egresos ORDER BY id DESC");
			$stm->execute();

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

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id
			    WHERE id_usuario = ? AND anulado = 1
			    ORDER BY e.id DESC");
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

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE anulado IS NULL ORDER BY e.id DESC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function ListarAcreedor($id_acreedor)
	{
		try
		{
			$result = array();
			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE e.id_acreedor = ?  AND anulado IS NULL  ORDER BY e.id DESC");
			$stm->execute(array($id_acreedor));

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

			$stm = $this->pdo->prepare("SELECT * FROM egresos WHERE categoria <> 'Venta' AND fecha >= (SELECT fecha_apertura FROM cierres WHERE id_usuario = ? AND fecha_cierre IS NULL) AND anulado IS NULL  ORDER BY id DESC");
			$stm->execute(array($id_usuario));

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

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE fecha >= ? AND fecha <= ? AND id_usuario = ? AND id_compra IS NULL AND anulado IS NULL  ORDER BY e.id DESC ");
			$stm->execute(array($desde,$hasta,$id_usuario));

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
			          ->prepare("SELECT * FROM egresos WHERE MONTH(fecha) = MONTH(?) AND YEAR(fecha) = YEAR(?) AND categoria <> 'compra'  AND anulado IS NULL AND MONTH(fecha) = '6' AND DAY(fecha) = '1' ");
			          

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
			          ->prepare("SELECT categoria, SUM(monto) as monto FROM egresos WHERE MONTH(fecha) = MONTH(?) AND YEAR(fecha) = YEAR(?) AND anulado IS NULL AND categoria <> 'Transferencia' GROUP BY categoria ORDER BY id DESC");
			          

			$stm->execute(array($fecha, $fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function AgrupadoFechaMes($fecha)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT categoria, SUM(monto) as monto FROM egresos WHERE MONTH(fecha) = MONTH(?) AND YEAR(fecha) = YEAR(?) AND anulado IS NULL AND categoria <> 'Transferencia' AND categoria <> 'compra' AND categoria <> 'compras' GROUP BY categoria ORDER BY id DESC");
			          

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

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE cast(fecha as date) >= ? AND cast(fecha as date) <= ? AND anulado IS NULL ORDER BY e.id DESC");
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

			$stm = $this->pdo->prepare("SELECT * FROM egresos WHERE categoria <> 'compra' AND Cast(fecha as date) = ? AND anulado IS NULL ORDER BY id DESC");
			$stm->execute(array($fecha));

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

			$stm = $this->pdo->prepare("SELECT * FROM egresos WHERE CAST(fecha AS date) >= ? AND CAST(fecha AS date) <= ? AND id_acreedor IS NULL AND anulado IS NULL AND categoria <> 'compra' ORDER BY id ASC");
			$stm->execute(array($desde, $hasta));

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
				$stm = $this->pdo->prepare("SELECT concepto, categoria, sum(monto) as monto, fecha  FROM egresos WHERE MONTH(fecha) = $mes AND anulado IS NULL AND categoria <> 'Transferencia' GROUP BY categoria ORDER BY id DESC");
			}else{
				$stm = $this->pdo->prepare("SELECT concepto, categoria, sum(monto) as monto FROM egresos WHERE anulado IS NULL AND categoria <> 'Transferencia' GROUP BY categoria ORDER BY id DESC");	
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
			          ->prepare("SELECT * FROM egresos WHERE id = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

    public function ActualizarCompra($id_compra)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE egresos e SET e.monto = (SELECT SUM(c.total) FROM compras c WHERE c.id_compra = ?) WHERE id_compra = ?");			          

			$stm->execute(array($id_compra, $id_compra));
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
			            ->prepare("UPDATE egresos SET anulado = 1 WHERE id = ?");			          

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
                        sucursal      = ?,
                        nro_cheque      = ?,
                        plazo      = ?
						
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
                        $data->nro_cheque,
                        $data->plazo,
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
			session_start();
			$id_usuario = $_SESSION['user_id'];
			$sql = "INSERT INTO egresos (id_cliente, id_usuario, id_caja, id_compra, id_acreedor, fecha, categoria, concepto, comprobante, monto, forma_pago, sucursal, nro_cheque, plazo) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
				    $data->id_cliente,
				    $id_usuario,
				    $data->id_caja,
					$data->id_compra,
					$data->id_acreedor,
					$data->fecha,
                	$data->categoria, 
                	$data->concepto, 
                	$data->comprobante,                        
                	$data->monto,
                	$data->forma_pago,
                	$data->sucursal,
                	$data->nro_cheque,
                	$data->plazo
                   
                )
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}
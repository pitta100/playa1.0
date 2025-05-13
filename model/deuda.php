<?php
class deuda
{
	private $pdo;
    
	 public $id;                     // Identificador único de la deuda (probablemente autoincremental).
	public $id_cliente;              // Identificador del cliente al que se le asigna la deuda.
	public $id_venta;                // Identificador de la venta que generó la deuda.
	public $fecha;                   // Fecha en la que se crea la deuda.
	public $vencimiento;             // Fecha en la que se espera que se pague la deuda.
	public $concepto;                // Descripción o concepto de la deuda (ej., "Compra de producto X").
	public $monto;                   // Monto total de la deuda.
	public $saldo;                   // Saldo pendiente de pago (probablemente se irá reduciendo a medida que se realicen pagos).
	public $sucursal;                // Identificador de la sucursal donde se generó la deuda o donde el cliente realizó la compra.
	public $cuotas;                  // Número de cuotas en las que se dividirá el pago de la deuda.
	public $montoRefuerzo;           // Monto adicional que se añadirá a la deuda (posiblemente relacionado con intereses o refuerzos).
	public $cantidadRefuerzo;        // Cantidad de veces que se aplicará el refuerzo o monto adicional.
	public $fecha_refuerzo;          // Fecha en la que se aplica el refuerzo o monto adicional.
	public $fecha_pago_cuota;        // Fecha límite para el pago de cada cuota de la deuda.
	public $tipo_entrega;            // Tipo de entrega de la deuda, podría ser "contado" o "crédito".
	public $entrega_inicial;         // Monto de la entrega inicial realizada al momento de la compra.
	public $entregas_restantes;      // Número de entregas restantes que deben realizarse.
	public $monto_estimado;          // Monto estimado por cada entrega restante (en el caso de pago parcial).
	public $venci_entrega_restante;  // Fecha de vencimiento de la próxima entrega restante.
	public $totalEntrega;            // Total de entrega restante (suma de todas las entregas restantes).
	public $frecuencia_pagos;        // Frecuencia de pago de las cuotas (puede ser "Mensual", "Semanal", "Quincenal", etc.).

    
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

			$stm = $this->pdo->prepare("SELECT *, d.id as id, c.id as id_cliente FROM deudas d LEFT JOIN clientes c ON d.id_cliente = c.id WHERE saldo > 0 ORDER BY d.id DESC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarAgrupadoCliente()
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, d.id as id, c.id as id_cliente, SUM(monto) as monto, SUM(saldo) AS saldo FROM deudas d LEFT JOIN clientes c ON d.id_cliente = c.id WHERE saldo > 0 GROUP BY d.id_cliente ORDER BY d.id DESC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarDia($fecha)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM deudas d LEFT JOIN clientes c ON d.id_cliente = c.id WHERE Cast(fecha as date) = ?");
			          

			$stm->execute(array($fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function ListarMes($fecha)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM deudas d LEFT JOIN clientes c ON d.id_cliente = c.id WHERE MONTH(fecha) = MONTH(?) AND YEAR(fecha) = YEAR(?)");
			          

			$stm->execute(array($fecha, $fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
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
			          ->prepare("SELECT *, d.id FROM deudas d LEFT JOIN clientes c ON d.id_cliente = c.id WHERE d.id = ?");
			          

			$stm->execute(array($id));
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
			          ->prepare("SELECT MAX(id) as id FROM deudas");
			$stm->execute();
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function listar_cliente($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM deudas WHERE id_cliente = ? AND saldo > 0");
			          

			$stm->execute(array($id));
			return $stm->fetchAll(PDO::FETCH_OBJ);
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
			            ->prepare("DELETE FROM deudas WHERE id = ?");			          

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
			            ->prepare("DELETE FROM deudas WHERE id_venta = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function SumarSaldo($data)
	{
		try 
		{
			$sql = "UPDATE deudas SET saldo = saldo + ? WHERE id = ?";

			$this->pdo->prepare($sql)
				->execute(
				    array($data->monto, $data->id)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Actualizar($data)
	{
		try 
		{
			$sql = "UPDATE deudas SET 
						id_cliente    = ?,
						id_venta      = ?,
						fecha      	  = ?,
						vencimiento	  = ?,
						concepto      = ?, 
						monto         = ?,
						saldo         = ?,
						sucursal      = ?,
						cuotas      = ?
                        
				    WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				    	$data->id_cliente,
                        $data->id_venta,
                        $data->fecha,
                        $data->vencimiento,
                        $data->concepto,                        
                        $data->monto,
                        $data->saldo,
                        $data->sucursal,
                        $data->cuotas,
                        $data->id
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}


	public function EditarMonto($id_venta, $monto)
	{
		try 
		{
			$sql = "UPDATE deudas SET 
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
	
	public function Restar($data)
	{
		try 
		{
			$sql = "UPDATE deudas SET 
					
					saldo = saldo - ?
                        
				    WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(                       
                        $data->monto,
                        $data->id
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function disminuir($datas)
	{
		try 
		{
			$sql = "UPDATE deudas SET 
					
					cuotas = cuotas - ?
                        
				    WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(                       
                        $datas->cuotas,
                        $datas->id
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function Interes($datas)
	{
	    try 
	    {
	        $sql = "UPDATE deudas SET 
					
					intereses = ?
                        
				    WHERE id = ?";
	                
	        	$this->pdo->prepare($sql)
			     ->execute(
				    array(                       
                        $datas->intereses,
                        $datas->id
					)
				);
	    } catch (Exception $e) 
	    {
	        die($e->getMessage());
	    }
	}

	public function nuevafecha($datas)
	{
	    try 
	    {
	        $sql = "UPDATE deudas SET 
	                    fecha_pago_cuota = ?,
	                    vencimiento = ?
	                WHERE id = ?";

	        $this->pdo->prepare($sql)
	             ->execute(
	                array(  
	                    $datas->fecha_pago_cuota,                     
	                    $datas->vencimiento,
	                    $datas->id
	                )
	            );
	    } 
	    catch (Exception $e) 
	    {
	        die($e->getMessage());
	    }
	}


	
	public function Guardar($data)
	{
		try 
		{
		$sql = "INSERT INTO deudas (id_cliente, id_venta, fecha, vencimiento, concepto, monto, saldo, sucursal) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
					$data->id_cliente,
					$data->id_venta,
					$data->fecha,
					$data->vencimiento,
					$data->concepto,
                    $data->monto,
                    $data->saldo,
                    $data->sucursal
                    
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
        // Agregar el campo 'frecuencia_pagos' a la consulta SQL
        $sql = "INSERT INTO deudas (
                id_cliente,
                id_venta,
                fecha,
                vencimiento,
                concepto,
                monto,
                intereses,
                saldo,
                sucursal,
                cuotas,
                montoRefuerzo,
                cantidadRefuerzo,
                fecha_refuerzo,
                fecha_pago_cuota,
                tipo_entrega,
                entrega_inicial,
                entregas_restantes,
                monto_estimado,
                venci_entrega_restante,
                totalEntrega,
                frecuencia_pagos  -- Aquí añadimos el nuevo campo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Incluir 'frecuencia_pagos' en el arreglo de parámetros que se pasan a la consulta
        $this->pdo->prepare($sql)->execute(array(
            $data->id_cliente,
            $data->id_venta,
            $data->fecha,
            $data->vencimiento,
            $data->concepto,
            $data->monto,
            $data->intereses ?? 0,
            $data->saldo,
            $data->sucursal,
            $data->cuotas,
            $data->montoRefuerzo,
            $data->cantidadRefuerzo,
            $data->fecha_refuerzo,
            $data->fecha_pago_cuota,
            $data->tipo_entrega,
            $data->entrega_inicial,
            $data->entregas_restantes,
            $data->monto_estimado,
            $data->venci_entrega_restante,
            $data->totalEntrega,
            $data->frecuencia_pagos // Aquí agregamos el valor de 'frecuencia_pagos'
        ));
    } 
    catch (Exception $e) 
    {
        die($e->getMessage());
    }
}

	public function listarDetallesModel($id)
	{
	    try {
	        $stm = $this->pdo->prepare("SELECT *, d.id FROM deudas d LEFT JOIN clientes c ON d.id_cliente = c.id WHERE d.id = ?");
	        $stm->execute(array($id));
	        return $stm->fetch(PDO::FETCH_OBJ); // Esto devuelve el primer resultado, que es el que quieres
	    } catch (Exception $e) {
	        die($e->getMessage());
	    }
	}

	public function listarDetallesRefuersosModel($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT *, d.id FROM deudas d LEFT JOIN clientes c ON d.id_cliente = c.id WHERE d.id = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function listarDeudas($id) {
	    try {
	        // Le damos un alias a nombre para poder acceder como 'cliente_nombre'
	        $stmt = $this->pdo->prepare("SELECT d.*, c.nombre AS cliente_nombre FROM deudas d
	                                     LEFT JOIN clientes c ON d.id_cliente = c.id
	                                     WHERE d.id = ?");
	        $stmt->execute([$id]);
	        $deudas = $stmt->fetchAll(PDO::FETCH_OBJ);

	        return $deudas;
	    } catch (Exception $e) {
	        die($e->getMessage());
	    }
	}
	public function listarDeudasCalendar() {
	    try {
	        // Consulta para obtener las deudas con los campos relevantes
	        $stmt = $this->pdo->prepare("SELECT 
	                                    d.id, 
	                                    d.id_cliente, 
	                                    d.fecha, 
	                                    d.vencimiento, 
	                                    d.concepto, 
	                                    d.monto, 
	                                    d.saldo, 
	                                    c.nombre AS cliente_nombre
	                                FROM 
	                                    deudas d
	                                LEFT JOIN 
	                                    clientes c ON d.id_cliente = c.id
	                                WHERE 
	                                    d.vencimiento IS NOT NULL
	                                ORDER BY 
	                                    d.vencimiento");
	        $stmt->execute();
	        $deudas = $stmt->fetchAll(PDO::FETCH_OBJ);

	        return $deudas;  // Devolvemos las deudas
	    } catch (Exception $e) {
	        die($e->getMessage());
	    }
	}
	public function disminuirCantidadRefuerzo($deuda) {
	try {
	        $stmt = $this->pdo->prepare("UPDATE deudas 
	                                     SET cantidadRefuerzo = cantidadRefuerzo - :cantidadRefuerzo 
	                                     WHERE id = :id");
	        $stmt->execute([
	            ':cantidadRefuerzo' => $deuda->cantidadRefuerzo,
	            ':id' => $deuda->id
	        ]);
	    } catch (Exception $e) {
	        die($e->getMessage());
	    }
	}
	public function disminuirRefuerzo($deuda) {
    try {
	        $stmt = $this->pdo->prepare("UPDATE deudas 
	                                     SET montoRefuerzo = montoRefuerzo - :montoRefuerzo 
	                                     WHERE id = :id");
	        $stmt->execute([
	            ':montoRefuerzo' => $deuda->montoRefuerzo,
	            ':id' => $deuda->id
	        ]);
	    } catch (Exception $e) {
	        die($e->getMessage());
	    }
	}
	public function nuevaFechaRefuerzo($deuda) {
    try {
        $stmt = $this->pdo->prepare("UPDATE deudas 
                                     SET fecha_refuerzo = :fecha_refuerzo 
                                     WHERE id = :id");
        $stmt->execute([
            ':fecha_refuerzo' => $deuda->fecha_refuerzo,
            ':id' => $deuda->id
        ]);
    } catch (Exception $e) {
        die($e->getMessage());
    }
}







}
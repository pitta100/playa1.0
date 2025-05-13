<?php
class cierre_inventario
{
	private $pdo;
    
    public $id;
    public $fecha_apertura;
    public $fecha_cierre;
    public $usuario_apertura;
    public $usuario_cierre;
    public $motivo;
  

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

			$stm = $this->pdo->prepare("SELECT *, ci.id AS id, u.user AS usuario
				 FROM cierre_inventario ci
				 LEFT JOIN usuario u ON ci.usuario_inicial = u.id 
				 ORDER BY ci.id DESC");

			$stm->execute(array());

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	
	public function Consultar($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM cierre_inventario WHERE usuario_inicial = ? AND fecha_cierre IS NULL");
			          

			$stm->execute(array($id));
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
			          ->prepare("SELECT * FROM cierre_inventario WHERE id = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function ObtenerUsuario($id)
	{
		try 
		{
			//session_start();
			//$id_usuario = $_SESSION['user_id'];
			$stm = $this->pdo
			          ->prepare("SELECT * FROM cierre_inventario WHERE usuario_inicial = ? AND fecha_cierre = NULL");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

		public function ConsultarCierre($id, $fecha)
	{
		try 
		{
			
			$stm = $this->pdo
			          ->prepare("SELECT * FROM cierre_inventario
			          	WHERE usuario_inicial = ? AND fecha_cierre IS NULL AND CAST(fecha_apertura AS date) <= ?");
			        
			$stm->execute(array($id, $fecha));
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
			            ->prepare("DELETE FROM cierre_inventario WHERE id = ?");			          

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
			$sql = "UPDATE cierre_inventario SET 
			            fecha_apertura     = ?,
			            fecha_cierre      = ?,
			            usuario_inicial   	  = ?,
			            usuario_final = ?,
			            motivo   	  = ?,
						
				    WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				        $data->fecha_apertura,
				        $data->fecha_cierre,
				    	$data->usuario_inicial,
				    	$data->usuario_final,
				    	$data->motivo,
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
			$sql = "UPDATE cierre_inventario SET 

			            fecha_cierre   = ?,
			            motivo   = ?
						
				    WHERE usuario_inicial = ? AND fecha_cierre IS NULL";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				        $data->fecha_cierre,
				    	$data->motivo,
				    	$data->usuario_inicial
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
		$sql = "INSERT INTO cierre_inventario (fecha_apertura, fecha_cierre, usuario_inicial) 
		        VALUES (?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
				    $data->fecha_apertura,
					$data->fecha_cierre,
					$data->usuario_inicial
                )
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}
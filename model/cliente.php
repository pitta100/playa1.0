<?php
class cliente
{
    private $pdo;

   public $id;
    public $ruc;
    public $nombre;
    public $nick;
    public $correo;
    public $pass;
    public $telefono;
    public $cumple;
    public $direccion;
    public $fecha_registro;
    public $foto_perfil;
    public $sucursal;
    public $puntos;
    public $gastado;
    public $mayorista;
    public $adressWork;
    public $phoneWork;
    public $comprobanteIngreso;
    public $cedulaTributaria;
    public $facturasLegalesEmitidas;
    public $cedulaIdentidad;

    public function __CONSTRUCT()
    {
        try
        {
            $this->pdo = Database::StartUp();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Listar()
    {
        try
        {
            $result = array();

            $stm = $this->pdo->prepare("SELECT * FROM clientes ORDER BY id DESC");
            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ListarCumple($dia, $mes)
    {
        try
        {
            $result = array();

            $stm = $this->pdo->prepare("SELECT * FROM clientes WHERE DAY(cumple) = ? AND MONTH(cumple) = ? ORDER BY id DESC");
            $stm->execute(array($dia, $mes));

            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ListarClientes()
    {
        try
        {
            $result = array();

            $stm = $this->pdo->prepare("SELECT * FROM clientes WHERE cliente = 1 ORDER BY id DESC");
            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
    public function ListarMayorista()
    {
        try
        {
            $result = array();

            $stm = $this->pdo->prepare("SELECT * FROM clientes WHERE mayorista = 'SI' ORDER BY id DESC");
            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ListarProveedores()
    {
        try
        {
            $result = array();

            $stm = $this->pdo->prepare("SELECT * FROM clientes WHERE proveedor = 1 ORDER BY id DESC");
            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ListarFuncionarios()
    {
        try
        {
            $result = array();

            $stm = $this->pdo->prepare("SELECT * FROM clientes ORDER BY id DESC");
            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try
        {
            $stm = $this->pdo->prepare("SELECT * FROM clientes WHERE id = ?");
            $stm->execute(array($id));

            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function ObtenerClienteConVentas($id)
    {
        try
        {
            // Consulta para obtener los datos del cliente y sus ventas
            $stm = $this->pdo->prepare(" 
                SELECT 
                    c.*, 
                    v.id_venta, v.id_vendedor, v.vendedor_salon, v.id_producto, v.precio_costo, 
                    v.precio_venta, v.subtotal, v.descuento, v.iva, v.total, v.comprobante, 
                    v.nro_comprobante, v.cantidad, v.margen_ganancia, v.fecha_venta, 
                    v.metodo, v.banco, v.contado, v.anulado, v.id_gift
                FROM clientes c
                LEFT JOIN ventas v ON c.id = v.id_cliente
                WHERE c.id = ?
            ");
            $stm->execute(array($id));

            // Obtenemos todos los resultados
            $cliente = $stm->fetch(PDO::FETCH_OBJ);

            if ($cliente) {
                // Recuperamos todas las ventas asociadas al cliente
                $ventas = $this->pdo->prepare(" 
                    SELECT 
                        v.id_venta, v.id_vendedor, v.vendedor_salon, v.id_producto, v.precio_costo, 
                        v.precio_venta, v.subtotal, v.descuento, v.iva, v.total, v.comprobante, 
                        v.nro_comprobante, v.cantidad, v.margen_ganancia, v.fecha_venta, 
                        v.metodo, v.banco, v.contado, v.anulado, v.id_gift
                    FROM ventas v 
                    WHERE v.id_cliente = ?
                ");
                $ventas->execute(array($id));
                
                // Almacenamos las ventas en el objeto cliente
                $cliente->ventas = $ventas->fetchAll(PDO::FETCH_OBJ);
            }

            return $cliente;
        } 
        catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function Eliminar($id)
    {
        try
        {
            $stm = $this->pdo
                ->prepare("DELETE FROM clientes WHERE id = ?");

            $stm->execute(array($id));
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function SumarPuntos($puntos, $id_cliente)
    {
        try
        {
            $stm = $this->pdo
                ->prepare("UPDATE clientes SET puntos = puntos + ? WHERE id = ?");

            $stm->execute(array($puntos, $id_cliente));
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function SumarGastos($gastos, $id_cliente)
    {
        try
        {
            $stm = $this->pdo
                ->prepare("UPDATE clientes SET gastado = gastado + ? WHERE id = ?");

            $stm->execute(array($gastos, $id_cliente));
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
  public function Actualizar($data)
{
    try {
        // Verificamos si hay foto de perfil
        if ($data->foto_perfil != '') {
            $fotoPerfil = $data->foto_perfil;
        } else {
            $fotoPerfil = NULL;  // Asignamos NULL si no hay foto de perfil
        }

        // Obtener los documentos existentes antes de la actualización
        $comprobanteIngreso = !empty($data->comprobanteIngreso) ? $data->comprobanteIngreso : (isset($data->comprobanteIngreso_actual) ? $data->comprobanteIngreso_actual : NULL);
        $cedulaTributaria = !empty($data->cedulaTributaria) ? $data->cedulaTributaria : (isset($data->cedulaTributaria_actual) ? $data->cedulaTributaria_actual : NULL);
        $facturasLegalesEmitidas = !empty($data->facturasLegalesEmitidas) ? $data->facturasLegalesEmitidas : (isset($data->facturasLegalesEmitidas_actual) ? $data->facturasLegalesEmitidas_actual : NULL);
        $cedulaIdentidad = !empty($data->cedulaIdentidad) ? $data->cedulaIdentidad : (isset($data->cedulaIdentidad_actual) ? $data->cedulaIdentidad_actual : NULL);
        $estructuraJuridica = !empty($data->estructuraJuridica) ? $data->estructuraJuridica : (isset($data->estructuraJuridica_actual) ? $data->estructuraJuridica_actual : NULL);
        $beneficiarioFinal = !empty($data->beneficiarioFinal) ? $data->beneficiarioFinal : (isset($data->beneficiarioFinal_actual) ? $data->beneficiarioFinal_actual : NULL);
        $varios = !empty($data->varios) ? $data->varios : (isset($data->varios_actual) ? $data->varios_actual : NULL);

        // Si hay foto de perfil, se ejecuta el UPDATE con foto
        if ($fotoPerfil != NULL) {
            $sql = "UPDATE clientes SET
                        ruc = ?, 
                        nombre = ?, 
                        nick = ?, 
                        correo = ?, 
                        pass = ?, 
                        telefono = ?, 
                        cumple = ?, 
                        direccion = ?, 
                        foto_perfil = ?, 
                        sucursal = ?, 
                        puntos = ?, 
                        gastado = ?, 
                        mayorista = ?, 
                        adressWork = ?, 
                        residencia_url = ?,
                        phoneWork = ?, 
                        comprobanteIngreso = ?, 
                        cedulaTributaria = ?, 
                        facturasLegalesEmitidas = ?, 
                        cedulaIdentidad = ?,
                        estructuraJuridica = ?, 
                        beneficiarioFinal = ?, 
                        varios = ?
                    WHERE id = ?";
            $this->pdo->prepare($sql)
                ->execute(
                    array(
                        $data->ruc,
                        $data->nombre,
                        $data->nick,
                        $data->correo,
                        $data->pass,
                        $data->telefono,
                        $data->cumple,
                        $data->direccion,
                        $fotoPerfil, // Foto de perfil
                        $data->sucursal,
                        $data->puntos,
                        $data->gastado,
                        $data->mayorista,
                        $data->adressWork,
                        $data->residencia_url,
                        $data->phoneWork,
                        $comprobanteIngreso,
                        $cedulaTributaria,
                        $facturasLegalesEmitidas,
                        $cedulaIdentidad,
                        $estructuraJuridica,
                        $beneficiarioFinal,
                        $varios,
                        $data->id
                    )
                );
        } else {
            // Si no hay foto de perfil, se ejecuta el UPDATE sin foto
            $sql = "UPDATE clientes SET
                        ruc = ?, 
                        nombre = ?, 
                        nick = ?, 
                        correo = ?, 
                        pass = ?, 
                        telefono = ?, 
                        cumple = ?, 
                        direccion = ?, 
                        sucursal = ?, 
                        puntos = ?, 
                        gastado = ?, 
                        mayorista = ?, 
                        adressWork = ?, 
                        residencia_url = ?,
                        phoneWork = ?, 
                        comprobanteIngreso = ?, 
                        cedulaTributaria = ?, 
                        facturasLegalesEmitidas = ?, 
                        cedulaIdentidad = ?,
                        estructuraJuridica = ?, 
                        beneficiarioFinal = ?, 
                        varios = ?
                    WHERE id = ?";
            $this->pdo->prepare($sql)
                ->execute(
                    array(
                        $data->ruc,
                        $data->nombre,
                        $data->nick,
                        $data->correo,
                        $data->pass,
                        $data->telefono,
                        $data->cumple,
                        $data->direccion,
                        $data->sucursal,
                        $data->puntos,
                        $data->gastado,
                        $data->mayorista,
                        $data->adressWork,
                        $data->residencia_url,
                        $data->phoneWork,
                        $comprobanteIngreso,
                        $cedulaTributaria,
                        $facturasLegalesEmitidas,
                        $cedulaIdentidad,
                        $estructuraJuridica,
                        $beneficiarioFinal,
                        $varios,
                        $data->id
                    )
                );
        }

    } catch (Exception $e) {
        die($e->getMessage());
    }
}

public function Registrar(cliente $data)
{
    try {
        // Verificamos si hay foto de perfil
        if ($data->foto_perfil != '') {
            $sql = "INSERT INTO clientes (
                        ruc, nombre, nick, correo, pass, telefono, cumple, direccion, 
                        fecha_registro, foto_perfil, sucursal, cliente, proveedor, puntos, 
                        gastado, mayorista, adressWork, residencia_url, phoneWork, 
                        comprobanteIngreso, cedulaTributaria, facturasLegalesEmitidas, 
                        cedulaIdentidad, estructuraJuridica, beneficiarioFinal, varios
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $this->pdo->prepare($sql)
                ->execute(
                    array(
                        $data->ruc,
                        $data->nombre,
                        $data->nick,
                        $data->correo,
                        $data->pass,
                        $data->telefono,
                        $data->cumple,
                        $data->direccion,
                        $data->fecha_registro,
                        $data->foto_perfil,
                        $data->sucursal,
                        $data->cliente,
                        $data->proveedor,
                        $data->puntos,
                        $data->gastado,
                        $data->mayorista,
                        $data->adressWork,
                        $data->residencia_url,
                        $data->phoneWork,
                        $data->comprobanteIngreso,
                        $data->cedulaTributaria,
                        $data->facturasLegalesEmitidas,
                        $data->cedulaIdentidad,
                        $data->estructuraJuridica,
                        $data->beneficiarioFinal,
                        $data->varios
                    )
                );
        } else {
            // Si no hay foto de perfil
            $sql = "INSERT INTO clientes (
                        ruc, nombre, nick, correo, pass, telefono, cumple, direccion, 
                        fecha_registro, sucursal, cliente, proveedor, puntos, gastado, 
                        mayorista, adressWork, residencia_url, phoneWork, 
                        comprobanteIngreso, cedulaTributaria, facturasLegalesEmitidas, 
                        cedulaIdentidad, estructuraJuridica, beneficiarioFinal, varios
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $this->pdo->prepare($sql)
                ->execute(
                    array(
                        $data->ruc,
                        $data->nombre,
                        $data->nick,
                        $data->correo,
                        $data->pass,
                        $data->telefono,
                        $data->cumple,
                        $data->direccion,
                        $data->fecha_registro,
                        $data->sucursal,
                        $data->cliente,
                        $data->proveedor,
                        $data->puntos,
                        $data->gastado,
                        $data->mayorista,
                        $data->adressWork,
                        $data->residencia_url,
                        $data->phoneWork,
                        $data->comprobanteIngreso,
                        $data->cedulaTributaria,
                        $data->facturasLegalesEmitidas,
                        $data->cedulaIdentidad,
                        $data->estructuraJuridica,
                        $data->beneficiarioFinal,
                        $data->varios
                    )
                );
        }

    } catch (Exception $e) {
        die($e->getMessage());
    }
}

    public function ObtenerVentasPorCliente($id)
    {
        $sql = "SELECT * FROM ventas WHERE id_cliente = ? AND anulado = 0 ORDER BY fecha_venta DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

   
}

<?php 
class PagoCuota
{
    private $pdo;

    public $id;
    public $id_deuda;
    public $nro_cuota;
    public $monto_cuota;
    public $monto_pagado;
    public $pagado;
    public $fecha_vencimiento;
    public $fecha_pago;
    public $metodo_pago;
    public $observacion;

    /*
|--------------------------------------------------------------------------
| ¿Cómo lo usás?
|--------------------------------------------------------------------------
| Desde tu controlador o lógica de negocio, podrías hacer:
|
| $pago = new PagoCuota();
| $pago->PagarCuota($id); // Marca una cuota como pagada
|
| $cuotas = $pago->ListarPorDeuda(); // Muestra cuotas de una deuda
|
*/


    public function __CONSTRUCT()
    {
        try {
            $this->pdo = Database::StartUp();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // ✅ Listar todas las cuotas de una deuda
    public function ListarPorDeuda($id_deuda)
    {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM pagos_cuotas WHERE id_deuda = ? ORDER BY nro_cuota ASC");
            $stm->execute([$id_deuda]);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // ✅ Marcar cuota como pagada
    public function PagarCuota($id)
    {
        try {
            $stm = $this->pdo->prepare("UPDATE pagos_cuotas SET pagado = 1, fecha_pago = NOW() WHERE id = ?");
            return $stm->execute([$id]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // ✅ Registrar una nueva cuota
    public function Registrar(PagoCuota $data)
    {
        try {
            $sql = "INSERT INTO pagos_cuotas (
                        id_deuda, nro_cuota, monto_cuota, monto_pagado, pagado,
                        fecha_vencimiento, fecha_pago, metodo_pago, observacion
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $this->pdo->prepare($sql)->execute([
                $data->id_deuda,
                $data->nro_cuota,
                $data->monto_cuota,
                $data->monto_pagado,
                $data->pagado,
                $data->fecha_vencimiento,
                $data->fecha_pago,
                $data->metodo_pago,
                $data->observacion
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    // Verificar si una cuota está pagada
    public function EstaPagada($id_deuda, $nro_cuota) {
        $stm = $this->pdo->prepare("SELECT pagado FROM pagos_cuotas WHERE id_deuda = ? AND nro_cuota = ?");
        $stm->execute([$id_deuda, $nro_cuota]);
        return $stm->fetchColumn() == 1;
    }

    // Marcar una cuota como pagada
    public function PagarCuota($id_deuda, $nro_cuota) {
        $stm = $this->pdo->prepare("INSERT INTO pagos_cuotas (id_deuda, nro_cuota, pagado) 
                                    VALUES (?, ?, 1)
                                    ON DUPLICATE KEY UPDATE pagado = 1");
        $stm->execute([$id_deuda, $nro_cuota]);
    }



}



 ?>
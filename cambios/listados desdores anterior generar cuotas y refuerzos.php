<?php
$id_deuda = isset($_GET['id']) ? $_GET['id'] : null;
$fecha_refuerzo = isset($_GET['fecha_refuerzo']) ? $_GET['fecha_refuerzo'] : null;
if (!$id_deuda) {
    echo "No se ha definido el id de la deuda.";
    exit;
}
?>

<h1 class="page-header">Cuotas y Refuerzos</h1>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr style="background-color: #5DACCD; color:#fff">
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Cuota</th>
                <th>Refuerzo</th>
                <th>Saldo Restante</th>
                <th>Interés</th>
                <th>Nuevo Pago</th>
            </tr>
        </thead>
        <tbody>
        <?php  
        $sumaRefuerzos = 0; // Variable para la suma de los refuerzos
        $totalCuotas = 0; // Variable para el total de cuotas
        $totalMontoCuotas = 0; // Variable para el monto total de las cuotas

        foreach($this->model->listarDeudas($id_deuda) as $deuda): 

            $fechaRefuerzo = null;
            if (!empty($deuda->fecha_refuerzo)) {
                try {
                    $fechaRefuerzo = new DateTime($deuda->fecha_refuerzo);
                } catch (Exception $e) {
                    echo "Error al convertir fecha_refuerzo: " . $e->getMessage();
                }
            }

            $valorCuota = $deuda->saldo / $deuda->cuotas;
            $saldoRestante = $deuda->saldo;
            $hoy = new DateTime();

            // 1. Definir cantidad de refuerzos y monto de refuerzo
            $cantidadRefuerzo = $deuda->cantidadRefuerzo; // Asegúrate de que esta variable esté definida correctamente
            $montoRefuerzoTotal = $deuda->montoRefuerzo;
            $montoRefuerzoUnitario = ($cantidadRefuerzo > 0) ? ($montoRefuerzoTotal / $cantidadRefuerzo) : 0;

            // 2. Generar eventos
            $eventos = [];
            $fechaVencimiento = new DateTime($deuda->vencimiento);

            // Primero generamos todas las fechas de refuerzo
            $fechasRefuerzo = [];
            if ($fechaRefuerzo && $cantidadRefuerzo > 0) {
                for ($i = 0; $i < $cantidadRefuerzo; $i++) {
                    $fecha = clone $fechaRefuerzo;
                    $fecha->modify("+$i years");
                    $fechasRefuerzo[] = $fecha->format('Y-m'); // solo año-mes
                    $eventos[] = [
                        'tipo' => 'refuerzo',
                        'fecha' => $fecha,
                        'monto' => $montoRefuerzoUnitario
                    ];
                }
            }

            // Ahora generamos las cuotas
            for ($i = 0; $i < $deuda->cuotas; $i++) {
                $fecha = clone $fechaVencimiento;
                $fecha->modify("+$i months");

                // Si el mes coincide con un refuerzo, no agregamos la cuota
                if (in_array($fecha->format('Y-m'), $fechasRefuerzo)) {
                    continue;
                }

                $eventos[] = [
                    'tipo' => 'cuota',
                    'fecha' => $fecha,
                    'monto' => $valorCuota
                ];
            }

            // 3. Ordenar por fecha
            usort($eventos, function($a, $b) {
                return $a['fecha'] <=> $b['fecha'];
            });

            // 4. Mostrar la tabla unificada
            foreach ($eventos as $evento) {
                $esRefuerzo = $evento['tipo'] === 'refuerzo';
                $monto = $evento['monto'];
                $fecha = $evento['fecha'];
                $interes = 0;
                $nuevoPago = $monto;

                if (!$esRefuerzo) {
                    // Solo se descuenta del saldo en cuotas
                    $saldoRestante -= $valorCuota;

                    // Cálculo de interés si está vencida
                    $diferenciaDias = $hoy->diff($fecha)->days;
                    if ($hoy > $fecha && $diferenciaDias > 5) {
                        $interes = $valorCuota * 0.03;
                        $nuevoPago += $interes;
                    }

                    // Sumar el total de cuotas
                    $totalCuotas++;
                    $totalMontoCuotas += $valorCuota;
                }

                // Estilo para fila refuerzo
                $estiloFila = $esRefuerzo ? 'style="background-color: #d4edda;"' : '';
        ?>
        <tr <?php echo $estiloFila; ?>>
            <td><?php echo htmlspecialchars($deuda->cliente_nombre); ?></td>
            <td><?php echo $fecha->format('d/m/Y'); ?></td>
            <td><?php echo $esRefuerzo ? 'Refuerzo' : 'Cuota'; ?></td>
            <td><?php echo $esRefuerzo ? '-' : number_format($valorCuota, 0, ',', '.'); ?></td>
            <td><?php echo $esRefuerzo ? number_format($monto, 0, ',', '.') : '-'; ?></td>
            <td><?php echo number_format(max($saldoRestante, 0), 0, ',', '.'); ?></td>
            <td><?php echo (!$esRefuerzo && $interes > 0) ? number_format($interes, 0, ',', '.') : '-'; ?></td>
            <td><?php echo number_format($nuevoPago, 0, ',', '.'); ?></td>
        </tr>
        <?php 
                // Sumar el monto de los refuerzos
                if ($esRefuerzo) {
                    $sumaRefuerzos += $monto;
                }

            } // fin foreach eventos
            
        endforeach; 
        ?>
        
        <!-- 🔥 Fila Final: Total de Cuotas -->
        <tr style="background-color: #f8f9fa; font-weight:bold;">
            <td colspan="7" style="text-align: right;">Total Cuotas:</td>
            <td><?php echo number_format($totalMontoCuotas, 0, ',', '.'); ?></td>
        </tr>

        <!-- 🔥 Fila Final: Total de Refuerzos -->
        <tr style="background-color: #f8f9fa; font-weight:bold;">
            <td colspan="7" style="text-align: right;">Total Refuerzos:</td>
            <td><?php echo number_format($sumaRefuerzos, 0, ',', '.'); ?></td>
        </tr>

        </tbody>
    </table>
</div>

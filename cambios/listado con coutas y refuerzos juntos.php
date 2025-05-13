<?php
$id_deuda = isset($_GET['id']) ? $_GET['id'] : null;
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
        foreach($this->model->listarDeudas($id_deuda) as $deuda): 
            $valorCuota = $deuda->saldo / $deuda->cuotas;
            $saldoRestante = $deuda->saldo;
            $hoy = new DateTime();

            // 1. Generar cuotas
            $eventos = [];
            $fechaVencimiento = new DateTime($deuda->vencimiento);

            for ($i = 0; $i < $deuda->cuotas; $i++) {
                $fecha = clone $fechaVencimiento;
                $fecha->modify("+$i months");
                $eventos[] = [
                    'tipo' => 'cuota',
                    'fecha' => $fecha,
                    'monto' => $valorCuota
                ];
            }

            // 2. Generar refuerzos
            $cantidadRefuerzo = $deuda->cantidadRefuerzo;
            $montoRefuerzoTotal = $deuda->montoRefuerzo;
            $montoRefuerzoUnitario = ($cantidadRefuerzo > 0) ? ($montoRefuerzoTotal / $cantidadRefuerzo) : 0;
            $fechaRefuerzo = (!empty($deuda->fecha_refuerzo)) ? new DateTime($deuda->fecha_refuerzo) : null;

            if ($fechaRefuerzo && $cantidadRefuerzo > 0) {
                for ($i = 0; $i < $cantidadRefuerzo; $i++) {
                    $fecha = clone $fechaRefuerzo;
                    $fecha->modify("+$i years");
                    $eventos[] = [
                        'tipo' => 'refuerzo',
                        'fecha' => $fecha,
                        'monto' => $montoRefuerzoUnitario
                    ];
                }
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
            } // fin foreach eventos
        endforeach; 
        ?>
        </tbody>
    </table>
</div>

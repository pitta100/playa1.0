<?php
$id_deuda = isset($_GET['id']) ? $_GET['id'] : null;
$fecha_refuerzo = isset($_GET['fecha_refuerzo']) ? $_GET['fecha_refuerzo'] : null;
  $frecuencia = isset($_GET['frecuencia_pagos']) ? $_GET['frecuencia_pagos'] : (isset($deuda->frecuencia_pagos) ? strtolower($deuda->frecuencia_pagos) : null);

if (!$id_deuda) {
    echo "No se ha definido el id de la deuda.";
    exit;
}
?>
<h1 class="page-header">Lista de Cuotas</h1>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr style="background-color: #5DACCD; color:#fff">
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Cuota</th>
                <th>Saldo Restante</th>
                <th>Interés</th>
                <th>Nuevo Pago</th>
                <th>Fecha Refuerzo</th>
                <th>Monto Refuerzo</th>
            </tr>
        </thead>
        <tbody>
        <?php  
        foreach($this->model->listarDeudas($id_deuda) as $deuda): 
              //var_dump($deuda); 
//exit;  // Esto te ayudará a ver los datos de la deuda antes de continuar
            $valorCuota = $deuda->saldo / $deuda->cuotas;
            $saldoRestante = $deuda->saldo;
            $fechaBase = new DateTime($deuda->vencimiento); // Fecha de vencimiento base
            $hoy = new DateTime();
            
            // Datos de refuerzo
            $montoRefuerzoTotal = $deuda->montoRefuerzo;
            $cantidadRefuerzo = $deuda->cantidadRefuerzo;
            $fechaRefuerzo = new DateTime($deuda->fecha_refuerzo);
            $montoRefuerzoUnitario = ($cantidadRefuerzo > 0) ? ($montoRefuerzoTotal / $cantidadRefuerzo) : 0;
            $contadorRefuerzos = 0;

            $cuotasPagadas = 0; // cuotas pagadas
            $sumaRefuerzos = 0; // suma total de refuerzos

            // Frecuencia de pagos
            $frecuencia = strtolower($deuda->frecuencia_pagos); // Obtener la frecuencia de pago (trimestral, semestral, etc.)

            while ($cuotasPagadas < $deuda->cuotas):
                // Crear la fecha de cada cuota basándonos en la fecha base de vencimiento
                $fechaCuota = clone $fechaBase;
                $fechaCuota->modify("+$cuotasPagadas months"); // Avanza mes a mes

                // Por defecto vacíos los campos de refuerzo
                $mostrarFechaRefuerzo = "-";
                $mostrarMontoRefuerzo = "-";
                $esRefuerzo = false;

                // Verificar si la frecuencia es adecuada para generar un refuerzo en este mes
                if ($cantidadRefuerzo > 0 && $fechaCuota >= $fechaRefuerzo) {
                    switch ($frecuencia) {
                        case 'trimestral':
                            $intervalo = '3 months';  // Refuerzos cada 3 meses
                            break;
                        case 'semestral':
                            $intervalo = '6 months';  // Refuerzos cada 6 meses
                            break;
                        case 'novenal':
                            $intervalo = '9 months';  // Refuerzos cada 9 meses
                            break;
                        case 'Anual':
                            $intervalo = '12 months';  // Refuerzos cada 12 meses
                            break;
                        default:
                            $intervalo = '12 months';  // Si no tiene frecuencia, considerar anual
                    }

                    // Verificamos si toca refuerzo
                    if ($fechaCuota->format('Y-m-d') == $fechaRefuerzo->format('Y-m-d') && $contadorRefuerzos < $cantidadRefuerzo) {
                        // 💥 Es un mes de refuerzo
                        $mostrarFechaRefuerzo = $fechaRefuerzo->format('d/m/Y');
                        $mostrarMontoRefuerzo = number_format($montoRefuerzoUnitario, 0, ',', '.');
                        $esRefuerzo = true;

                        // Sumamos al total de refuerzos
                        $sumaRefuerzos += $montoRefuerzoUnitario;

                        // Saltamos al próximo refuerzo según la frecuencia
                        $fechaRefuerzo->modify($intervalo);
                        $contadorRefuerzos++;
                    }
                }

                if (!$esRefuerzo) {
                    // 💥 Es un mes normal de cuota
                    $saldoRestante -= $valorCuota;
                    $cuotasPagadas++; // Solo contamos cuando es cuota normal
                }

                // Calculamos interés y nuevo pago
                $diferenciaDias = $hoy->diff($fechaCuota)->days; 
                $interes = 0;
                $nuevoPago = $valorCuota;

                if ($diferenciaDias > 5 && $hoy > $fechaCuota && !$esRefuerzo) {
                    $interes = $valorCuota * 0.03;
                    $nuevoPago += $interes;
                }

                // Estilo si es refuerzo
                $estiloFila = $esRefuerzo ? 'style="background-color: #d4edda;"' : '';
        ?>
        <tr <?php echo $estiloFila; ?>>
            <td><?php echo htmlspecialchars($deuda->cliente_nombre); ?></td>
            <td><?php echo $fechaCuota->format('d/m/Y'); ?></td>
            <td><?php echo $esRefuerzo ? '-' : number_format($valorCuota, 0, ',', '.'); ?></td>
            <td><?php echo number_format(max($saldoRestante, 0), 0, ',', '.'); ?></td>
            <td>
                <?php 
                if (!$esRefuerzo && $interes > 0) {
                    echo number_format($interes, 0, ',', '.');
                } else {
                    echo '-';
                }
                ?>
            </td>
            <td><?php echo $esRefuerzo ? number_format($montoRefuerzoUnitario, 0, ',', '.') : number_format($nuevoPago, 0, ',', '.'); ?></td>
            <td><?php echo $mostrarFechaRefuerzo; ?></td>
            <td><?php echo $mostrarMontoRefuerzo; ?></td>
        </tr>
        <?php 
            endwhile; 
        ?>
        <!-- 🔥 Fila Final: Total de Refuerzos -->
        <tr style="background-color: #f8f9fa; font-weight:bold;">
            <td colspan="7" style="text-align: right;">Total Refuerzos:</td>
            <td><?php echo number_format($sumaRefuerzos, 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</div> 

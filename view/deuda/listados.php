<?php
$id_deuda = $_GET['id'] ?? null;
$fecha_refuerzo = $_GET['fecha_refuerzo'] ?? null;
$frecuencia = $_GET['frecuencia_pagos'] ?? strtolower($deuda->frecuencia_pagos ?? '');

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
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($this->model->listarDeudas($id_deuda) as $deuda):
            $valorCuota = $deuda->saldo / $deuda->cuotas;
            $saldoRestante = $deuda->saldo;
            $fechaBase = new DateTime($deuda->vencimiento);
            $hoy = new DateTime();
            $montoRefuerzoTotal = $deuda->montoRefuerzo;
            $cantidadRefuerzo = $deuda->cantidadRefuerzo;
            $fechaRefuerzo = new DateTime($deuda->fecha_refuerzo);
            $montoRefuerzoUnitario = ($cantidadRefuerzo > 0) ? ($montoRefuerzoTotal / $cantidadRefuerzo) : 0;
            $contadorRefuerzos = 0;
            $cuotasPagadas = 0;
            $sumaRefuerzos = 0;
            $frecuencia = strtolower($deuda->frecuencia_pagos);

            while ($cuotasPagadas < $deuda->cuotas):
                $fechaCuota = clone $fechaBase;
                $fechaCuota->modify("+$cuotasPagadas months");

                $mostrarFechaRefuerzo = "-";
                $mostrarMontoRefuerzo = "-";
                $esRefuerzo = false;

                if ($cantidadRefuerzo > 0 && $fechaCuota >= $fechaRefuerzo) {
                    switch ($frecuencia) {
                        case 'trimestral':
                            $intervalo = '3 months';
                            break;
                        case 'semestral':
                            $intervalo = '6 months';
                            break;
                        case 'novenal':
                            $intervalo = '9 months';
                            break;
                        case 'Anual':
                            $intervalo = '12 months';
                            break;
                        default:
                            $intervalo = '12 months';
                    }

                    if ($fechaCuota->format('Y-m-d') == $fechaRefuerzo->format('Y-m-d') && $contadorRefuerzos < $cantidadRefuerzo) {
                        $mostrarFechaRefuerzo = $fechaRefuerzo->format('d/m/Y');
                        $mostrarMontoRefuerzo = number_format($montoRefuerzoUnitario, 0, ',', '.');
                        $esRefuerzo = true;
                        $sumaRefuerzos += $montoRefuerzoUnitario;
                        $fechaRefuerzo->modify($intervalo);
                        $contadorRefuerzos++;
                    }
                }

                if (!$esRefuerzo) {
                    $saldoRestante -= $valorCuota;
                    $cuotasPagadas++;
                }

                $diferenciaDias = $hoy->diff($fechaCuota)->days;
                $interes = 0;
                $nuevoPago = $valorCuota;

                if ($diferenciaDias > 5 && $hoy > $fechaCuota && !$esRefuerzo) {
                    $interes = $valorCuota * 0.03;
                    $nuevoPago += $interes;
                }

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
            <td>
                <?php if (!$esRefuerzo && $nuevoPago > 0): ?>
                    <button class="btn btn-success btn-sm marcar-pagado"
                            data-id-deuda="<?php echo $deuda->id; ?>"
                            data-cuota="<?php echo $cuotasPagadas; ?>">
                        Pagar
                    </button>
                <?php else: ?>
                    <span class="text-muted">Pagado</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php
            endwhile;
        ?>
        <tr style="background-color: #f8f9fa; font-weight:bold;">
            <td colspan="7" style="text-align: right;">Total Refuerzos:</td>
            <td><?php echo number_format($sumaRefuerzos, 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</div>

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
            $id_deuda = $_GET['id']; 
            

            foreach($this->model->listarDeudas($id_deuda) as $deuda): 
                $valorCuota = $deuda->saldo / $deuda->cuotas;
                $saldoRestante = $deuda->saldo;
                $fechaVencimiento = new DateTime($deuda->fecha_pago_cuota);
                $hoy = new DateTime();
                
                // Datos de refuerzo
                $montoRefuerzoTotal = $deuda->montoRefuerzo;
                $cantidadRefuerzo = $deuda->cantidadRefuerzo;
                $fechaRefuerzo = new DateTime($deuda->fecha_refuerzo);
                $montoRefuerzoUnitario = ($cantidadRefuerzo > 0) ? ($montoRefuerzoTotal / $cantidadRefuerzo) : 0;
                $contadorRefuerzos = 0;
                
                // NUEVAS VARIABLES para resumen
                $totalCuotas = 0;
                $totalMontoCuotas = 0;
                $totalRefuerzos = 0;
                $totalMontoRefuerzos = 0;

                $cuotasGeneradas = 0;
                while ($cuotasGeneradas < $deuda->cuotas):
                    $fechaVencimiento->modify('+1 month');

                    // Verificar si toca refuerzo
                    $esRefuerzo = false;
                    if ($cantidadRefuerzo > 0 && $fechaVencimiento->format('Y-m-d') == $fechaRefuerzo->format('Y-m-d') && $contadorRefuerzos < $cantidadRefuerzo) {
                        $esRefuerzo = true;
                    }

                    if ($esRefuerzo) {
                        // Mostramos solo refuerzo (sin cuota)
                        $mostrarFechaRefuerzo = $fechaRefuerzo->format('d/m/Y');
                        $mostrarMontoRefuerzo = number_format($montoRefuerzoUnitario, 0, ',', '.');
                        $estiloFila = 'style="background-color: #d4edda;"';
                        
                        // SUMAMOS refuerzo
                        $totalRefuerzos++;
                        $totalMontoRefuerzos += $montoRefuerzoUnitario;

                        $contadorRefuerzos++;

                        // Avanzamos fecha refuerzo para el siguiente (cada 12 meses)
                        $fechaRefuerzo->modify('+12 months');

                        ?>
                        <tr <?php echo $estiloFila; ?>>
                            <td><?php echo htmlspecialchars($deuda->cliente_nombre); ?></td>
                            <td><?php echo $fechaVencimiento->format('d/m/Y'); ?></td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td><?php echo $mostrarFechaRefuerzo; ?></td>
                            <td><?php echo $mostrarMontoRefuerzo; ?></td>
                        </tr>
                        <?php

                        continue; // saltamos cuota normal en fecha de refuerzo
                    }

                    // Cuota normal
                    $cuotasGeneradas++;
                    $saldoRestante -= $valorCuota;

                    $diferenciaDias = $hoy->diff($fechaVencimiento)->days; 
                    $interes = 0;
                    $nuevoPago = $valorCuota;

                    if ($diferenciaDias > 5 && $hoy > $fechaVencimiento) {
                        $interes = $valorCuota * 0.03;
                        $nuevoPago += $interes;
                    }

                    // SUMAMOS cuota
                    $totalCuotas++;
                    $totalMontoCuotas += $valorCuota;

                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($deuda->cliente_nombre); ?></td>
                        <td><?php echo $fechaVencimiento->format('d/m/Y'); ?></td>
                        <td><?php echo number_format($valorCuota, 0, ',', '.'); ?></td>
                        <td><?php echo number_format(max($saldoRestante, 0), 0, ',', '.'); ?></td>
                        <td>
                            <?php 
                            if ($interes > 0) {
                                echo number_format($interes, 0, ',', '.');
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td><?php echo number_format($nuevoPago, 0, ',', '.'); ?></td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                <?php endwhile; endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ESTADO DE LA DEUDA -->
<div class="row" style="margin-top: 30px;">
    <div class="col-md-3">
        <div class="alert alert-primary">
            <h5>Total Cuotas</h5>
            <h4><?php echo $totalCuotas; ?> cuotas</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="alert alert-success">
            <h5>Total Monto Cuotas</h5>
            <h4><?php echo number_format($totalMontoCuotas, 0, ',', '.'); ?> Gs</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="alert alert-warning">
            <h5>Total Refuerzos</h5>
            <h4><?php echo $totalRefuerzos; ?> refuerzos</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="alert alert-danger">
            <h5>Total Monto Refuerzos</h5>
            <h4><?php echo number_format($totalMontoRefuerzos, 0, ',', '.'); ?> Gs</h4>
        </div>
    </div>
</div>

<div class="alert alert-info text-center" style="margin-top: 20px;">
    <h4>Total General: <?php echo number_format(($totalMontoCuotas + $totalMontoRefuerzos), 0, ',', '.'); ?> Gs</h4>
</div>

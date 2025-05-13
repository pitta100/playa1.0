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
                    $fechaVencimiento = new DateTime($deuda->fecha);
                    $hoy = new DateTime();
                    
                    // Datos de refuerzo
                    $montoRefuerzoTotal = $deuda->montoRefuerzo;
                    $cantidadRefuerzo = $deuda->cantidadRefuerzo;
                    $fechaRefuerzo = new DateTime($deuda->fecha_refuerzo);
                    $montoRefuerzoUnitario = ($cantidadRefuerzo > 0) ? ($montoRefuerzoTotal / $cantidadRefuerzo) : 0;
                    $contadorRefuerzos = 0;

                    $cuotasPagadas = 0; // cuotas pagadas

                    while ($cuotasPagadas < $deuda->cuotas):
                        $fechaVencimiento->modify('+1 month');

                        // Por defecto vacíos los campos de refuerzo
                        $mostrarFechaRefuerzo = "-";
                        $mostrarMontoRefuerzo = "-";
                        $esRefuerzo = false;

                        // Verificamos si en este vencimiento toca refuerzo
                        if ($cantidadRefuerzo > 0 && $fechaVencimiento->format('Y-m-d') == $fechaRefuerzo->format('Y-m-d') && $contadorRefuerzos < $cantidadRefuerzo) {
                            // 💥 Es un mes de refuerzo
                            $mostrarFechaRefuerzo = $fechaRefuerzo->format('d/m/Y');
                            $mostrarMontoRefuerzo = number_format($montoRefuerzoUnitario, 0, ',', '.');
                            $esRefuerzo = true;

                            // Saltamos al próximo refuerzo
                            $fechaRefuerzo->modify('+12 months');
                            $contadorRefuerzos++;

                            // NO descontamos cuota ni saldoRestante
                        } else {
                            // 💥 Es un mes normal de cuota
                            $saldoRestante -= $valorCuota;
                            $cuotasPagadas++; // Solo contamos cuando es cuota normal
                        }

                        // Calculamos interés y nuevo pago
                        $diferenciaDias = $hoy->diff($fechaVencimiento)->days; 
                        $interes = 0;
                        $nuevoPago = $valorCuota;

                        if ($diferenciaDias > 5 && $hoy > $fechaVencimiento && !$esRefuerzo) {
                            $interes = $valorCuota * 0.03;
                            $nuevoPago += $interes;
                        }

                        // Estilo si es refuerzo
                        $estiloFila = $esRefuerzo ? 'style="background-color: #d4edda;"' : '';
                ?>
                <tr <?php echo $estiloFila; ?>>
                    <td><?php echo htmlspecialchars($deuda->cliente_nombre); ?></td>
                    <td><?php echo $fechaVencimiento->format('d/m/Y'); ?></td>
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
                endforeach; 
                ?>

        </tbody>
    </table>
</div>

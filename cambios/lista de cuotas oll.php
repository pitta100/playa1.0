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
                <th>Nuevo Pago</th> <!-- Nuevo campo para mostrar la cuota más el interés si corresponde -->
            </tr>
        </thead>
        <tbody>
            <?php  
            // Obtienes el id_deuda desde la URL
            $id_deuda = $_GET['id']; 

            // Iteras sobre las deudas obtenidas desde el modelo
            foreach($this->model->listarDeudas($id_deuda) as $deuda): 
                // Calculamos el valor de cada cuota
                $valorCuota = $deuda->saldo / $deuda->cuotas;  // Dividimos el saldo por las cuotas
                $saldoRestante = $deuda->saldo;  // Empezamos con el saldo original
                $fechaVencimiento = new DateTime($deuda->vencimiento);  // Fecha de la deuda
                $hoy = new DateTime();  // Fecha actual
                
                for ($i = 1; $i <= $deuda->cuotas; $i++): 
                    // Sumamos un mes al vencimiento de la cuota
                    $fechaVencimiento->modify('+1 month');
                    $saldoRestante -= $valorCuota;  // Restamos el valor de la cuota del saldo restante

                    // Verificamos si la cuota está vencida más de 10 días
                    $diferenciaDias = $hoy->diff($fechaVencimiento)->days; 
                    $interes = 0;
                    $nuevoPago = $valorCuota; // Inicializamos el Nuevo Pago con el valor de la cuota

                    // Solo aplicamos el interés si ha pasado más de 10 días después de la fecha de vencimiento
                    if ($diferenciaDias > 5 && $hoy > $fechaVencimiento) {
                        $interes = $valorCuota * 0.03;  // 3% de la cuota
                        $nuevoPago += $interes;  // Sumamos el interés al valor de la cuota
                    }
            ?>
            <tr>
                <td><?php echo ($deuda->cliente_nombre); ?></td> <!-- Muestra el nombre del cliente -->
                <td><?php echo $fechaVencimiento->format('d/m/Y'); ?></td> <!-- Muestra la fecha de vencimiento -->
                <td><?php echo number_format($valorCuota, 0, ',', '.'); ?></td> <!-- Muestra el valor de la cuota -->
                <td><?php echo number_format($saldoRestante, 0, ',', '.'); ?></td> <!-- Muestra el saldo restante -->
                <td>
                    <?php 
                    // Solo muestra el interés si es mayor que 0
                    if ($interes > 0) {
                        echo number_format($interes, 0, ',', '.');
                    } else {
                        echo '-';  // Si no hay interés, muestra un guion
                    }
                    ?>
                </td> <!-- Muestra el interés generado -->
                <td><?php echo number_format($nuevoPago, 0, ',', '.'); ?></td> <!-- Muestra el Nuevo Pago (cuota + interés si aplica) -->
            </tr>
            <?php endfor; endforeach; ?>
        </tbody>
    </table>
</div>

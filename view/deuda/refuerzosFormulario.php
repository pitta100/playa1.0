<form method="post">
                <input type="hidden" name="c" value="deuda">
                <input type="hidden" name="a" value="Cobrar">
                <input type="hidden" name="id" value="<?php echo $r->id ?>">
                <input type="hidden" name="id_cliente" value="<?php echo $r->id_cliente ?>">
                <input type="hidden" name="id_venta" value="<?php echo $r->id_venta ?>">
                <input type="hidden" name="cli" value="<?php echo $r->nombre ?>">
                <h3>Cobro por <?php echo $r->concepto ?></h3>
                <br>
                <h3>Total de cuotas <?php echo $r->cantidadRefuerzo ?></h3>
                <br>
                <h4>Saldo: <?php echo number_format($r->montoRefuerzo,0, ",", ",") ?></h4>
                <br>
                <h4>Vencimiento: <?php echo date("d/m/Y", strtotime($r->fecha_refuerzo)); ?></h4>
                <br>
                <div class="form-group" id="nro_comprobante">
                    <label>Monto refuerzo!</label>
                    <input type="number" name="montoRefuerzo" min="0"  max="<?php echo $r->montoRefuerzo ?>"  class="form-control">
                </div>
                <!-- Campo de intereses agregado -->
                <div class="form-group" id="nro_comprobante">
                    <label>cantidad de cuotas </label>
                    <input type="number" name="cantidadRefuerzo" min="1" max="<?php echo $r->cantidadRefuerzo ?>"  class="form-control">
                </div>

                <div class="form-group">
                    <label>Fecha de vencimiento</label>
                    <input type="datetime-local" id="venci" name="fecha_refuerzo" class="form-control" value="<?php echo date("Y-m-d") ?>T<?php echo date("H:i") ?>">
                </div>
                <div class="form-group">
                    <label>Método de pago</label>
                    <select name="forma_pago" class="form-control">
                        <option value="Efectivo">Efectivo</option> 
                        <option value="Tarjeta">Tarjeta</option> 
                        <option value="Cheque">Cheque</option> 
                    </select>
                </div>
                <div class="form-group" id="nro_comprobante">
                    <label>Nro. Comprobante !</label>
                    <input type="text" name="comprobante"  class="form-control" value="Recibo Nº ">
                </div>
                <div class="form-group">
                    <input type="submit" value="cobrar" class="btn btn-default">
                </div>
            
            </form>
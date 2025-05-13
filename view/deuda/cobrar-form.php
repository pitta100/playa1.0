<form method="post">
		    	<input type="hidden" name="c" value="deuda">
		    	<input type="hidden" name="a" value="Cobrar">
		    	<input type="hidden" name="id" value="<?php echo $r->id ?>">
		    	<input type="hidden" name="id_cliente" value="<?php echo $r->id_cliente ?>">
		    	<input type="hidden" name="id_venta" value="<?php echo $r->id_venta ?>">
		    	<input type="hidden" name="cli" value="<?php echo $r->nombre ?>">
		    	<h3>Cobro por <?php echo $r->concepto ?></h3>
		    	<br>
		    	<h3>Total de cuotas <?php echo $r->cuotas ?></h3>
		    	<br>
		    	<h4>Saldo: <?php echo number_format($r->saldo,0, ",", ",") ?></h4>
		    	<br>
		    	<h4>Fecha de tu cuota : <?php echo date("d", strtotime($r->vencimiento)); ?></h4>
		    	<br>
		  
		    	<div class="form-group" id="nro_comprobante">
		        	<label>Monto !</label>
		        	<input type="number" name="mon" min="0" max="<?php echo $r->saldo ?>"  class="form-control">
		    	</div>
		    	<!-- Campo de intereses agregado -->
			    <div class="form-group">
			        <label>Intereses</label>
			        <input type="number" name="inte" min="0" class="form-control" placeholder="Ingrese los intereses">
			    </div>
		    	<div class="form-group" id="nro_comprobante">
		        	<label>cantidad de cuotas </label>
		        	<input type="number" name="cuo" min="0" max="<?php echo $r->cuotas ?>"  class="form-control">
		    	</div>
		    	<div class="form-group">
				    <label for="fecha_pago">Fecha de pago</label>
				    <input type="datetime-local" name="fecha_pago"class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>">
				    <small class="form-text text-muted">Seleccione siempre la misma fecha de vencimiento de la cuota.</small>
   				</div>
				</div>
		    	<div class="form-group">
			        <label>Fecha de vencimiento</label>
			        <input type="datetime-local" id="venci" name="vencimi" class="form-control" value="<?php echo date("Y-m-d") ?>T<?php echo date("H:i") ?>">
			        <small class="form-text text-muted">Seleccione siempre la fecha del proximo vencimiento de la cuota.</small>
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
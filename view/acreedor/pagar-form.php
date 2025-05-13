<form method="get">
		    	<input type="hidden" name="c" value="acreedor">
		    	<input type="hidden" name="a" value="pagar">
		    	<input type="hidden" name="id" value="<?php echo $r->id ?>">
		    	<input type="hidden" name="id_cliente" value="<?php echo $r->id_cliente ?>">
		    	<input type="hidden" name="id_compra" value="<?php echo $r->id_compra ?>">
		    	<input type="hidden" name="cli" value="<?php echo $r->nombre ?>">
		    	<h3>Pago por <?php echo $r->concepto ?></h3>
		    	<br>
		    	<h4>Saldo: <?php echo number_format($r->saldo,0, ",", ",") ?></h4>
		    	<br>
		    	<div class="form-group" id="nro_comprobante">
		        	<label> <i class="fa-solid fa-money-check-dollar"></i> Monto</label>
		        	<input type="number" name="mon" min="0" max="<?php echo $r->saldo ?>"  class="form-control">
		    	</div>
		    	<div class="form-group">
					<label> <i class="fa-brands fa-cc-paypal"></i> Método de pago</label>
					<select name="forma_pago" class="form-control">
						<option value="Efectivo">Efectivo</option> 
						<option value="Tarjeta">Tarjeta</option> 
						<option value="Cheque">Cheque</option> 
					</select>
				</div>
				<div class="form-group" id="nro_comprobante">
		        	<label> <i class="fa-solid fa-receipt"></i> Nro. Comprobante</label>
		        	<input type="text" name="comprobante"  class="form-control" value="Recibo Nº ">
		    	</div>
		    	<div class="form-group">
		    		<i class="fa-solid fa-handshake"></i>
		        	<input type="submit" value="pagar" class="btn btn-default">
		    	</div>
			
          	</form>
          	<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>

</dir>
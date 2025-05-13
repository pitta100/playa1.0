<div id="finalizarModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
			<div class="modal-body">
				<form method="post" action="?c=compra&a=guardar">
				    
				    <div class="form-group" id="nro_comprobante">
				        <label> <i class="fa-solid fa-language"></i> ID de la compra</label>
				        <h4><?php echo $r->id_compra; ?></h4>
				    </div>
				    
					<div class="form-group" id="nro_comprobante">
				        <label> <i class="fa-regular fa-calendar-days"></i> Fecha de compra</label>
				        <input type="datetime-local" name="fecha_compra" class="form-control" value="<?php echo date("Y-m-d") ?>T<?php echo date("H:i") ?>">
				    </div>
				    
					<div class="form-group">
						<label> <i class="fa-solid fa-truck-fast"></i> Proveedor</label>
					    <select name="id_cliente" id="id_cliente" class="form-control" data-show-subtext="true" data-live-search="true" data-style="form-control" autofocus="autofocus">
							<option value="2">Proveedor sin nombre</option>
				        	<?php foreach($this->cliente->Listar() as $clie): ?> 
				        	<option value="<?php echo $clie->id; ?>"><?php echo $clie->nombre." ( ".$clie->ruc." )"; ?> </option>
				        	<?php endforeach; ?>
				        </select>
			    	</div>  

                    <div class="form-group">
						<label> <i class="fa-solid fa-receipt"></i> Comprobante</label>
						<select name="comprobante" id="comprobante" class="form-control">
							<option value="Ticket">Ticket</option> 
							<option value="Factura">Factura</option> 
						</select>
				    </div>
				    
				    <div class="form-group" id="nro_comprobante">
				        <label> <i class="fa-solid fa-receipt"></i> Nro. comprobante</label>
				        <input type="text" name="nro_comprobante" class="form-control" placeholder="Ingrese el nro de comprobante">
				    </div>

				    <div class="form-group">
						<label>Método de pago</label>
						<select name="pago" id="pago" class="form-control">
							<option value="Efectivo">Efectivo</option> 
							<option value="Transferencia">Transferencia</option>
							<option value="Cheque">Cheque</option> 
						</select>
				    </div>
				     <div class="form-group" id="nro_cheque" style="display: none;">
				        <label>Nro. Cheque</label>
				        <input type="text" name="nro_cheque" class="form-control" placeholder="Ingrese el nro del cheque">
				    </div>

				    <div class="form-group" id="banco" style="display: none;">
				        <label>Banco</label>
				        <input type="text" name="banco" class="form-control" placeholder="Ingrese nombre de banco">
				    </div>
				     <div class="form-group" id="plazo" style="display: none;">
				        <label>Plazo</label>
				        <input type="date" name="plazo" class="form-control" placeholder="Ingrese el plazo">
				    </div>

				    <div class="form-group">
						<label>Pago</label>
						<select name="contado" id="contado" class="form-control"> 
							<option value="Contado">Contado</option>
							<option value="Credito">Crédito</option>
						</select>
				    </div>
				    
				    <div class="form-group" id="entrega" style="display: none;">
				        <label>Entrega</label>
				        <input type="number" name="entrega" min="0" max="<?php echo $subtotal ?>" class="form-control" value="0" placeholder="Ingrese entrega">
				    </div>

				    <input type="hidden" name="subtotal" value="<?php echo $subtotal ?>">
				    <input type="hidden" name="total" class="totaldesc" id="totaldesc" value="<?php echo $subtotal ?>">
				    <input type="hidden" name="descuentoval" id="descuentoval" value="0">
				    <input type="hidden" name="ivaval" id="ivaval" value="0">
				    <input type="hidden" name="id_vendedor" value="12">
				    <input type="submit" class="btn btn-success" value="Finalizar compra">
				</form>

            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
            </div>
            
        </div>
    </div>
</div>

<script>
	$('#pago').on('change',function(){
		var valor = $(this).val();
		if (valor == "Cheque") {
			$("#banco").show();
			$("#plazo").show();
			$("#nro_cheque").show();
		}else{
			$("#banco").hide();
			$("#plazo").hide();
			$("#nro_cheque").hide();
		}
	});
	$('#contado').on('change',function(){
		var valor = $(this).val();
		if (valor == "Cuota") {
			$("#entrega").show();
		}else{
			$("#entrega").hide();
		}
	});
</script>
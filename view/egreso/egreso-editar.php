<ol class="breadcrumb">
  <li><a class="btn btn-success pull-right" href="?c=cliente"> <i class="fa-solid fa-arrow-right-from-bracket"></i>Agregar Nuevo Cliente.</a></li>
</ol>

<h1 class="page-header">
    <?php echo $egreso->id != null ? $egreso->fecha : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=egreso">GeoCad sector egreso</a></li>
  <li class="active"><?php echo $egreso->id != null ? $egreso->fecha : 'Nuevo Registro'; ?></li>
</ol>

<form id="crud-frm" method="post" action="?c=egreso&a=guardar" enctype="multipart/form-data">
    <input type="hidden" name="c" value="egreso" id="c"/>
    <input type="hidden" name="id" value="<?php echo $egreso->id; ?>" id="id" />
    <div class="form-group">
        <label> <i class="fa-regular fa-calendar-days"></i> Fecha</label>
        <input type="datetime-local" name="fecha" value="<?php echo (!$egreso->fecha)? (date("Y-m-d")."T".date("H:i")) : date("Y-m-d", strtotime($egreso->fecha))."T".date("H:i", strtotime($egreso->fecha)); ?>" class="form-control" placeholder="Fecha" required>
    </div>

    <input type="hidden" name="id_cliente" value="0">    
    <div class="form-group" >
		<label> <i class="fa-solid fa-truck-fast"></i>  Proveedor</label>
        <select name="id_cliente" id="cliente" class="form-control selectpickers" data-show-subtext="true" data-live-search="true" data-style="form-control"
                                title="-- Seleccione al proveedor --" autofocus>
            <option value="1" selected>Proveedor ocasional</option>
                            <?php foreach($this->cliente->Listar() as $cliente): ?> 
            <option data-subtext="<?php echo $cliente->ruc; ?>" value="<?php echo $cliente->id; ?>"<?php echo ($cliente->id ==$egreso->id_cliente)? "selected":""; ?>><?php echo $cliente->nombre; ?> </option>
                            <?php endforeach; ?>
                        </select>
	</div>

    <div class="form-group">
        <label> <i class="fa-solid fa-sitemap"></i> Código</label>
        <input type="text" name="categoria" value="<?php echo $egreso->categoria; ?>" class="form-control" placeholder="Ingrese la categoria" required>
    </div>

    <div class="form-group">
        <label> <i class="fa-regular fa-pen-to-square"></i> Técnico</label>
        <input type="text" name="concepto" value="<?php echo $egreso->concepto; ?>" class="form-control" placeholder="Ingrese su concepto" required>
    </div>
    
    <div class="form-group">
        <label> <i class="fa-solid fa-receipt"></i>  Comprobante</label>
        <input type="text" name="comprobante" value="<?php echo $egreso->comprobante; ?>" class="form-control" placeholder="Ingrese su comprobante" required>
    </div>
    
    <div class="form-group">
        <label> <i class="fa-solid fa-money-check-dollar"></i> Monto</label>
        <input type="number" name="monto" value="<?php echo $egreso->monto; ?>" class="form-control" placeholder="Ingrese el monto" min="0" required>
    </div>

    <div class="form-group" >
        <label> <i class="fa-brands fa-cc-paypal"></i>  Forma de pago</label>
        <select name="forma_pago" id="pago" class="form-control">
            <option value="Efectivo" <?php echo ($egreso->forma_pago == "Efectivo")? "selected":""; ?>>Efectivo</option>
            <option value="Cheque" <?php echo ($egreso->forma_pago == "Cheque")? "selected":""; ?>>Cheque</option>
            <option value="Transferencia" <?php echo ($egreso->forma_pago == "Transferencia")? "selected":""; ?>>Transferencia</option>
        </select>
    </div>
     <div class="form-group"id="nro_cheque"style="display: none;" >
        <label>Nro Cheque</label>
        <input type="text" name="nro_cheque" id="cheque" value="<?php echo $egreso->nro_cheque; ?>" class="form-control" placeholder="Ingrese el comprobante"  >
    </div>
    <div class="form-group" id="plazo" style="display: none;">
        <label>Plazo</label>
        <input type="date" name="plazo" id="plazo" value="<?php echo $egreso->plazo; ?>" class="form-control" placeholder="Ingrese plazo"  >
    </div>
    
    <input type="hidden" name="sucursal" value="0">
    
    <hr />
    
    <div class="text-right">
        <button class="btn btn-success"><i class="fa-solid fa-cloud-arrow-up"></i> Guardar</button>
    </div>
</form>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>

</dir>


<script>
	$('#pago').on('change',function(){
		var valor = $(this).val();
		if (valor == "Cheque") {
			
			$("#plazo").show();
			$("#nro_cheque").show();
		}else{
		
			$("#plazo").hide();
			$("#nro_cheque").hide();
		}
	});

</script>

<?php 
$monto_venta = $this->venta_tmp->Obtener();
$monto_pago =  $this->pago_tmp->Obtener();

$saldo = $monto_venta->monto - $monto_pago->monto;

?>
<div class="form-group" id="banco" style="display: none;">
    <label>Banco</label>
    <input type="text" name="banco" class="form-control" placeholder="Ingrese nombre de banco">
</div>
<input type="hidden" name="subtotal" value="<?php echo $subtotal ?>">
<input type="hidden" name="total" class="totaldesc" id="totaldesc" value="<?php echo $subtotal ?>">
<input type="hidden" name="descuentoval" id="descuentoval" value="0">
<input type="hidden" name="ivaval" id="ivaval" value="0">
<input type="hidden" name="id_vendedor" value="12">
<input type="hidden"  id="sub" value="<?php echo $monto_venta->monto ?>">
<div class="form-group">
    <label>Monto a cubrir</label>
    <h3><?php echo number_format($monto_venta->monto,0,".",".") ?></h3>
     <label>MONTO PAGADO</label>
	<input type="text" class="form-control" id="monto_efectivo" placeholder="Ingrese el monto de pago">
</div>
<div class="form-group">
	<label>VUELTO</label>
	<h2 id="vuelto"></h2>
</div>
<?php if ($saldo==0): ?>
    <div align="center">
        <input type="submit" class="btn btn-primary" value="Finalizar venta">
    </div>
<?php endif ?>
    <div align="center" style="display: none;" id="fin">
          <input type="submit" class="btn btn-primary" value="Finalizar venta">
    </div>
</form>
<br>
<div id="creditos">
<label>Pagos</label>
<?php if ($saldo!=0): ?>
<form method="POST" id="pago_frm">
<div class="container">
  <div class="row">
    <div class="col-sm-2">
        <select name="pago" class="form-control" id="pago">
            <?php foreach ($this->metodo->Listar() as $m): ?>
            <option value="<?php echo $m->metodo ?>"><?php echo $m->metodo ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-sm-2">
        <input type="text" name="monto" id="monto" class="form-control" value="<?php echo $saldo ?>" placeholder="Ingrese el Monto">
    </div>
    <div class="col-sm-2">
        <input class="btn btn-primary" type="submit" value="Agregar pago">
    </div>
  </div>
</div>

</form>
<?php endif ?>
<br>
<table class="table table-striped table-bordered display responsive nowrap" width="100%" id="tabla">
    <thead>
        <tr style="background-color: #000; color:#fff">
            <th>Pago</th>
            <th>Monto</th>        
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php $sumaTotal=0;foreach($this->pago_tmp->Listar() as $r): ?>
        <tr class="click">
            <td><?php echo $r->pago; ?></td>
            <td><?php echo number_format($r->monto, 0,".","."); ?></td>
            <td>
                <a class="btn btn-danger eliminar" id_pago="<?php echo $r->id; ?>" >Eliminar</a>
            </td>
        </tr>
    <?php $sumaTotal+=$r->monto; endforeach; ?>
    </tbody>
    <tfoot>
        <tr style="background-color: #000; color:#fff">
            <th>Total cubierto</th>
            <th><?php echo number_format($sumaTotal, 0,".","."); ?></th>        
            <th></th>
        </tr>
    </tfoot>
</table>
</div>

<script>
    
    $('#pago_frm').on('submit',function(e){
        e.preventDefault();
        var pago = $("#pago").val();
        var monto = $("#monto").val();
        var url = "?c=pago_tmp&a=guardar&pago="+pago+"&monto="+monto;
            $.ajax({

                url: url,
                method : "POST",
                data: pago,
                success:function(respuesta){
                    $("#pagos").html(respuesta);
                    $("#monto").focus();
                    $('.selectpicker').selectpicker();
                }

            })
    });

    $('.eliminar').on('click',function(){

        var id = $(this).attr("id_pago");
        var monto = $("#monto").val();
        var url = "?c=pago_tmp&a=eliminar&id="+id;

        $.ajax({

            url: url,
            method : "POST",
            data: id,
            success:function(respuesta){
                $("#pagos").html(respuesta);
                $('.selectpicker').selectpicker();
            }

        })
        
    });
    $('#monto_efectivo').on('keyup',function(){
		var valor = parseInt($(this).val());
		var total = $("#sub").val();
		var vuelto = valor - total;
		$("#vuelto").html((vuelto).toLocaleString('de-DE'));
	});
    
</script>
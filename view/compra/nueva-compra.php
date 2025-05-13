 
<?php $fecha = date("Y-m-d"); ?>
<h1 class="page-header">Nueva compra <a class="btn btn-success" href="#productoModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="producto"> cargar un nuevo vehiculo</a> </h1>
<div class="container">
    <div class="row">
        <form method="post" action="?c=compra_tmp&a=guardar">

        
        <div class="col-sm-4">
            <label> <i class="fa-brands fa-buromobelexperte"></i> Producto </label>
        <select name="id_producto" id="producto" class="form-control selectpicker" data-show-subtext="true" data-live-search="true" data-style="form-control"
                title="-- Seleccione el producto --" autofocus required>
            <?php foreach($this->producto->Listar() as $producto): ?> 
                <option style="font-size: 18px"
                        data-subtext="<?php echo $producto->marcaVehiculo . ' | ' . $producto->modelo . ' | ' . $producto->anio; ?>" value="<?php echo $producto->id; ?>"><?php echo $producto->producto . ' ( ' . $producto->stock . ' ) - ' . number_format($producto->precio_minorista, 0, ".", ".") .
                                ' | Modelo: ' . $producto->modelo .
                                ' | Año: ' . $producto->anio .
                                ' | Color: ' . $producto->color .
                                ' | Placa: ' . $producto->placa .
                                ' | VIN: ' . $producto->vin; ?>
                </option>
            <?php endforeach; ?>
        </select>
        </div>
        <div class="col-sm-2">
            <label> <i class="fa-solid fa-box-archive"></i> Cantidad</label>
            <input type="number" name="cantidad" class="form-control" id="cantidad" value="" min="1" required step="any">   
        </div>
        <div class="col-sm-2">
            <label> <i class="fa-solid fa-chart-line"></i>  Precio de compra</label>
            <input type="number" value="0" name="precio_compra" id="precio_compra" class="form-control" min="0">
            <input type="submit" name="bton" style="display: none">
        </div>
        <div class="col-sm-2">
            <label> <i class="fa-solid fa-hand-holding-dollar"></i> Precio de venta</label>
            <input type="number" value="0" id="precio_min" name="precio_min" class="form-control" min="0">   
        </div>
        <div class="col-sm-2" style="display:none">
            <label>Mayorista</label>
            <input type="number" value="0" id="precio_may" name="precio_may" class="form-control" min="0">   
        </div>
    </form>
    </div>
</div>
<p> </p>
<div class="table-responsive">

<table class="table table-striped table-bordered display responsive nowrap" width="100%" id="tabla1">

    <thead>
        <tr style="background-color: #000; color:#fff">
            <th> <i class="fa-brands fa-buromobelexperte"></i>  Producto</th>
            <th> <i class="fa-solid fa-hand-holding-dollar"></i> Precio de venta</th>
            <th>Precio por Unidad</th>
            <th> <i class="fa-solid fa-box-archive"></i>  Cantidad</th>
            <th> <i class="fa-solid fa-calculator"></i> Total (Gs.)</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
     $subtotal=0;
     foreach($this->model->Listar() as $r): 
        $totalItem = $r->precio_compra*$r->cantidad;
        $subtotal += ($totalItem);?>
        <tr>
            
            <td><?php echo $r->producto; ?></td>
            <td><?php echo number_format($r->precio_min, 0, "," , "."); ?></td>
            <td><?php echo number_format($r->precio_compra, 0, "," , "."); ?></td>
            <td><?php echo $r->cantidad; ?></td>
            <td><div id="precioTotal<?php echo $r->id; ?>" class="total_item">
                <?php echo number_format( $totalItem, 0, "," , "."); ?></div></td>
            <td>
                <a  class="btn btn-danger" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=compra_tmp&a=Eliminar&id=<?php echo $r->id; ?>">Cancelar</a>
            </td>
        </tr>
    <?php endforeach; ?>
        
        
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Total Gs: <div id="total" style="font-size: 30px"><?php echo number_format($subtotal,0,",",".") ?></div></td>
            <td></td>
        </tr>
    </tbody>
</table> 
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina -P & Q AUTOMOTORES SA.<?php echo date("Y") ?></a></li>
    </ul>

</dir>
<?php if($subtotal>0){ ?>
<div align="center"><a class="btn btn-lg btm btn-success " href="#finalizarModal" class="btn btn-success" data-toggle="modal" data-target="#finalizarModal" data-c="compra">Finalizar (F4)</a></div>
<?php } ?>
</div>
</div> 
</div>
</div>

<?php include("view/compra/finalizar-modal.php"); ?>
<?php include("view/crud-modal.php"); ?>

<script type="text/javascript">


    $('#producto').on('change',function(){
        var id = $(this).val();
        var url = "?c=producto&a=Buscar&id="+id;
            $.ajax({

                url: url,
                method : "POST",
                data: id,
                cache: false,
                contentType: false,
                processData: false,
                success:function(respuesta){
                    var producto = JSON.parse(respuesta);
                    $("#precio_compra").val(producto.precio_costo);
                    $("#precio_min").val(producto.precio_minorista);
                    $("#precio_may").val(producto.precio_mayorista);
                    $("#cantidad").focus();
                }

            })
    });

    function calcular(){
        var subtotal = $('#subtotal').val();
        var descuento = $('#descuento').val();
        var iva = $('#iva').val(); 
        var reales = $('#reales').val();
        var dolares = $('#dolares').val();       
        $('#descuentoval').val(descuento); 
        $('#ivaval').val(iva);
        if(descuento==0 && iva==0){
            var total = subtotal;
        }
        if(descuento==0 && iva!=0){
            var ivac = parseInt(subtotal * (iva/100));
            var total = parseInt(subtotal) + ivac;
        }
        if(descuento!=0 && iva==0){
            var total = subtotal - (subtotal * (descuento/100));
        }
        if(descuento!=0 && iva!=0){
            var ivac = parseInt(subtotal * (iva/100));
            var num = parseInt(subtotal) + ivac;
            var total = num - (subtotal * (descuento/100));
        }
        var totalrs = (total/reales).toFixed(2);
        var totalus = (total/dolares).toFixed(2);
        var totalc = total.toLocaleString();

        $('.totaldesc').val(totalc);
        $('#totalrs').val(totalrs);
        $('#totalus').val(totalus);
    }


</script>
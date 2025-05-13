<?php 
$licenciaPath = __DIR__ . '/../../../licencia/licencia.key';
$claveEsperada = 'p&8automotoressapitta100198830';

// Verifica si el archivo existe y si contiene la clave correcta
if (!file_exists($licenciaPath) || trim(file_get_contents($licenciaPath)) !== $claveEsperada) {
    die('🔒 Acceso denegado. Esta instalación no está autorizada.');
}
?> 
<?php $fecha = date("Y-m-d"); ?>
<h1 class="page-header">Nueva venta de servicios <a class="btn btn-lg btn-success pull-right" href="#cierreModal" class="btn btn-success" data-toggle="modal" data-target="#cierreModal" data-c="venta"> GeoCad cierre de caja</a></h1>
<div class="container">
    <div class="row" >
        <form method="post" id="productoNuevo">
            <div class="col-sm-3">
                    <label> <i class="fa-brands fa-buromobelexperte"></i> Producto</label>
                <select name="id_producto" id="producto" class="form-control selectpicker" data-show-subtext="true" data-live-search="true" data-style="form-control"
                        title="-- Seleccione el producto --" autofocus required>
                    <?php foreach($this->producto->Listar() as $producto): ?> 
                        <option style="font-size: 18px"
                                data-subtext="<?php echo $producto->marcaVehiculo . ' | ' . $producto->modelo . ' | ' . $producto->anio; ?>"
                                value="<?php echo $producto->id; ?>"
                                <?php echo ($producto->stock < 1) ? 'disabled' : ''; ?>>
                            <?php echo $producto->producto . ' ( ' . $producto->stock . ' ) - ' . number_format($producto->precio_minorista, 0, ".", ".") .
                                        ' | Modelo: ' . $producto->modelo .
                                        ' | Año: ' . $producto->anio .
                                        ' | Color: ' . $producto->color .
                                        ' | Placa: ' . $producto->placa .
                                        ' | VIN: ' . $producto->vin; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-3">
                <label> <i class="fa-solid fa-box-archive"></i> Cantidad</label>
                <input type="number" name="cantidad" class="form-control" id="cantidad" value="1" step="any" min="0" max="100">   
            </div>
            <!--<div class="col-sm-3">
                <label> <i class="fa-solid fa-comment-dollar"></i> Precio</label>
                <select name="precio_venta" class="form-control" id="precio_venta">
                    <option id="precio_minorista" value=""> -- Seleccionar --</option>
                </select>
            </div>-->
            <div class="col-sm-3">
                <label><i class="fa-solid fa-comment-dollar"></i> Precio</label>
                <select name="precio_venta" class="form-control" id="precio_venta" required>
                    <option value="">-- Seleccione el precio --</option>
                    <option value="" id="opcion_minorista"></option>
                    <option value="" id="opcion_financiado"></option>
                </select>
            </div>

            <div class="col-sm-3">
                <label> <th><i class="fa-solid fa-money-bill-1-wave"></i> Descuento (%)</label>
                <input type="number" name="descuento" class="form-control" id="descuento" value="0">
                <input type="submit" name="bton" style="display: none">   
            </div>
             <!-- Campos ocultos para los valores de financiación -->
            <input type="hidden" name="entrega" id="entrega" value="0">
            <input type="hidden" name="cuota_vehiculo" id="cuota_vehiculo" value="1">
            <input type="hidden" name="monto_refuerzo" id="monto_refuerzo" value="0">
            <input type="hidden" name="cantidad_refuerzo" id="cantidad_refuerzo" value="1">

        </form>
    </div>
</div>
<p> </p>
<div class="table-responsive" id="tabla_items">

<table class="table table-striped table-bordered display responsive nowrap" width="100%" id="tabla1">

    <thead>
        <tr style="background-color: #000; color:#fff">
            <th><i class="fa-solid fa-money-bill-1-wave"></i> Codigo</th>
            <th> <i class="fa-brands fa-buromobelexperte"></i> Producto</th>
            <th> <i class="fa-solid fa-comment-dollar"></i> Precio por Unidad</th>
            <th> <i class="fa-solid fa-box-archive"></i>  Cantidad</th>
            <th> <i class="fa-solid fa-money-bill-1-wave"></i> Descuento (%)</th>
            <th>Total (Gs.)</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
     $subtotal=0;
     foreach($this->model->Listar() as $r): 
        $totalItem = (($r->precio_venta*$r->cantidad)-(($r->precio_venta*$r->cantidad)*($r->descuento/100)));
        $subtotal += ($totalItem);?>
        <tr>
            <td><?php echo $r->codigo; ?></td>
            <td><?php echo $r->producto; ?></td>
            <td><?php echo number_format($r->precio_venta, 0, "," , "."); ?></td>
            <td><?php echo $r->cantidad; ?></td>
            <td><?php echo $r->descuento; ?></td>
            <td><div id="precioTotal<?php echo $r->id; ?>" class="total_item">
                <?php echo number_format( $totalItem, 0, "," , "."); ?></div></td>
            <td>
                <a  class="btn btn-danger cancelar" id_item="<?php echo $r->id; ?>">Cancelar</a>
            </td>
        </tr>
        <input type="hidden" id="clienteId" value="<?php echo $r->id_venta; ?>">
    <?php endforeach; ?>
        
        
        <tr>
            <td></td>
            <td></td>
            <td>Total Gs: <div id="total" style="font-size: 30px"><img src="http://www.customicondesign.com/images/freeicons/flag/round-flag/48/Paraguay.png"></i><?php echo number_format($subtotal,0,",",".") ?></div></td>
            <td>Total Rs: <div id="totalrs" style="font-size: 30px"><img src="http://www.customicondesign.com/images/freeicons/flag/round-flag/48/Brazil.png"></i><?php echo number_format(($subtotal/$cierre->cot_real), 2, "," , ".") ?></div></td>
            <td>Total Us: <div id="totalus" style="font-size: 30px"><img src="http://www.customicondesign.com/images/freeicons/flag/round-flag/48/USA.png"></i><?php echo number_format(($subtotal/$cierre->cot_dolar), 2, "," , ".") ?></div></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
<?php if($subtotal>0){ ?>
<div align="center"><a class="btn btn-lg btn-success " href="#finalizarModal" class="btn btn-success" data-toggle="modal" data-target="#finalizarModal" data-c="venta"> Finalizar (F4)</a></div>
<?php } ?>
</div>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina -P & Q AUTOMOTORES SA.<?php echo date("Y") ?></a></li>
    </ul>
</dir>
</div>
</div>
</div>
</div>
<?php include("view/venta/finalizar-modal.php"); ?>
</div>

<?php include("view/crud-modal.php"); ?>
<?php include("view/venta/cierre-modal.php"); ?>
<script type="text/javascript">



    $('.cancelar').on('click',function(){
        var datos = {};
        datos.id = $(this).attr("id_item");
        $.ajax({
            method : "POST",
            url: "?c=venta_tmp&a=eliminar",
            data: datos,
            success: function (data) { $("#tabla_items").html(data) } 
        });
    });
/* 
   Este es un comentario
   esto esta enviando los datos a la hora de 
   insertar los productos en la base de datos. 
*/
  
   $('#productoNuevo').submit(function (e) {
        e.preventDefault();

        // Asegúrate de que los campos ocultos tengan los valores correctos antes de enviar
        // Por ejemplo, si los valores de financiación se actualizan, puedes hacerlo aquí
        $('#entrega').val($('#producto').data('entrega_minima'));  // Asigna el valor del atributo data
        $('#cuota_vehiculo').val($('#producto').data('cuotas_minimas'));  // Asigna el valor de cuotas mínimas
        $('#monto_refuerzo').val($('#producto').data('monto_minimo_refuerzo'));  // Asigna el monto mínimo de refuerzo
        $('#cantidad_refuerzo').val($('#producto').data('cant_refuerzo'));  // Asigna la cantidad de refuerzos

        // Serializar el formulario y enviar los datos
        var datos = $(this).serialize();
        
        $.ajax({
            method: "POST",
            url: "?c=venta_tmp&a=guardar",
            data: datos,
            success: function (data) {
                // Actualiza la tabla o el contenido de la vista según la respuesta
                $("#tabla_items").html(data); 
                $("#productoNuevo")[0].reset();  // Resetea el formulario
                $('#producto').selectpicker('refresh');  // Refresca el selectpicker
                $("#precio_minorista").html("");  // Limpia el precio mostrado
                $("#producto").focus();  // Focaliza el campo de producto
                $('.selectpicker').selectpicker();  // Asegúrate de refrescar el selectpicker
            }
        });
    });

    
    $('#finalizarModal').on('show.bs.modal', function (event) {
		$("#finalizar_venta").focus();
	})
/* 
   Este es un comentario
   esto esta estirando los datos a la hora de 
   buscar los productos. 
*/
   // Cuando cambia el producto seleccionado
    $('#producto').on('change', function () {
        var id = $(this).val();
        var url = "?c=producto&a=buscar&id=" + id;

        $.ajax({
            url: url,
            method: "POST",
            data: id, // Enviamos el ID del producto
            cache: false,
            contentType: false,
            processData: false,
            success: function (respuesta) {
                var producto = JSON.parse(respuesta);

                // ✅ Actualizamos los precios en el select de precios
                $("#opcion_minorista").val(producto.precio_minorista);
                $("#opcion_minorista").text("Minorista - " + new Intl.NumberFormat().format(producto.precio_minorista) + " Gs");

                $("#opcion_financiado").val(producto.precio_financiado);
                $("#opcion_financiado").text("Financiado - " + new Intl.NumberFormat().format(producto.precio_financiado) + " Gs");

                // ✅ Establecemos el descuento máximo permitido
                $("#descuento").attr("max", producto.descuento_max);

                // ✅ Guardamos en memoria temporal (data attributes) los valores de financiación
                $('#producto').data('entrega_minima', producto.entrega_minima);
                $('#producto').data('cuotas_minimas', producto.cuotas_minimas);
                $('#producto').data('cant_refuerzo', producto.cant_refuerzo);
                $('#producto').data('monto_minimo_refuerzo', producto.monto_minimo_refuerzo);

                // ✅ Enfocamos el campo de cantidad para facilitar la carga rápida
                $("#cantidad").select();
            }
        });
    });

    
    $(document).ready(function () {
        var id = $("#clienteId").val();
        if(id>0){
            var url = "?c=cliente&a=buscar&id="+id;
            var categoria = "Plata";
            var descuento = 0;
            $.ajax({

                url: url,
                method : "POST",
                data: id,
                cache: false,
                contentType: false,
                processData: false,
                success:function(respuesta){
                    var cliente = JSON.parse(respuesta);
                    $("#puntos").val(cliente.puntos);
                    if(cliente.gastado < 3000000){
                        categoria = 'Plata';
                        descuento = 5;
                    }else if(cliente.gastado >= 3000000 && cliente.gastado < 10000000){
                        categoria = 'Oro';
                        descuento = 10;
                    }else{
                        categoria = 'Platino';
                        descuento = 15;
                    }
                    $("#categoria").val(categoria);
                    $("#cont_cli").html("<input type='text' value='"+cliente.nombre+"' class='form-control' readonly>");
                }

            })
        }
    });

    $('#cliente').on('change',function(){
        var id = $(this).val();
        var url = "?c=cliente&a=buscar&id="+id;
        var categoria = "Plata";
        var descuento = 0;
            $.ajax({

                url: url,
                method : "POST",
                data: id,
                cache: false,
                contentType: false,
                processData: false,
                success:function(respuesta){
                    var cliente = JSON.parse(respuesta);
                    $("#puntos").val(cliente.puntos);
                    if(cliente.gastado<3000000){
                        categoria = 'Plata';
                        descuento = 5;
                    }else if(cliente.gastado >= 3000000 && cliente.gastado<10000000){
                        categoria = 'Oro';
                        descuento = 10;
                    }else{
                        categoria = 'Platino';
                        descuento = 15;
                    }
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
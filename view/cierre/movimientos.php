<h1 class="page-header"> GeoCad movimientos de la caja en la sesión 
<a class="btn btn-lg btn-success " href="#cierreModal" class="btn btn-success" data-toggle="modal" data-target="#cierreModal" data-c="venta">Cierre de caja</a>
<a href="?c=venta&a=informediario&fecha=<?php echo date("Y-m-d"); ?>" class="btn btn-success" >Informe de ventas del día</a>
</h1>
<br><br><br>

<p> </p>
<table class="table table-striped display responsive nowrap datatable" width="100%">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th> <i class="fa-solid fa-language"></i>  N°</th>
        	<th> <i class="fa-regular fa-calendar-days"></i> Fecha</th>
            <th> <i class="fa-solid fa-sitemap"></i>  Categoría</th>
            <th> <i class="fa-regular fa-pen-to-square"></i> Concepto</th>
            <th> <i class="fa-solid fa-receipt"></i>  N° de comprobante</th>
            <th> <i class="fa-solid fa-money-check-dollar"></i>  Monto</th> 
            <th> <i class="fa-solid fa-cash-register"></i>  Caja</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $user_id = (isset($_GET['id']))? $_GET['id']:$_SESSION['user_id'];
    $cierre = $this->model->Consultar($user_id); ?>
    <tr class="click">
            <td> </td>
            <td><?php echo date("d/m/Y H:i", strtotime($cierre->fecha_apertura)); ?></td>
            <td><?php echo "Apertura"; ?></td>
            <td><?php echo "Apertura de caja del día"; ?></td>
            <td><?php echo ""; ?></td>
            <td class="monto"><?php echo number_format($cierre->monto_apertura,0,".",","); ?></td>
            <td><?php echo "Caja chica"; ?></td>
    </tr>
    <?php 
    $sumaEfectivo = $cierre->monto_apertura;
    $caja1 = $cierre->monto_apertura;
    $caja2 = 0;
    $sumaTarjeta = 0;
    $sumaTransferencia = 0;
    $sumaGiro = 0;
    $c=1;
    foreach($this->model->ListarMovimientosSesion($user_id) as $r):
    if($r->id_caja == 1){
        if(strlen($r->concepto)>=50){$concepto=substr($r->concepto, 0, 50)."...";}else{$concepto=$r->concepto;}?>
        <tr class="click" <?php if($r->anulado){echo "style='color:red'";}elseif($r->descuento>0){echo "style='color:#7dcd5d '";} ?>>
            <td><?php echo $c++; ?></td>
        	<td><?php echo date("d/m/Y H:i", strtotime($r->fecha)); ?></td>
            <td><?php echo $r->categoria; ?></td>
            <td title="<?php echo $r->concepto; ?>"><?php echo $concepto; ?></td>
            <td><?php echo $r->comprobante; ?></td>
            <td><?php echo number_format($r->monto,0,".",","); ?></td>
            <td><?php echo $r->caja; ?></td>
        </tr>
    <?php 
    $pos = strpos($r->forma_pago, "Efectivo");
    if(!$r->anulado && $pos !== false) {
    $sumaEfectivo +=  $r->monto;
    }
    
    $pos = strpos($r->forma_pago, "Giro");
    if(!$r->anulado && $pos !== false) {
    $sumaGiro +=  $r->monto;
    }
    if(!$r->anulado && $r->forma_pago == "Tarjeta") {
        $sumaTarjeta +=  $r->monto;
    }
    
    $pos = strpos($r->forma_pago, "Transferencia");
    if(!$r->anulado && $pos !== false) {
        $sumaTransferencia +=  $r->monto;
    }
    
    if(!$r->anulado && $r->id_caja == 1) {
        $caja1 +=  $r->monto;
    }
    
    if(!$r->anulado && $r->id_caja == 2) {
        $caja2 +=  $r->monto;
    }
    
    if(!$r->anulado && $r->id_caja == 3) {
        $caja3 +=  $r->monto;
    }
    }
    endforeach; ?>
    </tbody>
    <tfoot>
        <tr style="background-color: black; color:#fff">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total caja chica: </th>
            <th class="monto" id="monto_total"><?php echo number_format($caja1,0,".",","); ?></th> 
            <th></th>
        </tr>
    </tfoot>
</table>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>


</dir>
</div>
</div>
</div>
<?php include("view/crud-modal.php"); ?>
<?php include("view/venta/detalles-modal.php"); ?>
<?php include("view/deuda/cobros-modal.php"); ?>
<?php include("view/venta/cierre-modal.php"); ?>
<?php include("view/caja/transferencias.php"); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $( ":input" ).attr("hola","hola");
    });
</script>

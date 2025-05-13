<h1 class="page-header">Ventas del <?php echo date("d/m/Y"); ?> &nbsp;
<?php if($_SESSION['nivel']<=1){ ?>
<a class="btn btn-success" href="index.php?c=venta&a=cierre&fecha=<?php echo date("Y-m-d"); ?>">Genere su informe del día</a></h1>
<!--<a class="btn btn-success" href="index.php?c=venta&a=cierremes&desde=<?php //echo date("Y-m-d"); ?>&hasta=<?php //echo date("Y-m-d"); ?>">Informe del Mes</a>-->
<?php } ?>
</h1>
<a class="btn btn-success pull-right" href="?c=venta_tmp" class="btn btn-success"> <i class="fa-solid fa-hand-holding-dollar"></i> Nueva venta</a>
<br><br><br>
<table class="table table-striped table-bordered display responsive nowrap datatable">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th>Vendedor</th>
            <?php if (isset($_REQUEST['id_venta'])): ?>
            <th> <i class="fa-brands fa-buromobelexperte"></i> Producto</th>    
            <?php endif ?>
            <th> <i class="fa-solid fa-hand-holding-dollar"></i> Cliente</th>
            <th>Sub tot. (Gs.)</th>
            <th>Desc. (%)</th>
            <th> <i class="fa-solid fa-calculator"></i>  Total (Gs.)</th>
            <th>Ganan. (%)</th>
            <th> <i class="fa-regular fa-calendar-days"></i> Hora</th>
            <?php if (!isset($_GET['id_venta'])): ?>        
            <th></th>
            <?php endif ?>
    </thead>
    <tbody>
    <?php 
    $suma = 0; $count = 0;  
    foreach($this->model->ListarDia(date("Y-m-d")) as $r): ?>
        <tr class="click" <?php if($r->anulado){echo "style='color:gray'";} ?>>
            <td><?php echo $r->vendedor; ?></td>
            <?php if (isset($_REQUEST['id_venta'])): ?>
            <td><?php echo $r->producto; ?></td>    
            <?php endif ?>
            <td><?php echo $r->nombre_cli; ?></td>
            <td><?php echo number_format($r->subtotal,0,".",","); ?></td>
            <td><?php echo $r->descuento; ?></td>
            <td><?php echo number_format($r->total,0,".",","); ?></td>
            <td><?php echo round($r->margen_ganancia,2); ?></td>
            <td><?php echo date("H:i", strtotime($r->fecha_venta)); ?></td>
            <?php if (!isset($_GET['id_venta'])): ?>
            <td>
                <a href="#detallesModal" class="btn btn-success" data-toggle="modal" data-target="#detallesModal" data-id="<?php echo $r->id_venta;?>"> <i class="fa-solid fa-binoculars"></i> Ver</a>
                <?php echo ($r->anulado)? " ANULADO":"";?>
            </td>
            <?php endif ?>
        </tr>
    <?php 
        $count++;
    endforeach; ?>
    </tbody>
    
</table>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina-P & Q AUTOMOTORES SA. <?php echo date("Y") ?></a></li>
    </ul>

</dir>
</div>
</div>
<?php include("view/crud-modal.php"); ?>
<?php include("view/venta/detalles-modal.php"); ?>


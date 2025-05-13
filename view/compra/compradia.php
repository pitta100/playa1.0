<h1 class="page-header">compras del <?php echo date("d/m/Y"); ?> &nbsp;
<a class="btn btn-success" href="index.php?c=compra&a=cierre&fecha=<?php echo date("Y-m-d"); ?>"> <i class="fa-regular fa-calendar-days"></i> Informe del día</a></h1>
<a class="btn btn-success pull-right" href="?c=compra_tmp" class="btn btn-success"> <i class="fa-solid fa-chart-line"></i> Nueva compra</a>
<br><br><br>
<table class="table table-striped table-bordered display responsive nowrap datatable">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th>ID</th>
            <th>Comprador</th>
            <?php if (isset($_REQUEST['id_compra'])): ?>
            <th>Producto</th>    
            <?php endif ?>
            <th>Proveedor</th>
            <th>Total (Gs.)</th>
            <th>Hora</th>
            <?php if (!isset($_GET['id_compra'])): ?>        
            <th></th>
            <?php endif ?>
    </thead>
    <tbody>
    <?php 
    $suma = 0; $count = 0;  
    foreach($this->model->ListarDia(date("Y-m-d")) as $r): ?>
        <tr class="click" <?php if($r->anulado){echo "style='color:gray'";} ?>>
            <td><?php echo $r->id_compra; ?></td>
            <td><?php echo $r->vendedor; ?></td>
            <?php if (isset($_REQUEST['id_compra'])): ?>
            <td><?php echo $r->producto; ?></td>    
            <?php endif ?>
            <td><?php echo $r->nombre_cli; ?></td>
            <td><?php echo number_format($r->total,0,".",","); ?></td>
            <td><?php echo date("H:i", strtotime($r->fecha_compra)); ?></td>
            <?php if (!isset($_GET['id_compra'])): ?>
            <td>
                <a href="#detallesCompraModal" class="btn btn-success" data-toggle="modal" data-target="#detallesCompraModal" data-id="<?php echo $r->id_compra;?>"> <i class="fa-solid fa-binoculars"></i> Ver</a>
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
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina -P & Q AUTOMOTORES SA.<?php echo date("Y") ?></a></li>
    </ul>

</dir>
</div>
</div>
<?php include("view/crud-modal.php"); ?>
<?php include("view/compra/detalles-modal.php"); ?>


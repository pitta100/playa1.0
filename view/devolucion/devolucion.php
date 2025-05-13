<h1 class="page-header">Lista de ajustes &nbsp; <a href="?c=devolucion_tmp" class="btn btn-success"> <i class="fa-solid fa-gears"></i>  Nuevo ajuste </a></h1>
<!--<a class="btn btn-primary" href="#diaModal" class="btn btn-primary" data-toggle="modal" data-target="#diaModal">Informe diario</a>
<a class="btn btn-primary" href="#mesModal" class="btn btn-primary" data-toggle="modal" data-target="#mesModal">Informe Mensual</a>
<a class="btn btn-primary pull-right" href="?c=devolucion_tmp" class="btn btn-success">Nueva devolución</a>-->
<br><br><br>
<table class="table table-striped table-bordered display responsive nowrap datatable" width="100%">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th> <i class="fa-solid fa-language"></i> ID</th>
            <th> <i class="fa-solid fa-user"></i> Usuario</th>
            <th> <i class="fa-solid fa-calculator"></i> Total</th>
            <th> <i class="fa-regular fa-calendar-days"></i> Fecha y Hora</th>
            <?php if (!isset($_GET['id_venta'])): ?>        
            <th></th>
            <?php endif ?>
        </tr>
    </thead>
    <tbody>
    <?php 
    $suma = 0; $count = 0;  
    $id_venta = (isset($_REQUEST['id_venta']))? $_REQUEST['id_venta']:0;
    $suma = 0; $count = 0;  
    foreach($this->model->Listar($id_venta) as $r): ?>
        <tr class="click" <?php if($r->anulado){echo "style='color:gray'";} ?>>
            <td><?php echo $r->id_venta; ?></td>
            <td><?php echo $r->vendedor; ?></td>
            <td><?php echo number_format($r->total,0,".",","); ?></td>
            <td><?php echo date("d/m/Y H:i", strtotime($r->fecha_venta)); ?></td>
            <?php if (!isset($_GET['id_venta'])): ?>
            <td>
                <a href="#detallesModal" class="btn btn-success" data-toggle="modal" data-target="#devolucionModal" data-id="<?php echo $r->id_venta;?>"> <i class="fa-solid fa-binoculars"></i>  Ver</a>
                <!--<a  class="btn btn-primary edit" href="?c=venta_tmp&a=editar&id=<?php //echo $r->id_venta ?>" class="btn btn-success" >Editar</a>-->
                <?php if ($r->anulado): ?>
                ANULADO    
                <?php else: ?>
                <a  class="btn btn-danger delete" href="?c=devolucion&a=anular&id=<?php echo $r->id_venta ?>" class="btn btn-success">ANULAR</a>
                <?php endif ?>
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
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>

</dir>

</div>
</div>

<?php include("view/crud-modal.php"); ?>
<?php include("view/venta/mes-modal.php"); ?>
<?php include("view/venta/dia-modal.php"); ?>
<?php include("view/devolucion/detalles-modal.php"); ?>


<h1 class="page-header"> Informe del Inventario <a class="btn btn-success" href="?c=inventario&a=guardar" class="btn btn-success">Nuevo Inventario</a> </h1>
<?php if($_SESSION['nivel']<=1) ?>

<p> </p>
<table class="table table-striped table-bordered display responsive datatable" width="100%">

    <thead>
        <tr style="background-color: #000; color:#fff">
            <th> <i class="fa-solid fa-user"></i> Usuario</th>
            <th> <i class="fa-regular fa-calendar-days"></i> Fecha de Apertura</th> 
            <th> <i class="fa-regular fa-calendar-days"></i> Fecha de Cierre</th>    
            <th> <i class="fa-solid fa-magnifying-glass-chart"></i> Motivo</th>    
            <th></th> 
        </tr>
    </thead>
    <tbody>
    <?php 
$lista = $this->model->Listar();
    foreach($lista as $c):  ?>
        <tr >
            <td><?php echo $c->usuario; ?></td>
            <td><?php echo date("d/m/Y H:i", strtotime($c->fecha_apertura)); ?></td>
            <td><?php echo date("d/m/Y H:i", strtotime($c->fecha_cierre)); ?></td>
            <td><?php echo $c->motivo; ?></td>
            <td align="center">
                <a class="btn btn-success" href="?c=inventario&a=FechaApertura&fecha=<?php echo date("Y-m-d",strtotime($c->fecha_apertura))?>">Detalles</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
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




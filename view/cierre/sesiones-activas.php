<h1 class="page-header">Lista de  sesiones activas</h1>

<br><br><br>

<table class="table table-striped table-bordered display responsive nowrap datatable" width="100%">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th> <i class="fa-solid fa-user"></i> Usuario</th>
        	<th> <i class="fa-solid fa-door-open"></i> Apertura</th>
            <th> <i class="fa-solid fa-money-check-dollar"></i> Monto de apertura</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $lista = $this->model->ListarActivas();
    
    foreach($lista as $r): ?>
        <tr class="click">
            <td><?php echo $r->user; ?></td>
        	<td><?php echo date("d/m/Y H:i", strtotime($r->fecha_apertura)); ?></td>
            <td><?php echo number_format($r->monto_apertura,0,".",","); ?></td>
            <td>
                <a href="?c=cierre&a=movimientos&id=<?php echo $r->id_usuario; ?>" class="btn btn-success"><i class="fa-solid fa-binoculars"></i>   Ver detalles</a>
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

<script type="text/javascript">
    $( "#filtrar" ).click(function() {
      $("#filtro").toggle("slow");
      $("i").toggle();
    });
</script>
<h1 class="page-header">Cierres de caja  </h1>
<h3 id="filtrar" align="center">Desea buscar  por fecha <i class="fas fa-angle-down"></i><i class="fas fa-angle-up" style="display: none"></i></h3>
<div class="container">
  <div class="row">
    <div class="col">
        <div align="center" id="filtro">
            <form method="get" class="form-inline">
                <input type="hidden" name="c" value="cierre">
                <div class="form-group">
                    <label>Desde</label>
                    <input type="datetime-local" name="desde" value="<?php echo (isset($_GET['desde']))? $_GET['desde']:''; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Hasta</label>
                    <input type="datetime-local" name="hasta" value="<?php echo (isset($_GET['hasta']))? $_GET['hasta']:''; ?>" class="form-control" required>
                </div>
                <input type="submit" name="filtro" value="Filtrar" class="btn btn-success"> 
            </form>
        </div>
    </div>
  </div>
</div>
<p> </p>
<table class="table table-striped table-bordered display responsive nowrap datatable" width="100%">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th> <i class="fa-solid fa-user"></i> Usuario</th>
        	<th> Apertura</th>
            <th> <i class="fa-solid fa-shop-lock"></i> Cierre</th>
            <th> <i class="fa-solid fa-money-check-dollar"></i> Monto sistema</th>
            <th><i class="fa-solid fa-money-check-dollar"></i> Monto de cierre</th>
            <th> <i class="fa-solid fa-divide"></i> Diferencia</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $sumaSistema = 0;
    $sumaCierre = 0;
    $desde = (isset($_GET["desde"]))? $_GET["desde"]:0;
    $hasta = (isset($_GET["hasta"]))? $_GET["hasta"]:0;
    foreach($this->model->Listar($desde, $hasta) as $r): ?>
        <tr class="click">
            <td><?php echo $r->user; ?></td>
        	<td><?php echo date("d/m/Y H:i", strtotime($r->fecha_apertura)); ?></td>
            <td><?php echo date("d/m/Y H:i", strtotime($r->fecha_cierre)); ?></td>
            <td><?php echo number_format($r->monto_sistema,0,".",","); ?></td>
            <td><?php echo number_format($r->monto_cierre,0,".",","); ?></td>
            <td><?php echo number_format(($r->monto_cierre-$r->monto_sistema),0,".",","); ?></td>
            <td>
                <a href="?c=cierre&a=detalles&id=<?php echo $r->id; ?>" class="btn btn-success"> <i class="fa-solid fa-pencil"></i> Ver detalles</a>
            </td>    
        </tr>
    <?php 
    $sumaSistema += $r->monto_sistema;
    $sumaCierre += $r->monto_cierre;
    endforeach; ?>
    </tbody>
    <tfoot>
        <tr style="background-color: black; color:#fff">
            <th></th>
        	<th></th>
            <th>Total:</th>
            <th><?php echo number_format($sumaSistema,0,".",","); ?></th>
            <th><?php echo number_format($sumaCierre,0,".",","); ?></th>
            <th><?php echo number_format(($sumaCierre-$sumaSistema),0,".",","); ?></th>
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

<script type="text/javascript">
    $( "#filtrar" ).click(function() {
      $("#filtro").toggle("slow");
      $("i").toggle();
    });
</script>
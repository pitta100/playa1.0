<h1 class="page-header">Lista de egresos <a class="btn btn-success" href="#mesModal" data-toggle="modal" data-target="#mesModal" data-c="egreso">Informe</a></h1>
<a class="btn btn-success pull-right" href="#egresoModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="egreso"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Agregar</a>

<br><br><br>

<h3 id="filtrar" align="center"> <i class="fa-regular fa-calendar-days"></i> Filtrar por fecha <i class="fas fa-angle-down"></i><i class="fas fa-angle-up" style="display: none"></i></h3>
<div class="container">
  <div class="row">
    <div class="col-sm-4">
    </div>
    <div class="col-sm-4">
        <div align="center" id="filtro" style="display: none;">
            <form method="post">
                <div class="form-group">
                    <label> <i class="fa-solid fa-forward-step"></i> Desde</label>
                    <input type="date" name="desde" value="<?php echo (isset($_GET['desde']))? $_GET['desde']:''; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-backward-step"></i>  Hasta</label>
                    <input type="date" name="hasta" value="<?php echo (isset($_GET['hasta']))? $_GET['hasta']:''; ?>" class="form-control" required>
                </div>
                <input type="submit" name="filtro" value="Filtrar" class="btn btn-success"> 
            </form>
        </div>
    </div>
    <div class="col-sm-4">
    </div>
  </div>
</div>
<p> </p>
<table class="table table-striped table-bordered display responsive nowrap datatable" width="100%">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th> <i class="fa-solid fa-receipt"></i>  N° de comprobante</th>
            <th> <i class="fa-regular fa-calendar-days"></i> Fecha</th>
            <th> <i class="fa-brands fa-cc-paypal"></i> Forma de pago</th>
            <th> <i class="fa-solid fa-truck-fast"></i> Ruc</th>
            <th> <i class="fa-solid fa-truck-fast"></i> Proveedor</th>
            <th> <i class="fa-solid fa-sitemap"></i> Código</th>
            <th> <i class="fa-solid fa-money-check-dollar"></i> Monto</th>
            <th> <i class="fa-regular fa-pen-to-square"></i> Técnico</th> 
            <th> <i class="fa-solid fa-list-ol"></i> N° de cheque</th>
            <th> <i class="fa-solid fa-hourglass-end"></i> Plazo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $lista = (isset($_POST['desde']))? $this->model->Listar_rango($_POST['desde'],$_POST['hasta']):$this->model->Listar();
    
    foreach($lista as $r): 
    if(strlen($r->concepto)>=50){$concepto=substr($r->concepto, 0, 50)."...";}else{$concepto=$r->concepto;}?>
        <tr class="click" <?php if($r->anulado){echo "style='color:gray'";} ?>>

            <td><?php echo $r->comprobante; ?></td>
            <td><?php echo date("d/m/Y H:i", strtotime($r->fecha)); ?></td>
            <td><?php echo $r->forma_pago; ?></td>
             <td><?php if($r->id_cliente==1) {?>
            Proveedor ocasional
            <?php }else{?>
            <?php echo $r->ruc ; ?>
            <?php }?></td>
            <td><?php if($r->id_cliente==1) {?>
            Proveedor ocasional
            <?php }else{?>
            <?php echo $r->nombre ." ( ".$r->ruc." )"; ?>
            <?php }?></td>
            <td><?php echo $r->categoria; ?></td>
            <td><?php echo number_format($r->monto,0,".",","); ?></td>
            <td title="<?php echo $r->concepto; ?>"><?php echo $concepto; ?></td>
            <td><?php echo $r->nro_cheque; ?></td>
            <td><?php echo date("d/m/Y", strtotime($r->plazo)); ?></td>
            <td>
                <?php if (!$r->anulado): ?>
                    <?php if ($r->id_compra): ?>
                    <a href="#detallesCompraModal" class="btn btn-warning" data-toggle="modal" data-target="#detallesCompraModal" data-id="<?php echo $r->id_compra;?>"> <i class="fa-solid fa-chart-line"></i> Compra</a>
                        <?php if ($r->id_acreedor): ?>
                        <a href="#pagosModal" class="btn btn-success" data-toggle="modal" data-target="#pagosModal" data-id="<?php echo $r->id_acreedor; ?>">Pagos</a>
                        <a  class="btn btn-danger delete" href="?c=egreso&a=EliminarPago&id=<?php echo $r->id; ?>&id_compra=<?php echo $r->id_compra; ?>"><i class="fas fa-trash-alt"></i></a>
                        <?php endif ?>
                    <?php elseif($r->id_acreedor): ?>
                    <a href="#pagosModal" class="btn btn-success" data-toggle="modal" data-target="#pagosModal" data-id="<?php echo $r->id_acreedor; ?>">Pagos</a>
                    <a  class="btn btn-danger delete" href="?c=egreso&a=EliminarPago&id=<?php echo $r->id; ?>&id_compra=<?php echo $r->id_compra; ?>"><i class="fas fa-trash-alt"></i></a>
                    <?php else: ?>
                    <a  class="btn btn-success edit" href="#crudModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-id="<?php echo $r->id;?>" data-c="egreso"> <i class="fa-solid fa-pen-to-square"></i>  Editar</a>
                    <a  class="btn btn-danger delete" href="?c=egreso&a=Eliminar&id=<?php echo $r->id; ?>&id_compra=<?php echo $r->id_compra; ?>">Eliminar</a>
                    <?php endif ?>
                <?php else: ?>
                    ANULADO
                <?php endif ?>
            </td>    
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina-P & Q AUTOMOTORES SA. <?php echo date("Y") ?></a></li>
    </ul>

</dir>
</div>
</div>
</div>
<?php include("view/crud-modal.php"); ?>
<?php include("view/compra/detalles-modal.php"); ?>
<?php include("view/egreso/mes-modal.php"); ?>
<?php include("view/acreedor/pagos-modal.php"); ?>

<script type="text/javascript">
    $( "#filtrar" ).click(function() {
      $("#filtro").toggle("slow");
      $("i").toggle();
    });
</script>
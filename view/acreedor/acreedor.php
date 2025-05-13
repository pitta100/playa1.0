<h1 class="page-header">Lista de acreedores </h1>
<a class="btn btn-success pull-right" href="#acreedorModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="acreedor"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Agregar</a>
<br><br><br>
<div class="table-responsive">
<table class="table table-striped table-bordered display responsive nowrap datatable">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th></th>
        	<th> <i class="fa-solid fa-hands-holding-circle"></i> Cliente</th>
            <th> <i class="fa-regular fa-pen-to-square"></i>  Concepto</th>
            <th> <i class="fa-solid fa-money-check-dollar"></i> Monto</th>
            <th>Saldo</th>
            <th> <i class="fa-regular fa-calendar-days"></i> Fecha</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($this->model->Listar() as $r): ?>
        <tr class="click">
            <td>
                <div align="center"><a class="btn btn-primary " href="#pagarModal" class="btn btn-success" data-toggle="modal" data-target="#pagarModal" data-id="<?php echo $r->id;?>"> <i class="fa-solid fa-handshake"></i> Pagar</a></div>
                            </td>
        	<td><a href="?c=acreedor&a=clientepdf&id=<?php echo $r->id_cliente; ?>&cli=<?php echo $r->nombre; ?>"><?php echo $r->nombre; ?></a></td>
            <td><?php echo $r->concepto; ?></td>
            <td><?php echo number_format($r->monto,0,",","."); ?></td>
            <td><?php echo number_format($r->saldo,0,",","."); ?></td>
            <td><?php echo date("d/m/Y H:i", strtotime($r->fecha)); ?></td>
            <?php if ($r->id_compra): ?>
            <td>
                <a href="#pagosModal" class="btn btn-success" data-toggle="modal" data-target="#pagosModal" data-id="<?php echo $r->id; ?>">Pagos</a>
                <a href="#detallesCompraModal" class="btn btn-warning" data-toggle="modal" data-target="#detallesCompraModal" data-id="<?php echo $r->id_compra;?>">Compra</a>
            </td>
            <?php else: ?>
            <td>
                <a href="#pagosModal" class="btn btn-success" data-toggle="modal" data-target="#pagosModal" data-id="<?php echo $r->id; ?>">Pagos</a>
                <a  class="btn btn-warning edit" href="#crudModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-id="<?php echo $r->id;?>" data-c="acreedor"> <i class="fa-solid fa-pen-to-square"></i> Editar</a>
                <a  class="btn btn-danger" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=acreedor&a=Eliminar&id=<?php echo $r->id; ?>"> <i class="fa-solid fa-trash-can"></i> Eliminar</a>
            </td>
            <?php endif ?>
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
</div>
<?php include("view/crud-modal.php"); ?>
<?php include("view/acreedor/pagar-modal.php"); ?>
<?php include("view/compra/detalles-modal.php"); ?>
<?php include("view/acreedor/pagos-modal.php"); ?>

<script type="text/javascript">
    $('#pagarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        if(id>0){
            var url = "?c=acreedor&a=pagarModal&id="+id;
        }else{
            var url = "?c=acreedor&a=pagar";
        }
        $.ajax({

            url: url,
            method : "POST",
            data: id,
            cache: false,
            contentType: false,
            processData: false,
            success:function(respuesta){
                $("#modal-body").html(respuesta);
            }

        })
    })
</script>


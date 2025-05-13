<h1 class="page-header">Lista de deudas </h1>
<a class="btn btn-success pull-right" href="#deudaModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="deuda"> <i class="fa-solid fa-cloud-arrow-up"></i> AGREGAR </a>
<br><br><br>
<div class="table-responsive">
<table class="table table-striped table-bordered display responsive nowrap datatable">
    <thead>
        <tr style="background-color: black; color:#fff">
            <th></th>
            <th><i class="fa-solid fa-arrow-right-from-bracket"></i> Cliente</th>
            <th><i class="fa-regular fa-pen-to-square"></i> Concepto</th>
            <th><i class="fa-solid fa-money-check-dollar"></i> Monto</th>
            <th>Saldo</th>
            <th><i class="fa-regular fa-calendar-days"></i> Fecha</th>
            <th><i class="fa-regular fa-calendar-days"></i> Fecha de pago</th>
            <th><i class="fa-regular fa-calendar-days"></i> Cantidad de Cuotas</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($this->model->Listar() as $r): ?>
        <tr class="click">
            <td>
                <div align="center">
                    <a class="btn btn-success" href="#cobrarModal" data-toggle="modal" data-target="#cobrarModal" data-id="<?php echo $r->id; ?>" aria-label="Cobrar deuda">Cobrar</a>
                </div>
            </td>
            <td><a href="?c=deuda&a=clientepdf&id=<?php echo $r->id_cliente; ?>&cli=<?php echo $r->nombre; ?>" aria-label="Ver PDF de <?php echo $r->nombre; ?>"><?php echo $r->nombre; ?></a></td>
            <td><?php echo $r->concepto; ?></td>
            <td><?php echo number_format($r->monto, 0, ",", "."); ?></td>
            <td><?php echo number_format($r->saldo, 0, ",", "."); ?></td>
            <td><?php echo date("d/m/Y H:i", strtotime($r->fecha)); ?></td>
            <td><?php echo (date("Y", strtotime($r->vencimiento)) > 2000) ? date("d/m/Y", strtotime($r->vencimiento)) : ""; ?></td>
            <td><?php echo $r->cuotas; ?></td>
            <?php if ($r->id_venta): ?>
            <td>
                <a href="#cobrosModal" class="btn btn-success" data-toggle="modal" data-target="#cobrosModal" data-id="<?php echo $r->id; ?>" aria-label="Ver cobros de deuda <?php echo $r->id; ?>">
                    <i class="fa-solid fa-person-walking-arrow-loop-left"></i> Cobros
                </a>
                <a href="#detallesModal" class="btn btn-default" data-toggle="modal" data-target="#detallesModal" data-id="<?php echo $r->id_venta; ?>" aria-label="Ver detalles de venta <?php echo $r->id_venta; ?>">
                    <i class="fa-solid fa-hand-holding-dollar"></i> Venta
                </a>
                <a href="#listadoModal" class="btn btn-success" data-toggle="modal" data-target="#listadoModal" data-id="<?php echo $r->id; ?>" aria-label="Listar detalles de deuda <?php echo $r->id; ?>">
                    <i class="fa-solid fa-person-walking-arrow-loop-left"></i> Listar Detalles
                </a>
                <a class="btn btn-warning edit" href="#crudModal" data-toggle="modal" data-target="#crudModal" data-id="<?php echo $r->id; ?>" data-c="deuda" aria-label="Editar deuda <?php echo $r->id; ?>">
                    <i class="fa-solid fa-pen-to-square"></i> Editar
                </a>
            </td>
            <?php else: ?>
            <td>
                <a href="#cobrosModal" class="btn btn-success" data-toggle="modal" data-target="#cobrosModal" data-id="<?php echo $r->id; ?>" aria-label="Cobrar deuda <?php echo $r->id; ?>">Cobrar</a>
                <a class="btn btn-warning edit" href="#crudModal" data-toggle="modal" data-target="#crudModal" data-id="<?php echo $r->id; ?>" data-c="deuda" aria-label="Editar deuda <?php echo $r->id; ?>">
                    <i class="fa-solid fa-pen-to-square"></i> Editar
                </a>
                <a class="btn btn-danger" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=deuda&a=Eliminar&id=<?php echo $r->id; ?>" aria-label="Eliminar deuda <?php echo $r->id; ?>">
                    <i class="fa-solid fa-trash-can"></i> Eliminar
                </a>
            </td>
            <td>
                <a href="#listadoModal" class="btn btn-success" data-toggle="modal" data-target="#listadoModal" data-id="<?php echo $r->id; ?>" aria-label="Listar detalles de deuda <?php echo $r->id; ?>">
                    <i class="fa-solid fa-person-walking-arrow-loop-left"></i> Listar Detalles
                </a>
            </td>
            <?php endif ?>
            <td>
                <a href="#refuerzosModal" class="btn btn-success" data-toggle="modal" data-target="#refuerzosModal" data-id="<?php echo $r->id; ?>" aria-label="Agregar refuerzo">
                    <i class="fa-solid fa-plus"></i> Cobrar Refuerzo
                </a>
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
</div>
<?php include("view/crud-modal.php"); ?>
<?php include("view/deuda/cobrar-modal.php"); ?>
<?php include("view/venta/detalles-modal.php"); ?>
<?php include("view/deuda/cobros-modal.php"); ?>
<?php include("view/deuda/listado-modal.php"); ?>
<?php include("view/deuda/refuerzo-modal.php"); ?>
<script type="text/javascript">
    $('#cobrarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        if(id>0){
            var url = "?c=deuda&a=cobrarModal&id="+id;
        }else{
            var url = "?c=deuda&a=cobrar";
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
    });
   // Listado de detalles en el modal
    $('#listadoModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // El botón que activó el modal
        var id = button.data('id'); // Extrae el ID de la deuda desde el atributo "data-id"
        var url = "?c=deuda&a=listarDetalles&id=" + id; // URL para obtener los detalles de la deuda

        // Realizamos la llamada AJAX para cargar los detalles de la deuda
        $.ajax({
            url: url,            // La URL a la que se hace la solicitud
            method: "POST",      // Usamos el método POST para enviar el ID de la deuda
            data: { id: id },    // Enviamos el ID de la deuda como dato
            cache: false,        // Desactiva la caché
            contentType: false,  // No establecemos el tipo de contenido
            processData: false,  // No procesamos los datos
            success: function(respuesta) {
                $("#listado-detalles").html(respuesta); // Coloca la respuesta dentro del modal
            },
            error: function() {
                alert('Hubo un error al cargar los detalles.');
            }
        });
    });

    $('#refuerzosModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // El botón que activó el modal
        var id = button.data('id'); // Extrae el ID de la deuda desde el atributo "data-id"
        var url = "?c=deuda&a=refuerzosModal&id=" + id; // URL para obtener los detalles del cobro de refuerzos

        // Realizamos la llamada AJAX para cargar el formulario de refuerzos
        $.ajax({
            url: url,
            method: "POST",
            data: { id: id },
            cache: false,
            contentType: false,
            processData: false,
            success: function(respuesta) {
                $("#modal-body-refuerzos").html(respuesta); // Coloca la respuesta dentro del modal
            },
            error: function() {
                alert('Hubo un error al cargar el formulario de refuerzos.');
            }
        });
    });




</script>


<h1 class="page-header">Lista de Productos</h1>
<a class="btn btn-success pull-right" href="#productoModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="producto"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Agregar</a>
<br><br><br>
<table class="table table-striped table-bordered display responsive nowrap datatable">

    <thead>
        <tr style="background-color: #000; color:#fff">
            <th> <i class="fa-solid fa-code"></i>  Código</th>
            <th> <i class="fa-solid fa-sitemap"></i>  Categoría</th>
            <th> <i class="fa-brands fa-buromobelexperte"></i> Producto</th>
            <th> <i class="fa-solid fa-arrows-up-down"></i>  Precio costo</th>
            <th>Precio min</th>
            <th>Precio may</th>
            <th> <i class="fa-solid fa-basket-shopping"></i> Stock</th>
            <th>Stock mín</th>
            <th>Desc máx</th>
            <th>Import.</th>
            <th>Suc.</th>
            <th>Img</th>
            <th></th>
            <?php if($_SESSION['nivel']<=1){ ?>
            <th></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach($this->model->Listar() as $r): ?>
        <tr class="click">
            <td><?php echo $r->codigo; ?></td>
            <td><?php echo substr($r->categoria,0,15); ?></td>
            <td><?php echo substr($r->producto,0,10); ?></td>
            <td><?php echo number_format($r->precio_costo,0,".",","); ?></td>
            <td><?php echo number_format($r->precio_minorista,0,".",","); ?></td>
            <td><?php echo number_format($r->precio_mayorista,0,".",","); ?></td>
            <td><?php echo $r->stock; ?></td>
            <td><?php echo $r->stock_minimo; ?></td>
            <td><?php echo $r->descuento_max; ?></td>
            <td><?php echo $r->importado; ?></td>
            <td><?php echo $r->sucursal; ?></td>
            <td><a href="?c=imagen&id_prod=<?php echo $r->id; ?>&prod=<?php echo $r->producto; ?>" class="btn btn-success">Fotos</a></td>
            <td>
                <a  class="btn btn-success edit" href="#crudModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-id="<?php echo $r->id;?>" data-c="producto"> <i class="fa-solid fa-pen-to-square"></i> Edit</a>
            </td>
            
            <?php if($_SESSION['nivel']<=1){ ?>
            <td>
                <a  class="btn btn-danger delete" href="?c=producto&a=Eliminar&id=<?php echo $r->id; ?>"><i class="fas fa-trash-alt"></i></a>
            </td>
            <?php } ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> 
</div>
</div>
</div>
<?php include("view/crud-modal.php"); ?>

<script type="text/javascript">
    $(document).ready(function() {
    $('.tabla').DataTable({
        "dom": 'Bfrtip',
        "buttons": [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        responsive: {
            details: true
        },
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página.",
            "search": "Buscar",
            "zeroRecords": "Lo sentimos. No se encontraron registros.",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros aún.",
            "infoFiltered": "(filtrados de un total de _MAX_ registros)",
            "LoadingRecords": "Cargando ...",
            "Processing": "Procesando...",
            "SearchPlaceholder": "Comience a teclear...",
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente",
            }
        },
        stateSave: true
    });
});
</script>


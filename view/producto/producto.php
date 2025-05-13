<h1 class="page-header">Lista de servicios que ofrecemos.</h1>
<a class="btn btn-success pull-right" href="#productoModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="producto"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Agregar Vehiculos </a>
<br><br><br>
<?php if($_SESSION['nivel']==1){ ?>
<div class="container" style="display:none;">
  <div class="row">
    <div class="col-sm-4">
    </div>
    <div class="col-sm-4">
        <div align="center" id="filtro">
            <form method="get">
                <input type="hidden" name="c" value="producto">
                <div class="form-group">
                    <div class="form-group">
                        <label>Sucursal</label>
                        <select name="sucursal" class="form-control">
                            <?php foreach($this->sucursal->Listar() as $r): ?>
                                <option value="<?php echo $r->id; ?>" <?php if(isset($_GET['sucursal']) && $_GET['sucursal']==$r->id){ echo "selected"; }?>><?php echo $r->sucursal; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <input type="submit" name="filtro" value="Filtrar" class="btn btn-success"> 
            </form>
        </div>
    </div>
    <div class="col-sm-4">
    </div>
  </div>
</div>
<?php } ?>
<table id="tabla" class="table responsive display" style="width:100%">
        <thead>
            <tr style="background-color: #000; color:#fff">
                <th> <i class="fa-solid fa-code"></i> Código</th>
                <th> <i class="fa-solid fa-sitemap"></i> Sub-Categoría</th>
                <th> <i class="fa-solid fa-code-fork"></i> Producto </th>
                <th> <i class="fa-brands fa-buromobelexperte"></i>Marca</th>
                <th> <i class="fa-brands fa-buromobelexperte"></i>Descripcion</th>
                <th> <i class="fa-brands fa-buromobelexperte"></i>Chasis</th>
                <th> <i class="fa-brands fa-buromobelexperte"></i>Motor</th>
                <th> <i class="fa-brands fa-buromobelexperte"></i>Kilometraje</th>
                <th> <i class="fa-brands fa-buromobelexperte"></i>Pais de Origen</th>
                <th> <i class="fa-brands fa-buromobelexperte"></i>Chapa</th>
                  <th> <i class="fa-brands fa-buromobelexperte"></i>Fecha de Importacion</th>
                <th> <i class="fa-solid fa-arrows-up-down"></i> Financiado</th>
                <th> <i class="fa-solid fa-comment-dollar"></i> Contado</th>
                <th> <i class="fa-solid fa-basket-shopping"></i> Stock</th>
                <th> <i class="fa-solid fa-square-poll-vertical"></i> IVA</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php /*$sumaCosto=0;
            $q = (isset($_REQUEST['sucursal']))? $_REQUEST['sucursal']:"";
            foreach($this->model->ListarBuscar($q) as $r): 
            if(true){ ?>
            
            <tr class="click">
                <td><?php echo $r->codigo; ?></td>
                <td><?php echo substr($r->categoria,0,15); ?></td>
                <td><?php echo substr($r->categoria,0,15); ?></td>
                <td><a href="?c=venta&a=listarproducto&id_producto=<?php echo $r->id; ?>"><?php echo substr($r->producto,0,100); ?></a></td>
                <td><?php echo number_format($r->precio_costo,0,".",","); ?></td>
                <td><?php echo number_format($r->precio_minorista,0,".",","); ?></td>
                <td><?php echo $r->stock; ?></td>
                <td><?php echo $r->iva; ?></td>
                <?php if($_SESSION['nivel']<=1){ ?>
                <td>
                    <a  class="btn btn-warning edit" href="#crudModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-id="<?php echo $r->id;?>" data-c="producto">Edit</a>
                </td>
                <td>
                    <a  class="btn btn-danger delete" href="?c=producto&a=Eliminar&id=<?php echo $r->id; ?>">Borrar</a>
                </td>
                <?php } ?>
            </tr>
            <?php $sumaCosto+=($r->precio_costo*$r->stock); } endforeach; */?>
            
        </tbody>
        <tfoot>
            <tr style="background-color: #000; color:#fff">
                <th>Código</th>
                <th>Sub Categoría</th>
                <th>Producto</th>
                <th> Marca</th>
                 <th>Descripcion</th>
                 <th>Chasis</th>
                 <th>Motor</th>
                 <th>Kilometraje</th>
                 <th>Pais de Origen</th>
                 <th>Chapa</th>
                 <th>Fecha deImportacion</th>
                <th>Financiado</th>
                <th>Contado </th>
                <th>Stock</th>
                <th>IVA</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
</div>
</div>
<?php include("view/crud-modal.php"); ?>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina -P & Q AUTOMOTORES SA.<?php echo date("Y") ?></a></li>
    </ul>

</dir>

<script type="text/javascript">
    $(document).ready(function() {
        
        $('#tabla tfoot th').each( function () {
        var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
        } );
     
        // DataTable
        let tablaUsuarios = $('#tabla').DataTable({
            "ajax":{            
                "url": "?c=producto&a=ListarAJAX",
                "dataSrc":""
            },
            "columns":[
                {"data": "codigo",
                    render: function(data, type, row) {
                        let link = "?c=venta&a=listarproducto&id_producto="+row.id;
                        return '<a href="' + link + '" class="btn btn-default">' + data + '</a>';
                    }
                },
                {"data": "sub_categoria"},
                {"data": "producto"},
                {"data": "marcaVehiculo"},
                {"data": "descripcion"},
                {"data": "vin"},
                {"data": "motor"},
                {"data": "kilometraje"},
                {"data": "pais_origen"},
                {"data": "placa"},
                {"data": "fecha_importacion"},
                {"data": "precio_financiado"},
                {"data": "precio_minorista"},
                {"data": "stock"},
                {"data": "iva"},
                {"defaultContent": "<div class='text-center'><div class='btn-group'><button class='btn btn-success btn-sm btnEditar'>Edit</button><button class='btn btn-danger btn-sm btnBorrar'>Delete</button></div></div>"},
                                {
                  "data": null,
                  "render": function(data, type, row) {
                    return `<button class="btn btn-primary btn-sm btnPDF" data-id="${row.id}">
                              <i class="fa fa-file-pdf"></i> PDF
                            </button>`;
                  }
                }
            ],
            "dom": 'Bfrtip',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ],
            "stateSave": true,
        	responsive: {
            	details: true
        	},
            "language": {
                "lengthMenu":"Mostrar _MENU_ registros por página.",
                "search" : "Buscar en todos",
                "buttons": {
                    "colvis": "Columnas Visibles"
                },
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
            initComplete: function () {
                // Apply the search
                this.api().columns().every( function () {
                    var that = this;
     
                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    } );
                } );
            }
        });
       
        $("#tabla tbody").on("click", ".btnBorrar", function(){
        
            let data = tablaUsuarios.row($(this).parents()).data();        
            id = data.id;		       
            var respuesta = confirm("¿Está seguro de borrar "+data.producto+"?");                
            
            var url = "?c=producto&a=Eliminar&id="+id;
            tablaUsuarios.row(0).remove().draw();
    		$.ajax({
    
    			url: url,
    			method : "POST",
    			data: id,
    			cache: false,
    			contentType: false,
    			processData: false,
    			success:function(respuesta){
    			}
    
    		})
        });
        
        $("#tabla tbody").on("click", ".btnEditar", function(){
        
            let data = tablaUsuarios.row($(this).parents()).data();        
            var id = data.id;
            //var id = parseInt($(this).closest('tr').find('td:eq(0)').text()) ;	
            $('#crudModal').modal('show'); 
            var url = "?c=producto&a=obtener&id="+id;
    		$.ajax({
    
    			url: url,
    			method : "POST",
    			data: id,
    			cache: false,
    			contentType: false,
    			processData: false,
    			success:function(respuesta){
    				$("#edit_form").html(respuesta);
    			}
    
    		})
            
        });
        // Asegúrate de que este código esté después de la inicialización de DataTable
        $("#tabla tbody").on("click", ".btnPDF", function() {
    let row = $(this).closest('tr');
    if (row.hasClass('child')) {
        row = row.prev();
    }

    let data = tablaUsuarios.row(row).data();

    if (!data || !data.id) {
        alert("No se pudo obtener el vehículo.");
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "?c=producto&a=informeVehicular", true);
    xhr.responseType = "blob";

    xhr.onload = function () {
        if (xhr.status === 200) {
            let blob = xhr.response;
            let url = window.URL.createObjectURL(blob);

            // 👇 Acá en vez de crear un link para descargar, abrimos una nueva pestaña
            window.open(url, "_blank");
        } else {
            alert("Error al generar el PDF.");
        }
    };

    let formData = new FormData();
    formData.append("vehiculo", JSON.stringify(data));
    xhr.send(formData);
});
     
    } );
    
</script>

<script type="text/javascript">
    $(document).ready(function() {
    // Setup - add a text input to each footer cell
        $('#tabla tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Buscar '+title+'" />' );
        } );
     
    } );
</script>

<style type="text/css">
    tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
        color :black;
    }
</style>
</script>


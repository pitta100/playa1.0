<?php 
$licenciaPath = __DIR__ . '/../../../licencia/licencia.key';
$claveEsperada = 'p&8automotoressapitta100198830';

// Verifica si el archivo existe y si contiene la clave correcta
if (!file_exists($licenciaPath) || trim(file_get_contents($licenciaPath)) !== $claveEsperada) {
    die('🔒 Acceso denegado. Esta instalación no está autorizada.');
}
 ?>
<h1 class="page-header">Lista de ventas de servicios &nbsp;
<a class="btn btn-success" href="#diaModal" class="btn btn-primary" data-toggle="modal" data-target="#diaModal"> <i class="fa-regular fa-calendar-days"></i> Informe diario</a>
<a class="btn btn-success" href="#mesModal" class="btn btn-primary" data-toggle="modal" data-target="#mesModal"> <i class="fa-regular fa-calendar-days"></i> Informe Mensual</a></h1>
<a class="btn btn-success pull-right" href="?c=venta_tmp" class="btn btn-success"> <i class="fa-solid fa-hand-holding-dollar"></i> Nueva venta de servicios</a>
<br><br><br>

<h3 id="filtrar" align="center">Buscar por fechas <i class="fas fa-angle-right"></i><i class="fas fa-angle-left" style="display: none"></i></h3>
<div class="row">
    <div class="col-sm-12">
        <div align="center" id="filtro">
            <form method="get">
                <input type="hidden" name="c" value="venta">
                
                 <div class="form-group col-md-2">
                </div>
                <div class="form-group col-md-2">
                </div>
                <div class="form-group col-md-2">
                    <label>Desde</label>
                    <input type="date" name="desde" value="<?php //echo (isset($_GET['desde']))? $_GET['desde']:''; ?>" class="form-control">
                </div>
                <div class="form-group col-md-2">
                    <label>Hasta</label>
                    <input type="date" name="hasta" value="<?php //echo (isset($_GET['hasta']))? $_GET['hasta']:''; ?>" class="form-control">
                </div>
                
                <div class="form-group col-md-2">
                    <label></label>
                    <input type="submit" value="Filtrar" class="form-control btn btn-success">
                </div>
                
            </form>
        </div>
    </div>
</div>
<!--<table class="table table-striped table-bordered display responsive nowrap " id="tabla" width="100%">-->
<table id="tabla" class="table table-striped table-bordered display responsive " width="100%">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th> <i class="fa-solid fa-language"></i> ID </th>
            <th> <i class="fa-regular fa-calendar-days"></i> Fecha y Hora</th>
            <th> <i class="fa-solid fa-receipt"></i> Comprobante</th>
            <th> <i class="fa-solid fa-receipt"></i> Nro. comprobante</th>
            <th> <i class="fa-solid fa-handshake"></i> Pago</th>
            <th>Costo</th>
            <th>Total</th>
            <th> <i class="fa-solid fa-hand-holding-dollar"></i> Ganancia</th>
            <th></th>  
            <th></th>
            <th></th>
            <th></th>
            <th></th>
    </thead>
    <tbody>
    <?php /*
    $suma = 0; $count = 0;  
    $id_venta = (isset($_REQUEST['id_venta']))? $_REQUEST['id_venta']:0;
    $suma = 0; $count = 0;  
    foreach($this->model->Listar($id_venta) as $r): ?>
        <tr class="click" <?php if($r->anulado){echo "style='color:gray'";} ?>>
            <?php if (isset($_REQUEST['id_venta'])): ?>
            <td><?php echo $r->producto; ?></td>    
            <?php endif ?>
            <td><?php echo $r->id_venta; ?></td>
            <td><?php echo date("d/m/Y H:i", strtotime($r->fecha_venta)); ?></td>
            <td><?php echo $r->comprobante; ?></td>
            <td><?php echo $r->nro_comprobante; ?></td>
            <td><?php echo $r->metodo; ?></td>
            <td><?php echo $r->contado; ?></td>
            <td><?php echo number_format($r->total,0,".",","); ?></td>
            <?php if (!isset($_GET['id_venta'])): ?>
            <td>
                <a href="#detallesModal" class="btn btn-success" data-toggle="modal" data-target="#detallesModal" data-id="<?php echo $r->id_venta;?>">Ver</a>
                <a  class="btn btn-warning" href="?c=venta&a=ticket&id=<?php echo $r->id_venta ?>" class="btn btn-success">Reimprimir</a>
                <!--<a  class="btn btn-primary edit" href="?c=venta_tmp&a=editar&id=<?php //echo $r->id_venta ?>" class="btn btn-success" >Editar</a>-->
                <?php if ($r->anulado): ?>
                ANULADO    
                <?php else: ?>
                 <?php if($r->comprobante=="Factura"){ ?>
                 
                 <a  class="btn btn-warning" href="?c=devolucion_tmp&id_venta=<?php echo $r->id_venta ?>" class="btn btn-success">Devolución</a>
                <?php } ?>
                <a  class="btn btn-danger delete" href="?c=venta&a=anular&id=<?php echo $r->id_venta ?>" class="btn btn-success">ANULAR</a>
                <?php endif ?>
            </td>
            <?php endif ?>
        </tr>
    <?php 
        $count++;
    endforeach; */?>
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
<?php include("view/venta/mes-modal.php"); ?>
<?php include("view/venta/dia-modal.php"); ?>
<?php include("view/venta/detalles-modal.php"); ?>


<script type="text/javascript">
    $(document).ready(function() {
        
        let tablaUsuarios = $('#tabla').DataTable({
            
             "dom": 'Bfrtip',
            "buttons": [{
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            }, {
                extend: 'pdfHtml5',
                footer: true,
                title: "Gastos",
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: [0,1,2,3,4,6,7,9]
                }
            }, 'colvis'],
           
            responsive: {
                details: true
            },
             <?php if(isset($_GET['desde'])){ ?>
              "ajax":{            
                    "url": "?c=venta&a=ListarFiltros&desde=<?php echo $_GET['desde']?>&hasta=<?php echo $_GET['hasta']?>",
                    "dataSrc":""
                },
            <?php }else{ ?>
            
                    "ajax":{            
                    "url": "?c=venta&a=ListarAjax",
                    "dataSrc":""
                },    
                <?php } ?>

            "columns":[
                {"data": "id_venta"},
                {"data": "fecha_venta"},
                {"data": "",
                 render: function(data, type, row) {
                       
                            if(row.comprobante == 'Ticket' ){
                                return 'Ticket' ;
                            }else if(row.comprobante == 'TicketSi' ){
                                return 'Sin Impresión';
                            }else{
                                 return 'Factura';
                            }
                        } 
                },
                {"data": "nro_comprobante"},
                {"data": "contado"},
                {"data": "costo", render: $.fn.dataTable.render.number( ',', '.', 0)},
                {"data": "total", render: $.fn.dataTable.render.number( ',', '.', 0)},
                {"data": "ganancia", render: $.fn.dataTable.render.number( ',', '.', 0)},
                {"defaultContent": "",
                    render: function(data, type, row) {
                       
                           
                               return "<a href='#detallesModal' class='btn btn-success' data-toggle='modal' data-target='#detallesModal' data-c='venta' data-id='"+row.id_venta+"'> Ver</a>";
                             
                        }    
                 },
                {"defaultContent": "",
                    render: function(data, type, row) {
                       
                            if(row.comprobante == 'Ticket' ){
                               let link = "?c=venta&a=ticket&id="+row.id_venta;
                                return '<a href="' + link + '" class="btn btn-warning">Ticket</a>';
                            }else{
                                return '';
                            }
                        }    
                 },
                 {"defaultContent": "",
                    render: function(data, type, row) {
                       
                           if(row.comprobante == 'Factura' ){
                               let link = "?c=venta&a=factura&id="+row.id_venta;
                                return '<a href="' + link + '" class="btn btn-primary">Factura</a>';
                           }else{
                               return '';
                           }
                        }    
                 },
                  {
                    "defaultContent": "",
                    render: function(data, type, row) {
                        let link = "?c=venta&a=specificInformation&id=" + row.id_venta;
                        return '<a href="' + link + '" class="btn btn-primary">PDF</a>';
                    }
                },
                 {"defaultContent": "",
                    render: function(data, type, row) {
                        if(row.anulado==1){
                            return 'ANULADO'
                        }else{
                       
                            let link = "?c=venta&a=anular&id="+row.id_venta;
                            return '<a href="' + link + '" class="btn btn-danger">Eliminar</a>';
                        
                        }
                        
                    }
                 }

                ],
              
        });
    });
</script>
<script type="text/javascript">
    $( "#filtrar" ).click(function() {
      $("#filtro").toggle("slow");
      $("i").toggle();
    });
</script>


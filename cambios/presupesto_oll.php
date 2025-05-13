<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablas de Pedidos</title>
    <!-- Include Bootstrap CSS -->
    <style>
       .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem; /* Disminuir el espacio entre tarjetas */
            margin-bottom: 2rem; /* Espacio inferior */
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            padding: 1rem; /* Ajustar el relleno interno */
            margin: 0.5rem; /* Reducir el margen alrededor de las tarjetas */
            width: calc(20% - 1rem); /* Mostrar 5 tarjetas por fila, ajusta el porcentaje según sea necesario */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Reducir la sombra de las tarjetas */
        }

        .card-header {
            background: linear-gradient(to right, #FF0000, #1b6c28);
            color: #FFFFFF;
            padding: 0.5rem; /* Ajustar el relleno */
            border-bottom: 1px solid #ddd;
            border-radius: 0.25rem 0.25rem 0 0;
        }

        .card-body {
            padding: 1rem; /* Ajustar el relleno interno */
        }

        .card-footer {
            text-align: right;
            border-top: 1px solid #ddd;
            padding: 0.75rem; /* Ajustar el relleno */
        }


        .badge-info { background-color: #17a2b8; color: #fff; }
        .badge-primary { background-color: #ff0500; color: #fff; }
        .badge-success { background-color: #28a745; color: #fff; }
        .badge-secondary { background-color: #6c757d; color: #fff; }
        /* Estilo para la tabla */
        #tabla, #tabla1 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        #tabla thead tr, #tabla1 thead tr {
            background: linear-gradient(to right, #00FF00, #006400); /* Gradiente verde */
            color: #FFFFFF;
        }
        #tabla thead th, #tabla1 thead th {
            padding: 10px;
            border: 1px solid #000;
        }
        #tabla tbody tr:nth-child(even), #tabla1 tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
        #tabla tbody tr:nth-child(odd), #tabla1 tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        #tabla tbody td, #tabla1 tbody td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        #tabla tbody tr:last-child, #tabla1 tbody tr:last-child {
            background-color: #e0e0e0;
        }
        #tabla, #tabla1 {
            border: 1px solid #000;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="page-header">Tablas de Pedidos &nbsp;</h1>
        <h1>Vigentes</h1>
        <a class="btn btn-primary pull-right" href="?c=presupuesto_tmp">Nuevo Presupuesto de Comida</a>
        
        <?php if ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 2 || $_SESSION['nivel'] == 3): ?>
            <div class="card-container">
                <!-- Las tarjetas de pedidos se cargarán aquí mediante AJAX -->
            </div>
        <?php endif; ?>


        <?php if ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 2): ?>
            <h1>Finalizados</h1>
            <br>
            <table class="table table-striped table-bordered display nowrap responsive" width="100%" id="tabla1">
                <thead>
                    <tr style="background-color: black; color:#fff">
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Mro de Mesa</th>
                        <th>MOZO</th>
                        <th>Descripcion</th>
                        <th>Total</th>        
                        <th>Ver</th>
                        <th>Editar</th>
                        <th>Reimprimir</th>
                        <th>Venta</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Include jQuery library -->
    <?php include("view/crud-modal.php"); ?>
    <?php include("view/presupuesto/detalles-modal.php"); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            // DataTable for 'tabla1'
            let tablaFinalizados = $('#tabla1').DataTable({
                "ajax": {            
                    "url": "?c=presupuesto&a=ListarAJAXfinalizados",
                    "dataSrc": ""
                },               
                "columns": [
                    {"data": "id_venta"},
                    {"data": "fecha_venta"},
                    {"data": "nombre_cli"},
                    {"data": "id_mesa"},
                    {"data": "encargado"},
                    {"data": "motivo_cliente"},
                    {"data": "total"},
                    {
                        "defaultContent": "",
                        "render": function(data, type, row) {
                            return "<a href='#detallesPresupuestoModal' class='btn btn-info' data-toggle='modal' data-target='#detallesPresupuestoModal' data-c='presupuesto' data-id='"+row.id_venta+"'>Ver</a>";
                        }    
                    },
                    {
                        "defaultContent": "",
                        "render": function(data, type, row) {
                            let link = "?c=presupuesto&a=GuardarP&id="+row.id_venta;
                            return '<a href="' + link + '" class="btn btn-warning">Editar</a>';
                        }    
                    },
                    {
                        "defaultContent": "",
                        "render": function(data, type, row) {
                            let link = "?c=presupuesto&a=presupuesto&id="+row.id_venta;
                            return '<a href="' + link + '" class="btn btn-success">Reimprimir</a>';
                        }    
                    },
                    {
                        "defaultContent": "",
                        "render": function(data, type, row) {
                            let link = "?c=presupuesto&a=GuardarV&id="+row.id_venta;
                            return '<a href="' + link + '" class="btn btn-primary">Venta</a>';
                        }
                    }
                ],
                "language": {
                    "thousands": ".",
                    "lengthMenu": "Mostrar _MENU_ registros por Página de BarBirramania.",
                    "search": "Buscar",
                    "zeroRecords": "Lo sentimos. No se encontraron registros.",
                    "info": "Mostrando Página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros.",
                    "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                    "LoadingRecords": "Cargando ...",
                    "Processing": "Procesando...",
                    "SearchPlaceholder": "Comience a teclear...",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                },
                "sort": false,
                "stateSave": true,
                "initComplete": function () {
                    this.api().columns().every(function () {
                        var that = this;
                        $('input', this.footer()).on('keyup change clear', function () {
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });
                },
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            // Cargar tarjetas
            function cargarTarjetas() {
                $.ajax({
                    url: "?c=presupuesto&a=ListarAjax",
                    type: "GET",
                    success: function(data) {
                        if (typeof data === 'string') {
                            data = JSON.parse(data);
                        }
                        let cardContainer = $('.card-container');
                        cardContainer.empty();
                        $.each(data, function(index, pedido) {
                            let estadoBadge = '';
                            if (pedido.km == 0) {
                                estadoBadge = "<span class='badge badge-info'>Pendiente</span>";
                            } else if (pedido.km == 1) {
                                estadoBadge = "<span class='badge badge-primary'>En-Progreso</span>";
                            } else if (pedido.km == 2) {
                                estadoBadge = "<span class='badge badge-success'>Completo</span>";
                            } else {
                                estadoBadge = "<span class='badge badge-secondary'>Desconocido</span>";
                            }
                            let cardHtml = `
                                <div class="card">
                                    <div class="card-header">
                                        Pedido ID: ${pedido.id_venta}
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Cliente:</strong> ${pedido.nombre_cli}</p>
                                        <p><strong>MESA NRO:</strong> ${pedido.id_mesa}</p>
                                        <p><strong>MOZO:</strong> ${pedido.encargado}</p>
                                        <p><strong>Producto:</strong> ${pedido.producto}</p>
                                        <p><strong>Descripción:</strong> ${pedido.motivo_cliente}</p>
                                        <p><strong>Estado:</strong> ${estadoBadge}</p>
                                    </div>
                                    <div class="card-footer">
                                        <a href='#detallesPresupuestoModal' class='btn btn-info' data-toggle='modal' data-target='#detallesPresupuestoModal' data-c='presupuesto' data-id='${pedido.id_venta}'>Ver</a>
                                        <a href='?c=presupuesto&a=GuardarP&id=${pedido.id_venta}' class='btn btn-warning'>Editar</a>
                                        <a href='?c=presupuesto&a=presupuesto&id=${pedido.id_venta}' class='btn btn-success'>Reimprimir</a>
                                        <a href='?c=presupuesto&a=GuardarV&id=${pedido.id_venta}' class='btn btn-primary'>Venta</a>
                                    </div>
                                </div>
                            `;
                            cardContainer.append(cardHtml);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error:', textStatus, errorThrown);
                        alert("Error al realizar la solicitud.");
                    }
                });
            }

            cargarTarjetas();
            setInterval(cargarTarjetas, 10000);

            window.cambiarEstado = function(id_venta, nuevo_estado) {
                $.ajax({
                    url: "?c=presupuesto&a=cambiarEstado",
                    type: "POST",
                    data: {
                        id: id_venta,
                        km: nuevo_estado
                    },
                    success: function(response) {
                        if (typeof response === 'string') {
                            response = JSON.parse(response);
                        }
                        if (response.success) {
                            cargarTarjetas();
                        } else {
                            alert("Error al actualizar el estado.");
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error:', textStatus, errorThrown);
                        alert("Error al realizar la solicitud.");
                    }
                });
            }
        });
    </script>
</body>
</html>

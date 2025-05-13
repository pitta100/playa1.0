<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero Principal</title>
    <link rel="stylesheet" href="assets/libs/fullcalendar.min.css" />
    <script src="assets/libs/moment.min.js"></script>
    <script src="assets/libs/fullcalendar.min.js"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .full-screen {
            height: 100vh;
            background-color: #f4f4f4;
        }

        #calendar {
            max-width: 100%;
            margin: 0 auto;
            padding: 10px;
        }

        .content-wrapper {
            padding: 15px;
        }

        .page-header {
            margin-top: 20px;
            text-align: center;
            color: #333;
        }

        .card-body {
            padding: 15px;
        }

        .card {
            margin-top: 20px;
        }

        /* CSS para centrar el modal */
        .modal-dialog {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .modal-content {
            margin: 0 auto;
        }
    </style>
</head>
<body>

    <a class="btn btn-success pull-right" href="#notaModal" class="btn btn-success" data-toggle="modal" data-target="#notaModal" data-c="deuda"> <i class="fa-solid fa-cloud-arrow-up"></i> AGREGAR NOTA</a>

    <div class="content-wrapper">
        <h1 class="page-header">LISTA DE COMPROBANTE</h1>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-titleDos">Calendario de Vencimientos</h3>
                        </div>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" id="collapse-btn">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove" id="remove-btn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar los detalles del evento -->
    <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailsModalLabel">Detalles del Evento</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Cliente:</strong> <span id="modalCliente"></span></p>
                    <p><strong>Concepto:</strong> <span id="modalConcepto"></span></p>
                    <p><strong>Monto:</strong> <span id="modalMonto"></span></p>
                    <p><strong>Saldo:</strong> <span id="modalSaldo"></span></p>
                    <p><strong>Vencimiento:</strong> <span id="modalVencimiento"></span></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                </div>
            </div>
        </div>
    </div>
     <!-- Modal para mostrar los detalles del evento -->
    <div class="modal fade" id="eventNotaContentModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailsModalLabel">Detalles de la Nota</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>fecha:</strong> <span id="modalFecha"></span></p>
                    <p><strong>nota:</strong> <span id="modalNota"></span></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                </div>
            </div>
        </div>
    </div>


    <?php include("view/deuda/nota-modal.php"); ?>

<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            locale: 'es',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: function(start, end, timezone, callback) {
                var events = [];

                // Obtener los eventos de las deudas
                $.ajax({
                    url: '?c=deuda&a=listarDeudaCalendar',
                    dataType: 'json',
                    success: function(data) {
                        console.log("Datos de deudas:", data);  // Verifica los datos de las deudas en la consola
                        // Mapea las deudas a eventos
                        data.forEach(function(item) {
                            events.push({
                                title: item.cliente_nombre + ' - ' + item.concepto,
                                start: item.vencimiento,
                                description: 'Monto: ' + item.monto + ' | Saldo: ' + item.saldo + ' | Vencimiento: ' + item.vencimiento,
                                color: item.saldo <= 0 ? '#28a745' : '#ff0000',
                                id: item.id,
                                allDay: true
                            });
                        });

                        // Obtener los eventos de las notas
                        $.ajax({
                            url: '?c=nota&a=ctrlistarNotasCalendar',
                            dataType: 'json',
                            success: function(notas) {
                                console.log("Datos de notas:", notas);  // Verifica los datos de las notas en la consola
                                // Mapea las notas a eventos
                                notas.forEach(function(nota) {
                                    events.push({
                                        title: nota.nota,  // Título de la nota
                                        start: nota.fecha,  // Fecha de la nota
                                        color: '#3498db',  // Color para las notas
                                        allDay: true,  // Asegura que el evento ocupe todo el día
                                        extendedProps: {
                                            contenido: nota.nota  // Agregamos el contenido de la nota como extendedProps
                                        }
                                    });
                                });

                                // Llamamos a callback con todos los eventos combinados
                                callback(events);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error al cargar las notas:', error);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar los eventos de deudas:', error);
                    }
                });
            },

            // Unificar el evento click
            eventClick: function(event) {
                console.log(event); // Imprimir el objeto del evento

                if (event.description) {
                    // Mostrar detalles de la deuda al hacer clic en la deuda
                    $('#modalCliente').text(event.title);  // Establece el cliente
                    $('#modalConcepto').text(event.title.split(' - ')[1]);  // Establece el concepto
                    $('#modalMonto').text(event.description.split(' | ')[0].split(': ')[1]);  // Establece el monto
                    $('#modalSaldo').text(event.description.split(' | ')[1].split(': ')[1]);  // Establece el saldo
                    $('#modalVencimiento').text(event.description.split(' | ')[2].split(': ')[1]);  // Establece la fecha de vencimiento

                    // Muestra el modal con los detalles de la deuda
                    $('#eventDetailsModal').modal('show');
                } else if (event.extendedProps && event.extendedProps.contenido) {
                    // Mostrar detalles de la nota al hacer clic en la nota
                    $('#modalFecha').text(event.start.format('YYYY-MM-DD'));  // Usamos la fecha del evento
                    $('#modalNota').text(event.extendedProps.contenido);  // Usamos el contenido de la nota desde extendedProps

                    // Muestra el modal con los detalles de la nota
                    $('#eventNotaContentModal').modal('show');
                }
            }
        });

        // Cargar el formulario para agregar notas
        $('#notaModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // El botón que activó el modal
            var id = button.data('id'); // Extrae el ID desde el atributo "data-id"
            var url = "?c=deuda&a=formulario"; // URL para cargar el formulario en el modal
            $.ajax({
                url: url,
                method: "POST",
                data: { id: id },
                cache: false,
                success: function(respuesta) {
                    $("#nota-detalles").html(respuesta); // Coloca el formulario en el modal
                },
                error: function() {
                    alert('Hubo un error al cargar el formulario.');
                }
            });
        });

        // Función para manejar el colapso y expansión del card
        $('[data-card-widget="collapse"]').on('click', function () {
            var $card = $(this).closest('.card');
            $card.find('.card-body').slideToggle();
            $(this).find('i').toggleClass('fas fa-minus fas fa-plus');
        });

        // Funcionalidad para eliminar el card
        $('[data-card-widget="remove"]').on('click', function () {
            $(this).closest('.card').remove();
        });
    });
</script>

   
</body>
</html>

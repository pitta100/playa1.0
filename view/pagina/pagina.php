<h1 class="page-header">Tablero Principal</h1>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">

    <div class="container-fluid">
      <!-- /.row -->
      <div class="row">
        <!-- Columna de New Orders -->
        <div class="col-lg-2 col-md-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h4 id="totalProductos"></h4>
                <p>Total Productos</p>
              </div>
              <div class="icon">
                <!-- Reemplazar el icono con una imagen .ico -->
                <img src="assets/img/pitta011.ico" alt="Icono"> <!-- Ajusta el tamaño según necesites -->
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <div class="col-lg-2 col-md-4 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h4 id="totalCompras"></h4>
              <p>Total Compras</p>
            </div>
            <div class="icon">
              <img src="assets/img/pitta02.ico" alt="Icono" > <!-- Ajusta el tamaño según necesites -->
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h4 id="ganancias"></h4>
              <p>Ganancias</p>
            </div>
            <div class="icon">
               <img src="assets/img/pitta03.ico" alt="Icono"> <!-- Ajusta el tamaño según necesites -->
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h4 id="productoPocoStock"></h4>
              <p>Poco Stock</p>
            </div>
            <div class="icon">
             <img src="assets/img/pitta04.ico" alt="Icono"> <!-- Ajusta el tamaño según necesites -->
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
          <!-- small box -->
          <div class="small-box bg-primary">
            <div class="inner">
              <h4 id="ventasHoy"></h4>
              <p>Ventas de hoy</p>
            </div>
            <div class="icon">
              <img src="assets/img/pitta05.ico" alt="Icono"> <!-- Ajusta el tamaño según necesites -->
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!--<div class="col-lg-2 col-md-4 col-6">
     
          <div class="small-box bg-secundary">
            <div class="inner">
              <h3>150</h3>
              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>-->
      </div>
      
      <!-- /.row -->
      <div class="row">
        <div class="col-12">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-titleDos"></h3>
            </div>
            <div class="card-tools">
              <!-- Botón para expandir y colapsar con ID -->
              <button type="button" class="btn btn-tool" data-card-widget="collapse" id="collapse-btn">
                <i class="fas fa-minus"></i>
              </button>
              <!-- Botón para eliminar con ID -->
              <button type="button" class="btn btn-tool" data-card-widget="remove" id="remove-btn">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <div class="card-body">
             <div class="chart">
                <canvas id="barChart" style="min-height: 250px;height: 300px;max-height: 350px; width: 100%;">
                </canvas>
             </div>
            </div>
          </div>
        </div>
      </div>

      
      <div class="row">
        <div class="col-lg-6">
          <div class="card card-info">
              <div class="card-header">
                  <h3 class="card-title">Los 10 Productos mas vendidos </h3>
                  <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                          <i class="fas fa-times"></i>
                      </button>
                  </div> <!-- ./ end card-tools -->
              </div> <!-- ./ end card-header -->
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="tbla_productos_mas_vendidos">
                      <thead class="bg-info">
                         <tr style="font-size: 15px;">
                             <th>Codigo</th>
                             <th>Producto</th>
                             <th>Cantidad</th>
                             <th>Venta</th>
                         </tr>
                     </thead>
                     <tbody>
                       
                     </tbody>
                  </table>
                </div>
              </div> <!-- ./ end card-body -->
          </div>
        </div>
       
      </div>

    </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina -P & Q AUTOMOTORES SA.<?php echo date("Y") ?></a></li>
    </ul>
<!-- /.content-wrapper -->
<script>
$(document).ready(function () {

  // Realizar la solicitud AJAX cuando se carga el dashboard principal (los cuadritos)
  $.ajax({
    url: 'ajax/dashboard.ajax.php',  // La URL del archivo PHP o endpoint
    method: 'POST',                  // Método HTTP (GET, POST, etc.)
    dataType: 'json',                // Especificamos que esperamos una respuesta en formato JSON
    success: function(respuesta) {   // Función que se ejecuta cuando la solicitud es exitosa
      // Insertar los valores directamente sin formatear
      $("#totalProductos").html('P./' + respuesta[0]['totalProductos'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
      $("#totalCompras").html('G./' + respuesta[0]['totalCompras'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
      $("#ganancias").html('G./' + respuesta[0]['ganancias'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
      $("#productoPocoStock").html('P./' + respuesta[0]['productoPocoStock'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
      $("#ventasHoy").html('G./' + respuesta[0]['ventasHoy'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      console.log("respuesta", respuesta);  // Muestra la respuesta en la consola para depuración
    },
    error: function(xhr, status, error) {  // Función que se ejecuta si hay un error
      console.log("Error en la solicitud: ", error);
    }
  });

  // Aplicamos setInterval para actualizar cada 10 minutos (600000 ms)
  setInterval(function () {
    $.ajax({
      url: 'ajax/dashboard.ajax.php',  // La URL del archivo PHP o endpoint
      method: 'POST',                  // Método HTTP (GET, POST, etc.)
      dataType: 'json',                // Especificamos que esperamos una respuesta en formato JSON
      success: function(respuesta) {   // Función que se ejecuta cuando la solicitud es exitosa
        // Insertar los valores directamente sin formatear
        $("#totalProductos").html('P./' + respuesta[0]['totalProductos'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $("#totalCompras").html('G./' + respuesta[0]['totalCompras'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $("#ganancias").html('G./' + respuesta[0]['ganancias'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $("#productoPocoStock").html('P./' + respuesta[0]['productoPocoStock'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $("#ventasHoy").html('G./' + respuesta[0]['ventasHoy'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

        console.log("respuesta", respuesta);  // Muestra la respuesta en la consola para depuración
      },
      error: function(xhr, status, error) {  // Función que se ejecuta si hay un error
        console.log("Error en la solicitud: ", error);
      }
    });
  }, 600000);  // 600000 ms = 10 minutos

  // Funcionalidad para expandir/colapsar el card
  $('[data-card-widget="collapse"]').on('click', function () {
    var $card = $(this).closest('.card'); // Selecciona el card más cercano
    $card.find('.card-body').slideToggle(); // Colapsa o expande el cuerpo
    $(this).find('i').toggleClass('fas fa-minus fas fa-plus'); // Cambia el ícono
  });

  // Funcionalidad para eliminar el card
  $('[data-card-widget="remove"]').on('click', function () {
    $(this).closest('.card').remove(); // Elimina el card completo
  });

  // Solicitud para mostrar el cuadro de barras
  $.ajax({
    url: 'ajax/dashboard.ajax.php',  // La URL del archivo PHP o endpoint
    method: 'POST',
    data: {
      'accion': 1  // Parámetro para obtener la venta del mes
    },
    dataType: 'json',  // Especificamos que esperamos una respuesta en formato JSON
    success: function(respuesta) {  // Función que se ejecuta cuando la solicitud es exitosa
      if (respuesta.success) {
        console.log("Ventas del mes:", respuesta.ventas);
        var fecha_venta = [];
        var total_venta = [];
        var total_venta_mes = 0;

        // Recorrer los datos de respuesta.ventas para sumar los totales
        for (let i = 0; i < respuesta.ventas.length; i++) {
          fecha_venta.push(respuesta.ventas[i]['fecha_venta']);
          total_venta.push(respuesta.ventas[i]['total_venta']);

          // Depuración: Ver los valores que estamos sumando
          console.log("Venta del día:", respuesta.ventas[i]['total_venta']); // Ver el valor de cada venta

          // Convertir el valor de total_venta a número y acumularlo
          let venta = parseFloat(respuesta.ventas[i]['total_venta']);
          console.log("Venta como número:", venta);  // Verificación de que la conversión es correcta

          if (!isNaN(venta)) { // Si el valor no es NaN, sumarlo
            total_venta_mes += venta;
          } else {
            console.log("Valor no válido para total_venta:", respuesta.ventas[i]['total_venta']);
          }
        }

        // Depuración: Ver el total antes de mostrarlo
        console.log("Total ventas mes:", total_venta_mes);

        // Actualizar el título de la card con el total de ventas del mes
        $(".card-titleDos").html('Ventas del mes G/ ' + total_venta_mes.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

        var barChartCanvas = $("#barChart").get(0).getContext('2d'); // Selección correcta del canvas
        // Define los datos del gráfico
        var areaChartData = {
          labels: fecha_venta, // Array de fechas
          datasets: [{
            label: 'Ventas del Mes',
            backgroundColor: function(context) {
              var value = context.dataset.data[context.dataIndex]; // Obtener el valor de la barra
              var max = Math.max.apply(Math, context.dataset.data); // Obtener el valor máximo
              var min = Math.min.apply(Math, context.dataset.data); // Obtener el valor mínimo

              // Si el valor es el máximo, el color será verde, si es el mínimo será rojo, de lo contrario, azul
              if (value === max) {
                return 'rgba(40, 167, 69, 0.9)'; // Verde
              } else if (value === min) {
                return 'rgba(220, 53, 69, 0.9)'; // Rojo
              } else {
                return 'rgba(60,141,188,0.9)'; // Azul para los valores intermedios
              }
            },
            data: total_venta // Array de ventas
          }]
        };

        // Crear una copia profunda de 'areaChartData' usando $.extend() para evitar modificar el original
        var barChartData = $.extend(true, {}, areaChartData);

        // Asignar los datos del primer conjunto de datos de 'areaChartData' a 'barChartData'
        var temp0 = areaChartData.datasets[0];
        barChartData.datasets[0] = temp0;

        // Configuración de las opciones del gráfico
        var barChartOptions = {
          maintainAspectRatio: false,
          responsive: true,
          events: ['mousemove', 'mouseout', 'click'], // Asegura que el gráfico puede manejar eventos
          legend: {
            display: true,
          },
          tooltips: {
            enabled: true, // Habilita los tooltips
            mode: 'index', // Muestra el tooltip para el índice del punto
            intersect: false, // Si es false, se muestra el tooltip incluso si el mouse no está directamente sobre la barra
            callbacks: {
              // Personaliza la información mostrada en los tooltips
              label: function(tooltipItem, data) {
                // Muestra el valor directamente
                return 'Valor: ' + tooltipItem.yLabel;
              }
            }
          },
          animation: {
            duration: 500, // Duración de la animación (en milisegundos)
            easing: 'easeInOutQuad', // Tipo de animación
            onComplete: function() {
              // Usar una función de flecha para mantener el contexto de 'this'
              const chart = this.chart; // Aquí usamos 'this.chart' correctamente
              console.log('¡Animación completada!');

              // Obtener el contexto del gráfico
              var ctx = chart.ctx;

              // Configuración de la fuente y el estilo del texto
              ctx.font = "16px Arial";  // Puedes personalizar la fuente
              ctx.textAlign = 'center';
              ctx.textBaseline = 'bottom'; // Esto asegura que el texto se dibuje encima de la barra

              // Iterar sobre los datasets y mostrar los valores
              chart.data.datasets.forEach(function(dataset, datasetIndex) {
                var meta = chart.getDatasetMeta(datasetIndex); // Obtener el meta del dataset
                meta.data.forEach(function(bar, index) {
                  var model = bar._model;
                  var y_pos = model.y - 5;  // Ajusta la posición Y del texto para que se dibuje sobre la barra

                  // Si la barra está cerca del tope, mover el texto hacia abajo para evitar que se sobreponga
                  if (model.y < 10) {
                    y_pos = model.y + 15;  // Ajusta más el texto si está en la parte superior
                  }

                  // Configurar el color del texto
                  ctx.fillStyle = '#444';  // Puedes cambiar el color aquí

                  // Mostrar el valor de los datos sobre la barra
                  ctx.fillText(dataset.data[index], model.x, y_pos);
                });
              });
            }
          }
        };

        // Crear el gráfico de barras
        var barChart = new Chart(barChartCanvas, {
          type: 'bar', // Tipo de gráfico: 'bar' para gráfico de barras
          data: barChartData, // Datos del gráfico
          options: barChartOptions, // Opciones del gráfico
        });

      } else {
        console.log("Error:", respuesta.message);
      }
    },
    error: function(xhr, status, error) {  // Función que se ejecuta si hay un error
      console.log("Error en la solicitud:", error);
    }
  });

  // Solicitud para la tabla de productos más vendidos
  $.ajax({
    url: 'ajax/dashboard.ajax.php',  // La URL del archivo PHP o endpoint
    method: 'POST',
    data: {
      'accion': 2  // Listar los 10 productos más vendidos
    },
    dataType: 'json', // Tipo de datos que esperamos recibir (json, html, text, etc.)
    success: function(respuesta) {
      // Código que se ejecutará si la solicitud es exitosa
      console.log('Respuesta:', respuesta);

      let filas = '';  // Inicia la variable filas vacía fuera del bucle

      for (let i = 0; i < respuesta.length; i++) {
        filas += '<tr>' +  
                    '<td>' + respuesta[i]["id"] + '</td>' +  
                    '<td>' + respuesta[i]["producto"] + '</td>' +  
                    '<td>' + respuesta[i]["cantidad"] + '</td>' +  
                    '<td>Gs./' + respuesta[i]["total_venta"] + '</td>' +   
                 '</tr>';
      }

      // Agregar todas las filas al cuerpo de la tabla una vez al final del bucle
      $("#tbla_productos_mas_vendidos tbody").append(filas);

    },
    error: function(xhr, status, error) {
      // Código que se ejecutará si hay un error en la solicitud
      console.log('Error:', error);
      console.log('Estado:', status);
    }
  });

  // Solicitud para la tabla de productos con poco stock
  $.ajax({
    url: 'ajax/dashboard.ajax.php',  // La URL del archivo PHP o endpoint
    method: 'POST',
    data: {
      'accion': 3  // Listar los 10 productos con poco stock
    },
    dataType: 'json', // Tipo de datos que esperamos recibir (json, html, text, etc.)
    success: function(respuesta) {
      // Código que se ejecutará si la solicitud es exitosa
      console.log('Respuesta:', respuesta);

      let filas = '';  // Inicia la variable filas vacía fuera del bucle

      for (let i = 0; i < respuesta.length; i++) {
        filas += '<tr>' +  
                    '<td>' + respuesta[i]["id"] + '</td>' +  
                    '<td>' + respuesta[i]["producto"] + '</td>' +  
                    '<td>' + respuesta[i]["stock"] + '</td>' +  
                    '<td>' + respuesta[i]["stock_minimo"] + '</td>' +   
                 '</tr>';
      }

      // Agregar todas las filas al cuerpo de la tabla una vez al final del bucle
      $("#tbla_productos_pocos_stock tbody").append(filas);

    },
    error: function(xhr, status, error) {
      // Código que se ejecutará si hay un error en la solicitud
      console.log('Error:', error);
      console.log('Estado:', status);
    }
  });

});
</script>






<!--</script>

<script> Tambien podemos aplicar de este modo fromateando los valores.Este script funciona igual 
$(document).ready(function() {
  $.ajax({
    url: 'ajax/dashboard.ajax.php',  // La URL del archivo PHP o endpoint
    method: 'POST',                  // Método HTTP (GET, POST, etc.)
    dataType: 'json',                // Especificamos que esperamos una respuesta en formato JSON
    success: function(respuesta) {   // Función que se ejecuta cuando la solicitud es exitosa
    // Formateamos los números con separadores de miles
      var totalProductosFormateado = respuesta[0]['totalProductos'].toLocaleString();
      var totalComprasFormateado = respuesta[0]['totalCompras'].toLocaleString();
      var totalGananciasFormateado = respuesta[0]['ganancias'].toLocaleString();
      var totalproductoPocoStockFormateado = respuesta[0]['productoPocoStock'].toLocaleString();
      var totalventasHoyFormateado = respuesta[0]['ventasHoy'].toLocaleString();
      
      // Actualizamos el contenido HTML con los valores formateados
      $("#totalProductos").html(totalProductosFormateado);
      $("#totalCompras").html(totalComprasFormateado);
     // $("#totalCompras").html(respuesta[0]['totalCompras']); asi podemos colocar sin formatear el munero 
      $("#ganancias").html(totalGananciasFormateado);
      $("#productoPocoStock").html(totalproductoPocoStockFormateado);
       $("#ventasHoy").html(totalventasHoyFormateado);
      console.log("respuesta", respuesta);  // Muestra la respuesta en la consola

    },
    error: function(xhr, status, error) {  // Función que se ejecuta si hay un error
      console.log("Error en la solicitud: ", error);

    }
  });
});
</script>-->


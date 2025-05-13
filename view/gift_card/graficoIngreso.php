<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart MySQL</title>
    <style>
    </style>
</head>

<body>
    <div class="col-sm-12">
        <div align="center" id="filtro">
            <form id="dateForm">
                <label for="desde">Desde:</label>
                <input type="date" id="desde" name="desde" required>
                <label for="hasta">Hasta:</label>
                <input type="date" id="hasta" name="hasta" required>
                <input type="submit" value="Filtrar">
            </form>
        </div>
    </div>

    <canvas id="myChart" style="position: relative; height: 40vh; width: 80vw;"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var ctx = document.getElementById('myChart')
        var myChart = new Chart(ctx, {
            type: 'bar',  // Cambié el tipo de gráfico a 'bar'
            data: {
                labels: [], // Fechas en el eje X
                datasets: [{
                    label: 'Total de Monto',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    data: [] // Aquí almacenaremos los totales de ingresos
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Capturar el evento de envío del formulario
        document.getElementById('dateForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevenir el envío del formulario
            updateChart(); // Actualizar el gráfico con las fechas seleccionadas
        });

        let urlBase = "?c=ingreso&a=ListarAjaxingreso";

        function updateChart() {
            // Obtener fechas seleccionadas
            const desde = document.getElementById('desde').value;
            const hasta = document.getElementById('hasta').value;

            // Realizar la solicitud con las fechas seleccionadas
            let url = `${urlBase}&desde=${desde}&hasta=${hasta}`;
            fetch(url)
                .then(response => response.json())
                .then(datos => mostrar(datos))
                .catch(error => console.log(error))
        }

        const mostrar = (ingresos) => {
            console.log("Datos recibidos del servidor:", ingresos);

            // Limpiar las etiquetas anteriores
            myChart.data.labels = [];

            // Limpiar los datos anteriores
            myChart.data.datasets.forEach(dataset => {
                dataset.data = [];
            });

            let totalMonto = 0;

            ingresos.forEach(ingreso => {
                console.log("Ingreso procesado:", ingreso);

                // Verificar si la fecha está dentro del rango seleccionado
                const fecha = new Date(ingreso.fecha);
                const desdeDate = new Date(document.getElementById('desde').value);
                const hastaDate = new Date(document.getElementById('hasta').value + 'T23:59:59'); // Ajustar a la última hora del día

                if (fecha >= desdeDate && fecha <= hastaDate) {
                    // Agregar la fecha al eje X (puedes personalizar esto según tu necesidad)
                    myChart.data.labels.push(ingreso.fecha);

                    // Sumar al total del monto
                    totalMonto += parseFloat(ingreso.monto);

                    // Agregar datos a los datasets
                    myChart.data.datasets[0].data.push(totalMonto);
                }
            });

            // Agregar un mensaje de registro
            console.log("Datos después de mostrar:", myChart.data);

            myChart.update();
            console.log("Datos después de actualizar:", myChart.data);
        }
    </script>
</body>

</html>

             

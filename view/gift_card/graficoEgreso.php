<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart MySQL</title>
    <style>
        canvas {
            max-width: 800px;
            margin: 20px;
        }
    </style>
</head>

<body>

    <!-- Formulario para seleccionar fechas -->
    <form id="dateForm">
        <label for="desde">Desde:</label>
        <input type="date" id="desde" name="desde" required>
        <label for="hasta">Hasta:</label>
        <input type="date" id="hasta" name="hasta" required>
        <input type="submit" value="Filtrar">
    </form>

    <canvas id="myChart" style="position: relative; height: 40vh; width: 80vw;"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var ctx = document.getElementById('myChart')
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [], // Fechas en el eje X
                datasets: [{
                    label: 'Precio Compra',
                    backgroundColor: 'rgba(255, 0, 0, 0.5)',
                    data: [] // Aquí almacenaremos los totales de precio_compra
                }, {
                    label: 'Total',
                    backgroundColor: 'rgba(0, 0, 255, 0.5)',
                    data: [] // Aquí almacenaremos los totales de total
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: true // Barras apiladas
                    },
                    x: {
                        stacked: true // Barras apiladas
                    }
                }
            }
        });

        // Capturar el evento de envío del formulario
        document.getElementById('dateForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevenir el envío del formulario
            updateChart(); // Actualizar el gráfico con las fechas seleccionadas
        });

        let urlBase = "?c=compra&a=ListarAjaxx";

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

        const mostrar = (compras) => {
            console.log("Datos recibidos del servidor:", compras);

            // Limpiar las etiquetas anteriores
            myChart.data.labels = [];

            // Limpiar los datos anteriores
            myChart.data.datasets.forEach(dataset => {
                dataset.data = [];
            });

            compras.forEach(compra => {
                console.log("Compra procesada:", compra);

                // Agregar el id_producto al eje X (puedes personalizar esto según tu necesidad)
                myChart.data.labels.push(compra.id_producto);

                // Agregar datos a los datasets
                myChart.data.datasets[0].data.push(parseFloat(compra.precio_compra));
                myChart.data.datasets[1].data.push(parseFloat(compra.total));
            });

            // Agregar un mensaje de registro
            console.log("Datos después de mostrar:", myChart.data);

            myChart.update();
            console.log("Datos después de actualizar:", myChart.data);
        }
    </script>
</body>

</html>


    
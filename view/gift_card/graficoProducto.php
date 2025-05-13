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

    <canvas id="myChart" style="position: relative; height: 40vh; width: 80vw;"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var ctx = document.getElementById('myChart')
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [], // Nombres de productos en el eje X
                datasets: [{
                    label: 'Stock',
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    data: [] // Datos de stock
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        })

        // Realizar la solicitud sin filtrar por fechas
        let url = "?c=producto&a=ListarAJAXproducto";
        fetch(url)
            .then(response => response.json())
            .then(datos => mostrar(datos))
            .catch(error => console.log(error))

        const mostrar = (productos) => {
            // Limpiar las etiquetas y datos anteriores
            myChart.data.labels = [];
            myChart.data.datasets[0].data = [];

            productos.forEach(producto => {
                // Agregar datos al array de etiquetas y al array de datos
                myChart.data.labels.push(producto.producto);
                myChart.data.datasets[0].data.push(producto.stock);
            });

            // Agregar un mensaje de registro
            console.log("Datos después de mostrar:", myChart.data);

            myChart.update();
            console.log("Datos después de actualizar:", myChart.data);
        }
    </script>
</body>

</html>

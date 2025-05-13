<h1>Gráfica creada </h1>
    <a href="https://geocad.com.py">By ventas</a>


<!DOCTYPE html>
<html lang="en">
<h3 id="filtrar" align="center">Buscar por fechas <i class="fas fa-angle-right"></i><i class="fas fa-angle-left" style="display: none"></i></h3>
<div class="col-sm-12">
        <div align="center">
            <form method="post">
                <input type="hidden" name="c" value="gift_card">
                
                 <div class="form-group col-md-2">
                </div>
                <div class="form-group col-md-2">
                </div>
                <div class="form-group col-md-2">
                    <label>Desde</label>
                    <input type="date" name="desde" value="<?php echo (isset($_GET['desde']))? $_GET['desde']:''; ?>" class="form-control">
                </div>
                <div class="form-group col-md-2">
                    <label>Hasta</label>
                    <input type="date" name="hasta" value="<?php echo (isset($_GET['hasta']))? $_GET['hasta']:''; ?>" class="form-control">
                </div>
                
                <div class="form-group col-md-2">
                    <label></label>
                    <input type="submit" value="Filtrar" class="form-control btn btn-success">
                </div>
                
            </form>
        </div>
    </div>
<head>
<?php include("view/crud-modal.php"); ?>
<?php include("view/venta/mes-modal.php"); ?>
<?php include("view/venta/dia-modal.php"); ?>
<?php include("view/venta/detalles-modal.php"); ?>
</head>

<body>
    
    <canvas id="myChart" style="position: relative; height: 50vh; width: 80vw;"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        
        var ctx = document.getElementById('myChart').getContext("2d")
        var myChart = new Chart(ctx, {
            type:'bar',
            data:{
                datasets: [{
                    label: 'Relacionamientos',
                    backgroundColor: ['#6bf1ab','#63d69f', '#438c6c', '#509c7f', '#1f794e', '#34444c', '#90CAF9', '#64B5F6', '#42A5F5', '#2196F3', '#0D47A1'],
                    borderColor: ['black'],
                    borderColor: 'orange',           
                    pointBorderColor: 'orange',
                    pointBackgroundColor: 'rgba(255,150,0,0.5)',
                    pointRadius: 5,
                    pointHoverRadius: 10,
                    pointHitRadius: 30,
                    pointBorderWidth: 2,
                    pointStyle: 'rectRounded',
                    borderWidth:4
                }]
            },
            options:{
                scales:{
                    y:{
                        beginAtZero:true
                    }
                }
            }
        })
        
        
                let url = "?c=venta&a=ListarAjaxventa"

                fetch(url)
                    .then( response => response.json() )
                    .then( datos => mostrar(datos) )
                     .catch( error => console.log(error) )


                const mostrar = (ventas) =>{
                    ventas.forEach(element => {
                        myChart.data['labels'].push(element.total)
                        myChart.data['labels'].push(element.fecha_venta)
                         myChart.data['labels'].push(element.producto)
                        myChart.data['datasets'][0].data.push(element.total)
                        myChart.data['datasets'][0].data.push(element.fecha_venta)
                        myChart.data['datasets'][0].data.push(element.producto)
                        myChart.update()
                    });


                    console.log(myChart.data)
                }    


    </script>

      
</body>
<body>
    
</body>


</html>
<h1>Gráfica creada</h1>
    <a href="https://geocad.com.py">By Geocad Tasaciones de ventas</a>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>


</dir>

<?php include("view/crud-modal.php"); ?>
<?php include("view/venta/mes-modal.php"); ?>
<?php include("view/venta/dia-modal.php"); ?>
<?php include("view/venta/detalles-modal.php"); ?>
<script type="text/javascript">
    $( "#filtrar" ).click(function() {
      $("#filtro").toggle("slow");
      $("i").toggle();
    });
</script>

    
             

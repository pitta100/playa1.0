<h1 class="page-header">Inventario <?php
if($_GET['fecha'] == ''){
        $fecha = date('Y-m-d');
    }else{
        $fecha = $_GET['fecha'];
    }
?> &nbsp;
<?php if($_SESSION['nivel']<=1) ?>
<a class="btn btn-success" href="?c=inventario&a=guardar" class="btn btn-success"> <i class="fa-solid fa-not-equal"></i>  Nuevo Inventario </a> </h1>

<a  class="btn btn-success pull-right" align= "center" href="?c=cierre_inventario&a=Cierreinventario"> Volver al Informe de Inventario </a>
<a  class="btn btn-warning pull-right " align= "center" href="#finalizar" data-toggle="modal" 
data-target="#finalizar" data-c="inventario" style="margin-right: 1rem">Finalizar </a>

<br><br><br>
<!--<h3 id="filtrar" align="center">Filtrar por Fecha <i class="fas fa-angle-down"></i><i class="fas fa-angle-up" style="display: none"></i></h3>
<div class="container">
  <div class="row">
    <div class="col-sm-4">
    </div>
    <div class="col-sm-4">
        <div align="center" id="filtro" style="display: none">
            <form method="post">
                <div class="form-group">
                    <label>Fecha</label>
                    <input type="date" name="desde" value="<?php //echo (isset($_GET['desde']))? $_GET['desde']:"";?>" class="form-control" required>
                </div>
                
                <input type="submit" name="filtro" value="Filtrar" class="btn btn-success"> 
            </form>
        </div>
    </div>
    <div class="col-sm-4">
    </div>
  </div>
</div>-->
<p> </p>
<table class="table table-striped table-bordered display responsive datatable" width="100%">

    <thead>
        <tr style="background-color: #000; color:#fff">
            <th> <i class="fa-solid fa-user"></i> Usuario</th> 
            <th> <i class="fa-solid fa-code"></i>  Código</th>
            <th> <i class="fa-brands fa-buromobelexperte"></i> Producto</th>    
            <th> <i class="fa-solid fa-basket-shopping"></i> Stock Actual</th>    
            <th> <i class="fa-solid fa-basket-shopping"></i> Stock Real</th>
            <th> <i class="fa-solid fa-people-robbery"></i> Faltante</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    //Lista la fecha del dia
    if($_GET['fecha'] == ''){
        $fecha = date('Y-m-d');
    }else{
        $fecha = $_GET['fecha'];
    }
   
  // session_start();
$c = $this->cierre_inventario->ConsultarCierre($_SESSION['user_id'],$fecha);
$lista = $this->model->Listar($fecha);
    foreach($lista as $r):  ?>
        <tr >
            <td><?php echo $r->usuario; ?></td>
            <td><?php echo $r->codigo; ?></td>
            <td><?php echo $r->producto.' ('.$r->precio_minorista; ?>)</td>
            <td id="<?php echo $r->id_i; ?>"><?php echo $r->stock_actual; ?></td>
            <td align="center" width="10%">
                <!--Tiene un campo que se puede tipear. Stock_real <!-->
<?php
                if($c){ ?>
                     <div class="form-group">
                    <input  id_real="<?php echo $r->id_i; ?>" stock_real="<?php echo $r->stock_real; ?>"  name="stock_real" class="stock_real form-control" type="number" value="<?php echo $r->stock_real; ?>" id="stock_real">
                </div>
                <?php }else { ?>
                    <div class="form-group"> 
                    <input  id_real="<?php echo $r->id_i; ?>" stock_real="<?php echo $r->stock_real; ?>"  name="stock_real" class="stock_real form-control" type="number" value="<?php echo $r->stock_real; ?>" id="stock_real" >
                </div>
                <?php } ?>
               
            </td>
            <td id="faltante<?php echo $r->id_i; ?>"><?php echo $r->faltante; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>

</dir>
</div>
</div> 
<?php include("view/crud-modal.php"); ?>
<?php include("view/inventario/finalizar-inventario.php"); ?>



<script type="text/javascript">
    
   /*  $('.stock_real').on('change',function(){
        
        var stock_real = $(this).val();
        var id = parseInt($(this).attr("id"));
        console.log(id);
        //alert(id);
        //La accion es StockReal
        var url = "?c=inventario&a=StockReal&id="+id+"&stock_real="+stock_real;
            $.ajax({

                url: url,
                cache: false,
                contentType: false,
                processData: false,
                success:function(respuesta){
                    //$("#stock_real").html(respuesta);
                    //location.reload(true);
                    //alert(respuesta);
                }

            })
            var real = parseInt($(this).val());
           // var actual = parseInt($(this).val());
            //var total = parseInt($(this).val());
            var url = "?c=inventario&a=obtenerjson&id="+id;
                $.ajax({

                url: url,
                method : "POST",
                data: id,
                cache: false,
                contentType: false,
                processData: false,
                success:function(respuesta){
                    var inventario = JSON.parse(respuesta);
                    var actual = inventario.stock_actual;
                    var total =  actual - real;
                    console.log(actual);

                    $("#faltante").html((total).toLocaleString('de-DE'));
                    console.log(total);
                }

            })
    });

   $('#stock_real').on('keyup',function(){
        var id = $(this).val();
        var real = parseInt($(this).val());
        //alert(real);
        console.log(total);
        var url = "?c=inventario&a=obtener&id="+id;
            $.ajax({

                url: url,
                method : "POST",
                data: id,
                cache: false,
                contentType: false,
                processData: false,
                success:function(respuesta){
                    var actual = $("#stock_actual").val();
                    var total = actual - real;
                    $("#faltante").html((total).toLocaleString('de-DE'));
                }

            })
    });*/


/*            $('#stock_real').on('keyup',function(){
            var real = parseInt($(this).val());
            alert(real);
            var actual = $("#stock_actual").val();
            var total = actual - real;
            $("#faltante").html((total).toLocaleString('de-DE'));
    });

    $("#link").val(window.location.href);*/
</script>

<script type="text/javascript">

    $('.stock_real').on('keyup',function(){

        var id_real = $(this).attr("id_real");
        var stock_real = $(this).val();
        var stock_actual = parseInt($("#"+id_real).text());


        $("#faltante"+id_real).html((stock_actual - stock_real));

        var url = "?c=inventario&a=StockReal&id="+id_real+"&stock_real="+stock_real;

        $.ajax({

            url: url,
            cache: false,
            contentType: false,
            processData: false,
            success:function(respuesta){
            }

        })

    });

      $( "#filtrar" ).click(function() {
      $("#filtro").toggle("slow");
      $("i").toggle();
    });
</script>


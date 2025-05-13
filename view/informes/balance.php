<?php

$fecha = (isset($_GET["fecha"]))? $_GET["fecha"]."-10":date("Y-m-d");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
?>

<h1 align="center">Genere su balance
<?php echo "<a href='?c=ingreso&a=balancemes&fecha=$fecha' class='btn btn-success'>Imprimir</a>";?>
</h1>
<hr />
<table width="20%" align="center">
    <tr>
        <td>
        <form action="?c=ingreso&a=balance">
            <table>  
            <tr>
                <td>
                <input type="hidden" name="c" value="ingreso">
                <input type="hidden" name="a" value="balance">
                <input type="month" name="fecha" value="<?php echo date("Y-m", strtotime($fecha)) ?>" class="form-control">
                </td>
                <td>
                <input class="btn btn-success" type="submit" name="buscar" value="Buscar">
                </td>
            </tr> 
            </table>  
        </form>
        </td>
    </tr>
</table>
<hr />
<div class="col-sm-6" align="center" style="border-right: 1px solid #d6d6d6">
<div class="content">

<h2 class="page-header">Lista de ingresos </h2>
<br><br><br>
<table class="table table-striped table-bordered display responsive nowrap tablas" width="100%">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th>En concepto de</th>
            <th> <i class="fa-solid fa-money-check-dollar"></i> Monto (Gs)</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $ingreso=0;
    $mes = (isset($_GET['m']))? $_GET['m']:0; 
     foreach($this->model->AgrupadoMes($fecha) as $r): ?>
        <tr class="click">
            <?php $ingreso = ($ingreso + $r->monto); ?>
            <td><?php echo $r->categoria; ?></td>
            <td><?php echo number_format($r->monto,0,",","."); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
        <tr style="font-size: 16px">
            <td align="right"><b>Total : </b></td>
            <td><b><?php echo number_format($ingreso,0,",","."); ?></b></td>
        </tr>
    
</table>
</div>
</div>

<div class="col-sm-6" align="center">
<div class="content">

<h2 class="page-header">Lista de egresos </h2>
<br><br><br>
<table class="table table-striped table-bordered display responsive nowrap tablas" width="100%">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th>Concepto</th>
            <th> <i class="fa-solid fa-money-check-dollar"></i> Monto (Gs)</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $egreso=0;
    $mes = (isset($_GET['m']))? $_GET['m']:0; 
    foreach($this->egreso->AgrupadoMes($fecha) as $r): ?>
        <tr class="click">
            <?php $egreso = ($egreso + $r->monto); ?>
            <td><?php echo $r->categoria; ?></td>
            <td><?php echo number_format($r->monto,0,",","."); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>   
        <tr style="font-size: 16px">
            <td align="right"><b>Total : </b></td>
            <td><b><?php echo number_format($egreso,0,",","."); ?></b></td>
        </tr>
</table>
</div>
</div>

<div class="col-sm-12">
<?php $total = $ingreso - $egreso;?> 
<h1 align="center"> Balance General: <?php echo number_format($total,0,",","."); ?> (Gs)</h1>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>

</dir>
</div>
<?php include("view/crud-modal.php"); ?>



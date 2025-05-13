 
<?php $fecha = date("Y-m-d"); ?>
<h1 class="page-header"> GeoCad detalles de ajuste</h1> 
<div align="center" width="30%"> 
    
</div>

<div class="table-responsive">

<table class="table table-striped table-bordered display responsive nowrap" width="100%" id="tabla1">

    <thead>
        <tr style="background-color: #7dcd5d; color:#fff">
            <th> <i class="fa-brands fa-buromobelexperte"></i> Producto</th>
            <th> <i class="fa-solid fa-arrows-up-down"></i> Costo</th>
            <th> <i class="fa-solid fa-box-archive"></i> Cant</th>
            <th> <i class="fa-solid fa-magnifying-glass-chart"></i> Motivo</th>
            <th> <i class="fa-solid fa-calculator"></i>  Total (Gs.)</th>
        </tr>
    </thead>
    <tbody>
    <?php
     $subtotal=0;
     $sumatotal = 0;
     $id_venta = $_GET['id'];
     foreach($this->model->Listar($id_venta) as $r): 
        $total = $r->precio_venta*$r->cantidad;
     ?>
        <tr>
            
            <td><?php echo $r->producto; ?></td>
            <td><?php echo number_format($r->precio_venta, 0, "," , "."); ?></td>
            <td><?php echo $r->cantidad; ?></td>
            <td><?php echo $r->descuento; ?></td>
            <td><?php echo number_format($total, 0, "," , "."); ?></td>
        </tr>
    <?php $sumatotal += $total ;endforeach; ?>
        
        
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total Gs: <div id="total" style="font-size: 20px"><?php echo number_format($sumatotal,0,",",".") ?></div></td>
        </tr>
    </tbody>
</table> 
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>

</dir>
</div> 
</div>
</div>

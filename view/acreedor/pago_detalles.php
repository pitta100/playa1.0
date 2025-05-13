 
<?php $fecha = date("Y-m-d"); ?>
<h1 class="page-header">detalles de pagos</h1> 
<div align="center" width="30%"> 
    
</div>

<div class="table-responsive">

<table class="table table-striped table-bordered display responsive nowrap">

    <thead>
        <tr style="background-color: #7dcd5d; color:#fff">
            <th> <i class="fa-regular fa-calendar-days"></i> Fecha</th>
            <th> <i class="fa-solid fa-receipt"></i>  Comprobante</th>
            <th> <i class="fa-solid fa-money-check-dollar"></i> Monto</th>
        </tr>
    </thead>
    <tbody>
    <?php
     $sumatotal = 0;
     $id_acreedor = $_GET['acreedor'];
     foreach($this->model->ListarAcreedor($id_acreedor) as $r):  ?>
        <tr>
            
            <td><?php echo date("d/m/Y", strtotime($r->fecha)); ?></td>
            <td><?php echo $r->comprobante; ?></td>
            <td><?php echo number_format($r->monto, 0, "," , "."); ?></td>
        </tr>
    <?php $sumatotal += $r->monto ;endforeach; ?>
        
        
        <tr>
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

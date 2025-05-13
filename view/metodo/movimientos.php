<h1 class="page-header">Movimientos</h1>
<br><br><br>

<p> </p>
<table class="table table-striped display responsive nowrap datatable" width="100%">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th>N°</th>
        	<th>Fecha</th>
            <th>Categoría</th>
            <th>Concepto</th>
            <th>N° de comprobante</th>
            <th>Ingreso</th>
            <th>Egreso</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $sumaTotal = 0; 
    $c=1;
    foreach($this->model->ListarMovimientos($_GET['metodo']) as $r):
    if(strlen($r->concepto)>=50){$concepto=substr($r->concepto, 0, 50)."...";}else{$concepto=$r->concepto;}
    if($r->monto>0){
        $ingreso = number_format($r->monto,0,".",",");
        $egreso = "";
    }else{
        $ingreso = "";
        $egreso = number_format(($r->monto*-1),0,".",",");
    } ?>
        <tr class="click" <?php if($r->anulado){echo "style='color:red'";}elseif($r->descuento>0){echo "style='color:#F39C12'";} ?>>
            <td><?php echo $c++; ?></td>
        	<td><?php echo date("d/m/Y H:i", strtotime($r->fecha)); ?></td>
            <td><?php echo $r->categoria; ?></td>
            <td title="<?php echo $r->concepto; ?>"><?php echo $concepto; ?></td>
            <td><?php echo $r->comprobante; ?></td>
            <td><?php echo $ingreso; ?></td>
            <td><?php echo $egreso; ?></td>
        </tr>
    <?php $sumaTotal += $r->monto; endforeach; ?>
    </tbody>
    <tfoot>
        <tr style="background-color: black; color:#fff">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total:</th>
            <th><?php echo number_format($sumaTotal,0,".",","); ?></th> 
        </tr>
    </tfoot>
</table>
</div>
</div>
</div>

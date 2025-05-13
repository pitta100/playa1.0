
<ol class="breadcrumb">
  <li><a class="btn btn-success pull-right" href="?c=cliente"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Agregar Nuevo Cliente.</a></li>
</ol>

<h1 class="page-header">
    <?php echo $ingreso->id != null ? $ingreso->fecha : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=ingreso">GeoCad sector ingreso</a></li>
  <li class="active"><?php echo $ingreso->id != null ? $ingreso->fecha : 'Nuevo Registro'; ?></li>
</ol>

<form id="crud-frm" method="post" action="?c=ingreso&a=guardar" enctype="multipart/form-data">
    <input type="hidden" name="c" value="ingreso" id="c"/>
    <input type="hidden" name="id" value="<?php echo $ingreso->id; ?>" id="id" />
    <div class="form-group">
        <label> <i class="fa-regular fa-calendar-days"></i> Fecha</label>
        <input type="datetime-local" name="fecha" value="<?php echo ($ingreso->fecha) ? date("Y-m-d", strtotime($ingreso->fecha)):date("Y-m-d") ?>T<?php echo date("H:i"); ?>" class="form-control" placeholder="Fecha" required>
    </div>

    <div class="form-group">
        <label> <i class="fa-solid fa-hands-holding-circle"></i> Cliente</label>
        <select name="id_cliente" id="id_cliente" class="form-control" data-show-subtext="true" data-live-search="true" data-style="form-control"
                    title="-- Seleccione el Cliente --" style="width:100%; display:0">
            <option value="0">Sin seleccionar</option>
            <?php foreach($this->cliente->Listar() as $clie): ?> 
            <option value="<?php echo $clie->id; ?>"><?php echo $clie->nombre." ( ".$clie->ruc." )"; ?> </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    
    <div class="form-group">
        <label> <i class="fa-solid fa-sitemap"></i> Codigo</label>
        <input type="text" name="categoria" value="<?php echo $ingreso->categoria; ?>" class="form-control" placeholder="Ingrese la categoria" required>
    </div>

    <div class="form-group">
        <label> <i class="fa-solid fa-magnifying-glass-chart"></i> Tecnico</label>
        <input type="text" name="concepto" value="<?php echo $ingreso->concepto; ?>" class="form-control" placeholder="Ingrese su concepto" required>
    </div>
    
    <div class="form-group">
        <label> <i class="fa-solid fa-receipt"></i> Comprobante</label>
        <input type="text" name="comprobante" value="<?php echo $ingreso->comprobante; ?>" class="form-control" placeholder="Ingrese su comprobante" required>
    </div>
    
    <div class="form-group">
        <label> <i class="fa-solid fa-money-check-dollar"></i>  Monto</label>
        <input type="number" name="monto" value="<?php echo $ingreso->monto; ?>" class="form-control" placeholder="Ingrese el monto" min="0" required>
    </div>

    <div class="form-group">
        <label> <i class="fa-brands fa-cc-paypal"></i> Forma de pago</label>
        <select name="forma_pago" class="form-control">
            <option value="Efectivo" <?php echo ($ingreso->forma_pago == "Efectivo")? "selected":""; ?>>Efectivo</option>
            <option value="Cheque" <?php echo ($ingreso->forma_pago == "Cheque")? "selected":""; ?>>Cheque</option>
            <option value="Transferencia" <?php echo ($ingreso->forma_pago == "Transferencia")? "selected":""; ?>>Transferencia</option>
        </select>
    </div>
    
    <input type="hidden" name="sucursal" value="0">
    
    <hr />
    
    <div class="text-right">
        <button class="btn btn-success"> <i class="fa-solid fa-cloud-arrow-up"></i> Guardar</button>
    </div>
</form>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>

</dir>
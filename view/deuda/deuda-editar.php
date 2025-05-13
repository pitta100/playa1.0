<h1 class="page-header">
    <?php echo $deuda->id != null ? $deuda->fecha : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=deuda">GeoCad sector deuda 2 </a></li>
  <li class="active"><?php echo $deuda->id != null ? $deuda->fecha : 'Nuevo Registro'; ?></li>
</ol>


<form id="crud-frm" method="post" action="?c=deuda&a=guardar" enctype="multipart/form-data">
    <input type="hidden" name="c" value="deuda" id="c"/>
    <input type="hidden" name="id" value="<?php echo $deuda->id; ?>" id="id" />
    <input type="hidden" name="id_venta" value="0">
    <div class="form-group">
        <label> <i class="fa-regular fa-calendar-days"></i>  Fecha</label>
        <input type="date" name="fecha" value="<?php echo ($deuda->fecha) ? date("Y-m-d", strtotime($deuda->fecha)):date("Y-m-d"); ?>" class="form-control" placeholder="Fecha" required>
    </div>

    <div class="form-group">
        <label> <i class="fa-solid fa-hands-holding-circle"></i> Cliente</label>
        <select name="id_cliente" id="id_cliente" class="form-control" data-show-subtext="true" data-live-search="true">
            <option value="2">Cliente casual (XXX)</option>
            <?php foreach($this->cliente->Listar() as $clie): ?> 
            <option value="<?php echo $clie->id; ?>" <?php echo ($clie->id == $deuda->id_cliente)? "selected":""; ?>><?php echo $clie->nombre." ( ".$clie->ruc." )"; ?> </option>
            <?php endforeach; ?>
        </select>
    </div> 
    
    <div class="form-group">
        <label> <i class="fa-regular fa-pen-to-square"></i> Concepto</label>
        <input type="text" name="concepto" value="<?php echo $deuda->concepto; ?>" class="form-control" placeholder="Ingrese su concepto" required>
    </div>
    
    <div class="form-group">
        <label> <i class="fa-solid fa-money-check-dollar"></i> Monto</label>
        <input type="number" id="monto" name="monto" value="<?php echo $deuda->monto; ?>" class="form-control" placeholder="Ingrese el monto" min="0" required>
    </div>
        
    <div class="form-group">
        <label>Saldo</label>
        <input type="number" id="saldo" name="saldo" value="<?php echo $deuda->saldo; ?>" class="form-control" placeholder="Ingrese el saldo" min="0" required>
    </div>
    <div class="form-group">
        <label>Cantidad de Cuotas</label>
        <input type="number" id="cuotas" name="cuotas" value="<?php echo $deuda->cuotas; ?>" class="form-control" placeholder="Ingrese el saldo" min="0" required>
    </div>
    <div class="form-group">
        <label>Fecha de vencimiento</label>
        <input type="datetime-local" id="venci" name="vencimiento" class="form-control" value="<?php echo date("Y-m-d") ?>T<?php echo date("H:i") ?>">
    </div>

    <div class="text-right">
        <button class="btn btn-success"> <i class="fa-solid fa-cloud-arrow-up"></i> Guardar</button>
    </div>

</form>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>

</dir>

<script>
    $( "#monto" ).keyup(function() {
        $( "#saldo" ).val($( "#monto" ).val());
    });  
</script>
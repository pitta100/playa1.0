<h1 class="page-header">
    <?php echo $cliente->id != null ? $cliente->nombre : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=cliente">GeoCad sector Cliente !</a></li>
  <li class="active"><?php echo $cliente->id != null ? $cliente->nombre : 'Nuevo Registro'; ?></li>
</ol>

<form id="crud-frm" method="post" action="?c=cliente&a=guardar" enctype="multipart/form-data">
    <input type="hidden" name="c" value="cliente" id="c"/>
    <input type="hidden" name="url" value="<?php echo $_SERVER['PHP_SELF'] ?>"/>
    <input type="hidden" name="nick" value=" "/>
    <input type="hidden" name="pass" value=" "/>
    <input type="hidden" name="sucursal" value=" "/>
    <input type="hidden" name="id" value="<?php echo $cliente->id; ?>" id="id" />

    <div class="form-group">
        <label> <i class="fa-solid fa-language"></i> CI/RUC</label>
        <input type="text" name="ruc" value="<?php echo $cliente->ruc; ?>" class="form-control" placeholder="Ingrese ruc/ci" required>
    </div>

    <div class="form-group">
        <label> <i class="fa-solid fa-user"></i>Nombre</label>
        <input type="text" name="nombre" value="<?php echo $cliente->nombre; ?>" class="form-control" placeholder="Ingrese nombre" required>
    </div>

    <div class="form-group">
        <label><i class="fa-solid fa-phone-volume"></i> Teléfono</label>
        <input type="text" name="telefono" value="<?php echo $cliente->telefono; ?>" class="form-control" placeholder="Ingrese telefono">
    </div>
     <!--<div class="form-group">
        <label><i class="fa-solid fa-phone-volume"></i> Teléfono</label>
        <input type="text" name="telefono" value="<?php echo $cliente->telefono; ?>" class="form-control" placeholder="Ingrese telefono">
    </div>-->
    <div class="form-group">
        <label>Correo</label>
        <input type="mail" name="correo" value="<?php echo $cliente->correo; ?>" class="form-control" placeholder="Ingrese correo" required>
    </div>

    <div class="form-group">
        <label> <i class="fa-solid fa-map-location-dot"></i>  Dirección</label>
        <input type="text" name="direccion" value="<?php echo $cliente->direccion; ?>" class="form-control" placeholder="Ingrese dirección">
    </div>
    <div class="form-group">
        <label><i class="fa-solid fa-briefcase"></i> Dirección Laboral</label>
        <input type="text" name="adressWork" value="<?php echo $cliente->adressWork ?? ''; ?>" class="form-control" placeholder="Dirección del trabajo">
    </div>
    <div class="form-group">
        <label><i class="fa-solid fa-map-pin"></i> Lugar de residencia (Ubicación Google Maps)</label>
        <input type="text" name="residencia_url" value="<?php echo $cliente->residencia_url ?? ''; ?>" class="form-control" placeholder="Pegá el link de Google Maps aquí">
         <small class="form-text text-muted">copia el enlace de google maps compartir.</small>
    </div>

    <div class="form-group">
        <label><i class="fa-solid fa-phone-office"></i> Teléfono Laboral</label>
        <input type="text" name="phoneWork" value="<?php echo $cliente->phoneWork ?? ''; ?>" class="form-control" placeholder="Teléfono del trabajo">
    </div>
     <!-- Comprobante de Ingreso -->
    <div class="form-group">
        <label>Comprobante de Ingreso (PDF)</label>
        <input type="file" name="comprobanteIngreso" class="form-control" accept="application/pdf">
        <?php if (!empty($cliente->comprobanteIngreso)) : ?>
            <a href="assets/documentos/clientes/<?php echo $cliente->comprobanteIngreso; ?>" target="_blank">📄 Ver archivo actual</a>
             <input type="hidden" name="comprobanteIngreso_actual" value="<?php echo $cliente->comprobanteIngreso; ?>">
        <?php endif; ?>
    </div>

    <!-- Balance General -->
    <div class="form-group">
        <label>Balance General (PDF)</label>
        <input type="file" name="cedulaTributaria" class="form-control" accept="application/pdf">
        <?php if (!empty($cliente->cedulaTributaria)) : ?>
            <a href="assets/documentos/clientes/<?php echo $cliente->cedulaTributaria; ?>" target="_blank">📄 Ver archivo actual</a>
             <input type="hidden" name="cedulaTributaria_actual" value="<?php echo $cliente->cedulaTributaria; ?>">
        <?php endif; ?>
    </div>

    <!-- Constancia de RUC -->
    <div class="form-group">
        <label>Constancia de Ruc (PDF)</label>
        <input type="file" name="facturasLegalesEmitidas" class="form-control" accept="application/pdf">
        <?php if (!empty($cliente->facturasLegalesEmitidas)) : ?>
            <a href="assets/documentos/clientes/<?php echo $cliente->facturasLegalesEmitidas; ?>" target="_blank">📄 Ver archivo actual</a>
             <input type="hidden" name="facturasLegalesEmitidas_actual" value="<?php echo $cliente->facturasLegalesEmitidas; ?>">
        <?php endif; ?>
    </div>

    <!-- Constitución -->
    <div class="form-group">
        <label>Constitución (PDF)</label>
        <input type="file" name="cedulaIdentidad" class="form-control" accept="application/pdf">
        <?php if (!empty($cliente->cedulaIdentidad)) : ?>
            <a href="assets/documentos/clientes/<?php echo $cliente->cedulaIdentidad; ?>" target="_blank">📄 Ver archivo actual</a>
             <input type="hidden" name="cedulaIdentidad_actual" value="<?php echo $cliente->cedulaIdentidad; ?>">
        <?php endif; ?>
    </div>

    <!-- Estructura Jurídica -->
    <div class="form-group">
        <label>Estructura Jurídica (PDF)</label>
        <input type="file" name="estructuraJuridica" class="form-control" accept="application/pdf">
        <?php if (!empty($cliente->estructuraJuridica)) : ?>
            <a href="assets/documentos/clientes/<?php echo $cliente->estructuraJuridica; ?>" target="_blank">📄 Ver archivo actual</a>
             <input type="hidden" name="estructuraJuridica_actual" value="<?php echo $cliente->estructuraJuridica; ?>">
        <?php endif; ?>
    </div>

    <!-- Beneficiario Final -->
    <div class="form-group">
        <label>Beneficiario Final (PDF)</label>
        <input type="file" name="beneficiarioFinal" class="form-control" accept="application/pdf">
        <?php if (!empty($cliente->beneficiarioFinal)) : ?>
            <a href="assets/documentos/clientes/<?php echo $cliente->beneficiarioFinal; ?>" target="_blank">📄 Ver archivo actual</a>
              <input type="hidden" name="beneficiarioFinal_actual" value="<?php echo $cliente->beneficiarioFinal; ?>">
        <?php endif; ?>
    </div>

    <!-- Otros Documentos -->
    <div class="form-group">
        <label>Otros Documentos (PDF)</label>
        <input type="file" name="varios" class="form-control" accept="application/pdf">
        <?php if (!empty($cliente->varios)) : ?>
            <a href="assets/documentos/clientes/<?php echo $cliente->varios; ?>" target="_blank">📄 Ver archivo actual</a>
            <input type="hidden" name="varios_actual" value="<?php echo $cliente->varios; ?>">
        <?php endif; ?>
    </div>


    <div class="form-group">
        <label>¿Es Mayorista?</label>
        <select name="mayorista" class="form-control">
            <option value="NO" <?php if($cliente->mayorista  == "NO"){echo "selected";} ?>>NO</option>
            <option value="SI" <?php if($cliente->mayorista  == "SI"){echo "selected";} ?>>SI</option>
        </select>
    </div>
     <div class="col-sm-3">
       <input type="checkbox" id="cl" value="1" <?= isset($cliente->cliente) && $cliente->cliente == 1 ? 'checked' : '' ?>> Cliente
        <label >Cliente</label>
    </div>
     <div class="col-sm-3">
       <input type="checkbox" id="p" value="1" <?= isset($cliente->proveedor) && $cliente->proveedor == 1 ? 'checked' : '' ?>> Proveedor
        <label>Proveedor</label>
    </div>

    <div class="form-group" style='display:none'>
        <label>Foto</label>
        <input type="file" name="foto_perfil" class="form-control">
    </div>

    <hr />

    <div class="text-right">
        <button class="btn btn-success"> <i class="fa-solid fa-cloud-arrow-up"></i> Guardar</button>
    </div>
</form>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina -P & Q AUTOMOTORES SA.<?php echo date("Y") ?></a></li>
    </ul>

</dir>

<script>
 $('#cl').on('click',function(){
        var cl = $(this).val();
        if(cl==0){
            $("#cl").val(1);
           
        }else{
            $("#cl").val(0);
        }
    });
     $('#p').on('click',function(){
        var p = $(this).val();
        if(p==0){
            $("#p").val(1);
           
        }else{
            $("#p").val(0);
        }
    });

    $('#crud-frm1').on('submit', function (event) {
        var parametros = $(this).serialize();
        var c = $("#c").val();
        var id = $("#id").val();

        var url = "?c="+c+"&a=guardar&id="+id;
            $.ajax({
                type: "POST",
                url: url,
                data: parametros,
                cache: false,
                processData: false,
                success: function(respuesta){
                    load(c);
                    $("#crudModal").modal('hide');
                }
            });
        event.preventDefault();
    })
</script>
<h1 class="page-header">
    <?php echo $producto->id != null ? $producto->producto : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=producto">GeoCad sector Producto</a></li>
  <li class="active"><?php echo $producto->id != null ? $producto->producto : 'Nuevo Registro'; ?></li>
</ol>

<form id="crud-frm" method="post" action="?c=producto&a=guardar" enctype="multipart/form-data">
    <input type="hidden" name="c" value="producto" id="c"/>
    <input type="hidden" name="id" value="<?php echo $producto->id; ?>" id="id" />
    <input type="hidden" name="stock" value="<?php echo $producto->stock; ?>" id="stock" />    
    <div class="form-group">
        <label> <i class="fa-solid fa-code"></i> Código <a href='#' class='btn btn-default' id="autocodigo">Auto código</a></label>
        <input type="text" name="codigo" id="codigo" value="<?php echo $producto->codigo; ?>" class="form-control" placeholder="Ingrese el codigo" required>
    </div>

    <div class="form-group">
        <label> <i class="fa-solid fa-sitemap"></i> Categoría</label>
        <select name="id_categoria" class="form-control">
            <?php foreach($this->categoria->Listar() as $r): ?>
                <option value="<?php echo $r->id; ?>" <?php echo ($r->id == $producto->id_categoria)? "selected":""; ?>><?php echo $r->categoria; ?></option>
                
            <?php endforeach; ?>
        </select>
    </div>
    <?php session_start(); if($_SESSION['nivel']<1){ ?>
    <div class="form-group">
        <label> <i class="fa-solid fa-code-branch"></i> Sucursal</label>
        <select name="sucursal" class="form-control">
            <?php foreach($this->sucursal->Listar() as $r): ?>
                <option value="<?php echo $r->id; ?>" ><?php echo $r->sucursal; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php }else{ ?>
    <input type="hidden" name="sucursal" value="<?php echo $_SESSION['sucursal']; ?>"
    <?php } ?>
    <div class="form-group">
        <label> <i class="fa-brands fa-buromobelexperte"></i> Producto</label>
        <input type="text" name="producto" value="<?php echo $producto->producto; ?>" class="form-control" placeholder="Ingrese el producto" list="prod" required>
        <datalist id="prod">
             <?php foreach($this->model->Listar() as $prod): ?> 
                <option data-subtext="<?php echo $prod->codigo; ?>" value="<?php echo $prod->id; ?>" <?php echo ($prod->stock<1)? 'disabled':''; ?>><?php echo $prod->producto.' ( '.$prod->stock.' ) - '.number_format($prod->precio_minorista,0,".","."); ?> </option>
                <?php endforeach; ?>
        </datalist>
    </div>
       <div class="form-group" style="display:none;">
        <label>Marca</label>
        <select name="marca" class="form-control">
            <?php foreach($this->marca->Listar() as $r): ?>
                <option value="<?php echo $r->id; ?>" <?php echo ($r->id == $producto->marca)? "selected":""; ?>><?php echo $r->marca; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
   <div class="form-group">
    <label>Marca Vehiculo</label>
        <input type="text" name="marcaVehiculo" class="form-control" 
            placeholder="Ej: Toyota, Chevrolet, Ford" 
            value="<?php echo isset($producto->marcaVehiculo) ? $producto->marcaVehiculo : ''; ?>">
    </div>

    <div class="form-group">
        <label>Modelo</label>
        <input type="text" name="modelo" class="form-control" 
            placeholder="Ej: Corolla, Hilux, Cruze" 
            value="<?php echo isset($producto->modelo) ? $producto->modelo : ''; ?>">
    </div>

    <div class="form-group">
        <label>Año</label>
        <input type="number" name="anio" class="form-control" 
            placeholder="Ej: 2020" 
            value="<?php echo isset($producto->anio) ? $producto->anio : ''; ?>">
    </div>
    <div class="form-group">
        <label>Versión / Línea</label>
        <input type="text" name="version" class="form-control" 
            placeholder="Ej: XLI, LTZ, Full, Limited" 
            value="<?php echo isset($producto->version) ? $producto->version : ''; ?>">
    </div>

    <div class="form-group">
        <label>Color</label>
        <input type="text" name="color" class="form-control" 
            placeholder="Ej: Blanco, Rojo, Negro" 
            value="<?php echo isset($producto->color) ? $producto->color : ''; ?>">
    </div>

    <div class="form-group">
        <label>Número de puertas</label>
        <select name="puertas" class="form-control">
            <option value="2" <?php echo ($producto->puertas == '2') ? 'selected' : ''; ?>>2</option>
            <option value="3" <?php echo ($producto->puertas == '3') ? 'selected' : ''; ?>>3</option>
            <option value="4" <?php echo ($producto->puertas == '4') ? 'selected' : ''; ?>>4</option>
            <option value="5" <?php echo ($producto->puertas == '5') ? 'selected' : ''; ?>>5</option>
        </select>
    </div>
    <div class="form-group">
        <label>Combustible</label>
        <select name="combustible" class="form-control">
            <option value="Gasolina" <?php echo ($producto->combustible == 'Gasolina') ? 'selected' : ''; ?>>Gasolina</option>
            <option value="Diésel" <?php echo ($producto->combustible == 'Diésel') ? 'selected' : ''; ?>>Diésel</option>
            <option value="Eléctrico" <?php echo ($producto->combustible == 'Eléctrico') ? 'selected' : ''; ?>>Eléctrico</option>
            <option value="Híbrido" <?php echo ($producto->combustible == 'Híbrido') ? 'selected' : ''; ?>>Híbrido</option>
        </select>
    </div>

    <div class="form-group">
        <label>Transmisión</label>
        <select name="transmision" class="form-control">
            <option value="Manual" <?php echo ($producto->transmision == 'Manual') ? 'selected' : ''; ?>>Manual</option>
            <option value="Automática" <?php echo ($producto->transmision == 'Automática') ? 'selected' : ''; ?>>Automática</option>
            <option value="CVT" <?php echo ($producto->transmision == 'CVT') ? 'selected' : ''; ?>>CVT</option>
            <option value="Tiptronic" <?php echo ($producto->transmision == 'Tiptronic') ? 'selected' : ''; ?>>Tiptronic</option>
        </select>
    </div>

    <div class="form-group">
        <label>Tracción</label>
        <select name="traccion" class="form-control">
            <option value="4x2" <?php echo ($producto->traccion == '4x2') ? 'selected' : ''; ?>>4x2</option>
            <option value="4x4" <?php echo ($producto->traccion == '4x4') ? 'selected' : ''; ?>>4x4</option>
            <option value="AWD" <?php echo ($producto->traccion == 'AWD') ? 'selected' : ''; ?>>AWD</option>
        </select>
    </div>


<div class="form-group">
    <label>Placa (si aplica)</label>
    <input type="text" name="placa" class="form-control" placeholder="Ej: ABC-123 o sin placa" value="<?php echo $producto->placa; ?>">
</div>

<hr>
<h4><i class="fa-solid fa-car-side"></i> Información del Vehículo</h4>

<div class="form-group">
    <label>Tipo de vehículo</label>
    <select name="tipo_vehiculo" class="form-control">
        <option value="Sedán" <?php echo ($producto->tipo_vehiculo == 'Sedán') ? 'selected' : ''; ?>>Sedán</option>
        <option value="SUV" <?php echo ($producto->tipo_vehiculo == 'SUV') ? 'selected' : ''; ?>>SUV</option>
        <option value="Camioneta" <?php echo ($producto->tipo_vehiculo == 'Camioneta') ? 'selected' : ''; ?>>Camioneta</option>
        <option value="Pickup" <?php echo ($producto->tipo_vehiculo == 'Pickup') ? 'selected' : ''; ?>>Pickup</option>
        <option value="Moto" <?php echo ($producto->tipo_vehiculo == 'Moto') ? 'selected' : ''; ?>>Moto</option>
        <!-- Agregá más si necesitás -->
    </select>
</div>

<div class="form-group">
    <label>Número de chasis (VIN)</label>
    <input type="text" name="vin" class="form-control" placeholder="Ingrese el VIN o número de chasis" value="<?php echo $producto->vin; ?>">
</div>
<div class="form-group">
    <label>Número de motor</label>
    <input type="text" name="motor" class="form-control" placeholder="Ingrese el número de motor" value="<?php echo $producto->motor; ?>">
</div>

<div class="form-group">
    <label>Kilometraje actual</label>
    <input type="number" name="kilometraje" class="form-control" placeholder="Ej: 85000" value="<?php echo $producto->kilometraje; ?>">
</div>

<div class="form-group">
    <label>Importado</label>
    <select name="importado" class="form-control">
        <option value="NO" <?php echo ($producto->importado == 'NO') ? 'selected' : ''; ?>>NO</option>
        <option value="SI" <?php echo ($producto->importado == 'SI') ? 'selected' : ''; ?>>SI</option>
    </select>
</div>
<div class="form-group">
    <label>País de origen (si es importado)</label>
    <input type="text" name="pais_origen" class="form-control" placeholder="Ej: Japón, Alemania, USA" value="<?php echo $producto->pais_origen; ?>">
</div>

<div class="form-group">
    <label>Fecha de importación</label>
    <input type="date" name="fecha_importacion" class="form-control" value="<?php echo $producto->fecha_importacion; ?>">
</div>

<div class="form-group">
    <label>¿Usado?</label>
    <select name="usado" class="form-control">
        <option value="NO" <?php echo ($producto->usado == 'NO') ? 'selected' : ''; ?>>NO</option>
        <option value="SI" <?php echo ($producto->usado == 'SI') ? 'selected' : ''; ?>>SÍ</option>
    </select>
</div>

<div class="form-group">
    <label>Dueño anterior</label>
    <input type="text" name="dueno_anterior" class="form-control" placeholder="Nombre completo o empresa" value="<?php echo $producto->dueno_anterior; ?>">
</div>

<div class="form-group">
    <label>Cédula/Pasaporte/Nro telefono</label>
    <input type="text" name="cedula_rif" class="form-control" placeholder="Ej:0983 3198595 sin caracteres especiales" value="<?php echo $producto->cedula_rif; ?>">
</div>


    <div class="form-group" style="display:none;">
        <label>Marca</label>
        <select name="marca" class="form-control">
            <?php foreach($this->marca->Listar() as $r): ?>
                <option value="<?php echo $r->id; ?>" <?php echo ($r->id == $producto->marca)? "selected":""; ?>><?php echo $r->marca; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" id="editorr" class="form-control"><?php echo $producto->descripcion; ?></textarea>
    </div>

    <div class="form-group">
        <label> <i class="fa-solid fa-arrows-up-down"></i> Precio costo</label>
        <input type="text" name="precio_costo" id="precio_costo" value="<?php echo $producto->precio_costo; ?>" class="form-control" placeholder="Ingrese el precio" required>
    </div>


    <div class="form-group" style="display:none;">
        <label>Precio may</label>
        <input type="number" id="porcentaje_mayorista" class="form-control" placeholder="Ingrese el porcentaje">
        <input type="number" name="precio_mayorista" id="precio_mayorista" value="<?php echo $producto->precio_mayorista; ?>" class="form-control" placeholder="Ingrese el precio">
    </div>

    <div class="form-group">
        <label> <i class="fa-solid fa-comment-dollar"></i> Precio de venta</label>
        <input type="number" id="porcentaje_minorista" class="form-control" placeholder="Ingrese el porcentaje">
        <input type="number" name="precio_minorista" id="precio_minorista" value="<?php echo $producto->precio_minorista; ?>" class="form-control" placeholder="Ingrese el precio">
    </div>
        <div class="form-group">
            <label> <i class="fa-solid fa-dollar-sign"></i> Precio financiado</label>
            <input type="number" name="precio_financiado" class="form-control" placeholder="Ej: 25000000" value="<?php echo isset($producto->precio_financiado) ? $producto->precio_financiado : ''; ?>">
        </div>

        <div class="form-group">
            <label> <i class="fa-solid fa-money-bill-wave"></i> Entrega mínima</label>
            <input type="number" name="entrega_minima" class="form-control" placeholder="Ej: 5000000" value="<?php echo isset($producto->entrega_minima) ? $producto->entrega_minima : ''; ?>">
        </div>

        <div class="form-group">
            <label> <i class="fa-solid fa-calendar-week"></i> Cuotas mínimas</label>
            <input type="number" name="cuotas_minimas" class="form-control" placeholder="Ej: 15000000" value="<?php echo isset($producto->cuotas_minimas) ? $producto->cuotas_minimas : ''; ?>">
        </div>
        <div class="form-group">
            <label> <i class="fa-solid fa-sort-numeric-up"></i> Cantidad de Refuerzos</label>
            <input type="number" name="cant_refuerzo" class="form-control" placeholder="Ej: 1, 2, 3" value="<?php echo isset($producto->cant_refuerzo) ? $producto->cant_refuerzo : ''; ?>">
        </div>

        <div class="form-group">
            <label> <i class="fa-solid fa-coins"></i> Monto Mínimo de Refuerzo</label>
            <input type="number" step="0.01" name="monto_minimo_refuerzo" class="form-control" placeholder="Ej: 5000000" value="<?php echo isset($producto->monto_minimo_refuerzo) ? $producto->monto_minimo_refuerzo : ''; ?>">
        </div>

    <div class="form-group">
        <label> <i class="fa-solid fa-basket-shopping"></i> Stock </label>
        <input type="number" name="stock" value="<?php echo $producto->stock; ?>" class="form-control" placeholder="Ingrese el stock ">
    </div>

    <div class="form-group" style="display:none;">
        <label>Descuento máximo</label>
        <input type="number" name="descuento_max" value="<?php echo $producto->descuento_max; ?>" class="form-control" placeholder="Ingrese el descuento máximo">
    </div>
    <div class="form-group">
        <label> <i class="fa-solid fa-square-poll-vertical"></i> IVA</label>
        <select name="iva" class="form-control">
            <option value="10" <?php echo ($producto->iva=='10')? "selected":""; ?>>10%</option>
            <option value="5" <?php echo ($producto->iva=='5')? "selected":""; ?>>5%</option>
        </select> 
    </div>
   <h4><i class="fa-solid fa-folder-open"></i> Documentos Adjuntos</h4>

<div class="form-group">
    <label>Título de propiedad</label>
    <?php if (!empty($producto->titulo_propiedad)): ?>
        <!-- Mostrar archivo actual si existe -->
        <p><a href="assets/documentos/<?php echo $producto->titulo_propiedad; ?>" target="_blank">Ver archivo actual</a></p>
        <!-- Campo oculto para mantener el nombre del archivo actual -->
        <input type="hidden" name="titulo_propiedad_actual" value="<?php echo $producto->titulo_propiedad; ?>">
    <?php else: ?>
        <p>No se ha subido ningún archivo</p>
    <?php endif; ?>
    <input type="file" name="titulo_propiedad" class="form-control">
</div>

<div class="form-group">
    <label>Factura original</label>
    <?php if (!empty($producto->factura_original)): ?>
        <!-- Mostrar archivo actual si existe -->
        <p><a href="assets/documentos/<?php echo $producto->factura_original; ?>" target="_blank">Ver archivo actual</a></p>
        <!-- Campo oculto para mantener el nombre del archivo actual -->
        <input type="hidden" name="factura_original_actual" value="<?php echo $producto->factura_original; ?>">
    <?php else: ?>
        <p>No se ha subido ningún archivo</p>
    <?php endif; ?>
    <input type="file" name="factura_original" class="form-control">
</div>

<div class="form-group">
    <label>Certificado de revisión técnica</label>
    <?php if (!empty($producto->revision_tecnica)): ?>
        <!-- Mostrar archivo actual si existe -->
        <p><a href="assets/documentos/<?php echo $producto->revision_tecnica; ?>" target="_blank">Ver archivo actual</a></p>
        <!-- Campo oculto para mantener el nombre del archivo actual -->
        <input type="hidden" name="revision_tecnica_actual" value="<?php echo $producto->revision_tecnica; ?>">
    <?php else: ?>
        <p>No se ha subido ningún archivo</p>
    <?php endif; ?>
    <input type="file" name="revision_tecnica" class="form-control">
</div>

<div class="form-group">
    <label>Permiso de circulación</label>
    <?php if (!empty($producto->permiso_circulacion)): ?>
        <!-- Mostrar archivo actual si existe -->
        <p><a href="assets/documentos/<?php echo $producto->permiso_circulacion; ?>" target="_blank">Ver archivo actual</a></p>
        <!-- Campo oculto para mantener el nombre del archivo actual -->
        <input type="hidden" name="permiso_circulacion_actual" value="<?php echo $producto->permiso_circulacion; ?>">
    <?php else: ?>
        <p>No se ha subido ningún archivo</p>
    <?php endif; ?>
    <input type="file" name="permiso_circulacion" class="form-control">
</div>



    
   <input type="hidden" name="imagen[]" class="form-control"  multiple>
    

    <hr />
    
    <div class="text-right">
        <button class="btn btn-success" id="guardar"> <i class="fa-solid fa-cloud-arrow-up"></i>  Guardar</button>
    </div>
</form>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>
</dir>

<script src="plugins/ckeditor/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ))
        .catch( error => {console.error( error );} );
</script>

<script type="text/javascript">
    $( "#porcentaje_minorista" ).keyup(function() {
        
        var costo = parseInt($("#precio_costo").val());
        var porcentaje = parseInt($("#porcentaje_minorista").val());

        var precio_minorista = costo + (costo * (porcentaje/100));
        
        $("#precio_minorista").val(precio_minorista);

    });
    
    $( "#autocodigo" ).click(function() {
        
        // find diff
        let difference = 999999 - 100000;
    
        // generate random number 
        let rand = Math.random();
    
        // multiply with difference 
        rand = Math.floor( rand * difference);
    
        // add with min value 
        rand = rand + 100000;
        
        $("#codigo").val(rand);
    });

    $( "#porcentaje_mayorista" ).keyup(function() {
        
        var costo = parseInt($("#precio_costo").val());
        var porcentaje = parseInt($("#porcentaje_mayorista").val());

        var precio_mayorista = costo + (costo * (porcentaje/100));
        
        $("#precio_mayorista").val(precio_mayorista);

    });

</script>
<script type="text/javascript">
hotkeys('f2, f4, ctrl+b', function (event, handler){
  switch (handler.key) {
    case 'f2': location.href ="?c=venta_tmp";
      break;
    case 'f4': $("#guardar").click();
      break;  
    case 'ctrl+b': alert('you pressed ctrl+b!');
      break;
    default: alert(event);
  }
});
</script>

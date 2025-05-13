<?php
$tmp = $this->venta_tmp->ObtenerDatosParcialesVenta();
$entrega = $tmp->entrega ?? 0;
$cuotas = $tmp->cuota_vehiculo ?? 0;
$monto_refuerzo = $tmp->monto_refuerzo ?? 0;
$cantidad_refuerzo = $tmp->cantidad_refuerzo ?? 0;
?> 

<div id="finalizarModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <form method="post" action="?c=venta&a=guardar" id="finalizar">
          <h2 align="center">GeoCad - Datos de Venta</h2>

          <!-- Cliente -->
          <div class="form-group">
            <label>Cliente / 
              <a class="btn btn-success pull-right" href="#clienteModal" data-toggle="modal" data-target="#crudModal" data-c="cliente"> 
                <i class="fa-solid fa-hands-holding-circle"></i> Agregar
              </a>
            </label>
            <select name="id_cliente" id="cliente" class="form-control">
              <option value="1" selected>Cliente ocasional</option>
              <?php foreach($this->cliente->Listar() as $cliente): ?>
              <option value="<?php echo $cliente->id; ?>">
                <?php echo $cliente->nombre; ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Comprobante -->
          <div class="form-group">
            <label><i class="fa-solid fa-receipt"></i> Comprobante</label>
            <select name="comprobante" class="form-control">
              <option value="Ticket">Ticket</option>
              <option value="TicketSi">Sin impresión</option>
              <option value="Factura">Factura</option>
            </select>
          </div>

          <!-- Nro Comprobante -->
          <div class="form-group" id="nro_comprobante">
            <label>Nro. comprobante</label>
            <input type="text" name="nro_comprobante" class="form-control">
          </div>

          <!-- Forma de pago -->
          <div class="form-group">
            <label><i class="fa-solid fa-arrows-up-down"></i> Forma de pago</label>
            <select name="contado" id="contado" class="form-control">
              <option value="Contado">Contado</option>
              <option value="Credito">Crédito</option>
            </select>
          </div>

          <!-- Entrega -->
          <div class="form-row" id="campos_credito" style="display: none;">
            <div class="col-sm-6">
              <label>Entrega</label>
              <input type="number" name="entrega" min="0" class="form-control" value="<?php echo $entrega; ?>">
            </div>

            <div class="col-sm-6">
              <label>Tipo de entrega</label>
              <select name="tipo_entrega" id="tipo_entrega" class="form-control">
                <option value="total">Entrega total</option>
                <option value="parcial">Entrega parcial</option>
              </select>
            </div>

            <div class="col-sm-6" id="entrega_parcial" style="display: none;">
              <label>Entrega inicial</label>
              <input type="number" name="entrega_inicial" min="0" class="form-control">
            </div>

            <div class="col-sm-6" id="entregas_restantes" style="display: none;">
              <label>Entregas restantes</label>
              <input type="number" name="entregas_restantes" min="0" class="form-control">
            </div>
              <!-- Este es el campo que se actualiza dinámicamente -->
            <div class="col-sm-12" id="detalle_entrega_parcial" style="display: none;">
              <label><strong>Monto estimado por entrega restante:</strong></label>
              <input type="text" id="monto_estimado" class="form-control bg-light" readonly value="--">
              <!-- Agregar un campo oculto para enviar el valor -->
              <input type="hidden" name="monto_estimado" id="monto_estimado_hidden" value="">
            </div>

             <div class="col-sm-6" id="vencimiento_entrega_restante" style="display: none;">
              <label>Vencimiento de la entrega restante </label>
              <input type="datetime-local" name="venci_entrega_restante" class="form-control" value="<?php echo date("Y-m-d") ?>T<?php echo date("H:i") ?>">
            </div>

            <div class="col-sm-6">
              <label>Cantidad de cuotas</label>
              <input type="number" name="cuo" min="1" max="160" class="form-control" value="<?php echo $cuotas; ?>">
            </div>

            <div class="col-sm-6">
              <label>Vencimiento</label>
              <input type="datetime-local" name="vencimiento" class="form-control" value="<?php echo date("Y-m-d") ?>T<?php echo date("H:i") ?>">
            </div>

            <div class="col-sm-6">
              <label>Monto del refuerzo</label>
              <input type="number" name="montoRefuerzo" class="form-control" value="<?php echo $monto_refuerzo; ?>">
            </div>

            <div class="col-sm-6">
              <label>Cantidad refuerzos</label>
              <input type="number" name="cantidadRefuerzo" class="form-control" value="<?php echo $cantidad_refuerzo; ?>">
            </div>

            <div class="col-sm-6">
              <label>Fecha refuerzo</label>
              <input type="datetime-local" name="fecha_refuerzo" class="form-control" value="<?php echo date("Y-m-d") ?>T<?php echo date("H:i") ?>">
              <small class="form-text text-muted">Siempre en diciembre</small>
            </div>
            <!-- Nueva opción: Frecuencia de pagos -->
           <div class="col-sm-6">
              <label><i class="fa-solid fa-calendar"></i> Frecuencia de pagos</label>
              <select name="frecuencia_pagos" id="frecuencia_pagos" class="form-control">
                <option value="Trimestral">Trimestral </option>
                <option value="Semestral">Semestral</option>
                <option value="Novenal">Novenal</option>
                <option value="Anual">Anual</option>
              </select>
            </div>
          </div>

          <!-- Pagos parciales, gifts, etc -->
          <input type="hidden" name="pago" value="5">
          <div id="pagos"><?php require_once 'view/pago_tmp/pago_tmp.php'; ?></div>
        </form>
      </div>

      <div class="modal-footer">
        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
      </div>

      <div>
        <ul class="list-unstyled CTAs">
          <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company <?php echo date("Y") ?></a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {

  // Función para calcular la entrega parcial
  function calcularEntregaParcial() {
    let total = parseFloat($("input[name='entrega']").val()) || 0;
    let inicial = parseFloat($("input[name='entrega_inicial']").val()) || 0;
    let restantes = parseInt($("input[name='entregas_restantes']").val()) || 0;

    if (inicial > total) {
      $("#monto_estimado").val("¡Error: entrega inicial mayor al total!");
      $("#monto_estimado_hidden").val("");
      return;
    }

    if (total > 0 && inicial >= 0 && restantes > 0) {
      let restante = total - inicial;
      let montoPorEntrega = (restante / restantes).toFixed(2);

      // Mostrar en el campo visible (formateado)
      $("#monto_estimado").val(`₲ ${parseFloat(montoPorEntrega).toLocaleString('es-PY')}`);

      // Asignar valor real sin formato al campo oculto
      $("#monto_estimado_hidden").val(montoPorEntrega);

      $("#detalle_entrega_parcial").show();
    } else {
      $("#monto_estimado").val("¡Error: Verifique los valores ingresados!");
      $("#monto_estimado_hidden").val("");
      $("#detalle_entrega_parcial").hide();
    }
  }

  // Mostrar los campos de crédito según la selección de "contado" o "crédito"
  $('#contado').on('change', function() {
    let tipoPago = $(this).val();
    if (tipoPago === 'Credito') {
      $('#campos_credito').show();
    } else {
     $('#campos_credito, #entrega_parcial, #entregas_restantes, #detalle_entrega_parcial, #vencimiento_entrega_restante').hide();
    }
  });

  $('#tipo_entrega').on('change', function() {
    if ($(this).val() === 'parcial') {
        $('#entrega_parcial, #entregas_restantes, #detalle_entrega_parcial, #vencimiento_entrega_restante').show();
    } else {
        $('#entrega_parcial, #entregas_restantes, #detalle_entrega_parcial, #vencimiento_entrega_restante').hide();
    }
});


  // Validar en tiempo real cuando se cambia algún valor
  $("input[name='entrega'], input[name='entrega_inicial'], input[name='entregas_restantes']").on('input', function () {
    calcularEntregaParcial(); // Calcular el monto estimado por entrega restante al cambiar los valores
  });

  // Validación antes de enviar el formulario
  $('#finalizar').on('submit', function (e) {
    let total = parseFloat($("input[name='entrega']").val()) || 0;
    let inicial = parseFloat($("input[name='entrega_inicial']").val()) || 0;
    let restantes = parseInt($("input[name='entregas_restantes']").val()) || 0;

    // Validar que la entrega inicial no sea mayor al total
    if (inicial > total) {
      alert("La entrega inicial no puede ser mayor al total.");
      e.preventDefault(); // Detener el envío del formulario
      return;
    }

    // Validar que las entregas restantes sean válidas
    //if (restantes <= 0) {
     // alert("Debe ingresar una cantidad válida de entregas restantes.");
     // e.preventDefault(); // Detener el envío del formulario
    //  return;
   // }

    // Validar que si el tipo de entrega es parcial, se ingrese el vencimiento
    if ($('#tipo_entrega').val() === 'parcial') {
      let vencimientoEntrega = $("input[name='venci_entrega_restante']").val();
      if (!vencimientoEntrega) {
        alert("Debe ingresar el vencimiento de la entrega restante.");
        e.preventDefault(); // Detener el envío del formulario
        return;
      }
    }
  });


});
</script>

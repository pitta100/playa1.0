<?php
require_once('plugins/tcpdf2/tcpdf.php');

// Obtener la data
$id_venta = $_GET['id'];
$venta_detalles = $this->venta->Listar($id_venta); // Devuelve un array

if (empty($venta_detalles)) {
    die('Venta no encontrada.');
}

// Tomamos los datos generales de la venta desde la primera fila
$venta = $venta_detalles[0];

// Crear instancia de TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Ventas');
$pdf->SetTitle('Reporte de Venta Detallado');
$pdf->SetSubject('Venta de Servicios');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

// Encabezado
$html = <<<EOD
<h2 style="text-align: center;"> Detalle de Venta N.º {$venta->id_venta}</h2>
<hr>
<h3>NOMBRE DEL  COMPRADOR /CLIENTE</h3>
<strong>Nombre:</strong> {$venta->nombre_cli}<br>
<strong>RUC:</strong> {$venta->ruc}<br>
<strong>Dirección:</strong> {$venta->direccion}<br>
<strong>Teléfono:</strong> {$venta->telefono}<br>
<br>

<h3>Información General de la Venta</h3>
<table border="1" cellpadding="5">
    <tr><td><strong>Vendedor:</strong></td><td>{$venta->user}</td></tr>
    <tr><td><strong>Vendedor Salón:</strong></td><td>{$venta->vendedor_salon}</td></tr>
    <tr><td><strong>Método de Pago:</strong></td><td>{$venta->metodo}</td></tr>
    <tr><td><strong>Forma:</strong></td><td>{$venta->contado}</td></tr>
    <tr><td><strong>Banco:</strong></td><td>{$venta->banco}</td></tr>
    <tr><td><strong>Total:</strong></td><td>{$venta->total} Gs/Us.</td></tr>
    <tr><td><strong>Comprobante:</strong></td><td>{$venta->comprobante} - {$venta->nro_comprobante}</td></tr>
    <tr><td><strong>Fecha de Venta:</strong></td><td>{$venta->fecha_venta}</td></tr>
</table>
<br><br>

<h3> Detalles de Vehículos</h3>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr style="background-color: #f0f0f0;">
            <th><b>Producto</b></th>
            <th><b>Marca</b></th>
            <th><b>Modelo</b></th>
            <th><b>Año</b></th>
            <th><b>Color</b></th>
            <th><b>Placa</b></th>
            <th><b>Kilometraje</b></th>
            <th><b>VIN</b></th>
        </tr>
    </thead>
    <tbody>
EOD;

// Agregar todos los productos/vehículos de la venta
foreach ($venta_detalles as $v) {
    $html .= "
    <tr>
        <td>{$v->producto}</td>
        <td>{$v->marcaVehiculo}</td>
        <td>{$v->modelo}</td>
        <td>{$v->anio}</td>
        <td>{$v->color}</td>
        <td>{$v->placa}</td>
        <td>{$v->kilometraje} km</td>
        <td>{$v->vin}</td>
    </tr>";
}

$html .= "</tbody></table>";
$html .= <<<EOD
<h3>DATOS ANTERIORES</h3>
<table border="1" cellpadding="5">
    <tr>
        <td><strong>Dueño Anterior:</strong></td>
        <td>{$venta->dueno_anterior}</td>
    </tr>
    <tr>
        <td><strong>Cédula/Pasaporte/Nro telefono:</strong></td>
        <td>{$venta->cedula_rif}</td>
    </tr>
</table>
EOD;


$html .= "<br><br><p style='text-align: center;'>&copy; PITTA100 Company - Sistemas Informáticos- P & Q AUTOMOTORES SA.</p>";

// Generar PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output("Venta_Detalle_{$venta->id_venta}.pdf", 'I'); // 'I' = inline en navegador
?>

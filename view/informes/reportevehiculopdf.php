<?php
require_once('plugins/tcpdf2/tcpdf.php');

if (!isset($_POST['vehiculo'])) {
    die("No se enviaron datos del vehículo.");
}

$vehiculo = json_decode($_POST['vehiculo']);

// Ahora que ya tenemos $vehiculo, podemos buscar la última venta
require_once __DIR__ . '/../../model/producto.php';
$modelo = new Producto();
$venta = $modelo->ObtenerUltimaVenta($vehiculo->id);
$tieneVenta = $venta && is_object($venta); // Verifica si hay datos reales

// Crear nuevo documento PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('PITTA100');
$pdf->SetAuthor('P & Q AUTOMOTORES SA');
$pdf->SetTitle('Reporte del Vehículo');
$pdf->SetHeaderData('img/logo.png', 30, "Compra y Venta de vehiculo", "Sistemas P&Q, Automotores S A.\nTel: +595 123 456 789\nDirección: Super Carretera 123");

$pdf->setHeaderFont(['helvetica', '', 10]);
$pdf->setFooterFont(['helvetica', '', 8]);
$pdf->SetMargins(15, 30, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(15);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->SetFont('helvetica', '', 11);
$pdf->AddPage();

$html = <<<EOD
<style>
    h2, h3 {
        text-align: center;
        font-family: helvetica;
        font-weight: bold;
    }

    table {
        width: 95%;
        border-collapse: collapse;
        margin: 10px auto;
        font-family: helvetica;
    }

    th {
        background-color: #f0f0f0;
        text-align: left;
        padding: 6px;
        font-weight: bold;
        width: 40%;
    }

    td {
        padding: 6px;
        text-align: center;
    }

    .footer {
        text-align: center;
        margin-top: 40px;
        font-size: 10px;
        font-style: italic;
        color: #555;
    }
</style>

<h2>Historial del Vehículo</h2>
<table border="1">
    <tr><th>Código</th><td>{$vehiculo->codigo}</td></tr>
    <tr><th>Producto</th><td>{$vehiculo->producto}</td></tr>
    <tr><th>Descripción</th><td>{$vehiculo->descripcion}</td></tr>
    <tr><th>Marca</th><td>{$vehiculo->marcaVehiculo}</td></tr>
    <tr><th>Chasis (VIN)</th><td>{$vehiculo->vin}</td></tr>
    <tr><th>Motor</th><td>{$vehiculo->motor}</td></tr>
    <tr><th>Kilometraje</th><td>{$vehiculo->kilometraje} km</td></tr>
    <tr><th>País de Origen</th><td>{$vehiculo->pais_origen}</td></tr>
    <tr><th>Tracción</th><td>{$vehiculo->traccion}</td></tr>
    <tr><th>Nro de Placa</th><td>{$vehiculo->placa}</td></tr>
    <tr><th>Tipo de Vehículo</th><td>{$vehiculo->tipo_vehiculo}</td></tr>
    <tr><th>Fecha de Importación</th><td>{$vehiculo->fecha_importacion}</td></tr>
    <tr><th>Precio de Venta</th><td>{$vehiculo->precio_minorista}</td></tr>
    <tr><th>¿Usado?</th><td>{$vehiculo->usado}</td></tr>
</table>

<h3>Valores Financieros Estimados</h3>
<table border="1">
    <tr><th>Precio Minorista</th><td>{$vehiculo->precio_minorista}</td></tr>
    <tr><th>Precio Financiado</th><td>{$vehiculo->precio_financiado}</td></tr>
    <tr><th>Entrega Mínima Solicitada</th><td>{$vehiculo->entrega_minima}</td></tr>
    <tr><th>Cuotas Mínimas</th><td>{$vehiculo->cuotas_minimas}</td></tr>
    <tr><th>Cantidad de Refuerzos</th><td>{$vehiculo->cant_refuerzo}</td></tr>
    <tr><th>Monto Mínimo del Refuerzo</th><td>{$vehiculo->monto_minimo_refuerzo}</td></tr>
</table>

<h3>Último Dueño Registrado</h3>
<table border="1">
    <tr><th>Nombre</th><td>{$vehiculo->dueno_anterior}</td></tr>
    <tr><th>Cédula / RUC</th><td>{$vehiculo->cedula_rif}</td></tr>
</table>

<h3>Nuevo Propietario</h3>
<table border="1">
EOD;

if ($tieneVenta) {
    $html .= <<<EOD
    <tr><th>Cliente</th><td>{$venta->nombre}</td></tr>
    <tr><th>RUC</th><td>{$venta->ruc}</td></tr>
    <tr><th>Teléfono</th><td>{$venta->telefono}</td></tr>
    <tr><th>Correo Electrónico</th><td>{$venta->correo}</td></tr>
    <tr><th>Dirección Laboral</th><td>{$venta->adressWork}</td></tr>
    <tr><th>Teléfono Laboral</th><td>{$venta->phoneWork}</td></tr>
    <tr><th>Fecha de Venta</th><td>{$venta->fecha_venta}</td></tr>
EOD;
} else {
    $html .= <<<EOD
    <tr><td colspan="2" style="text-align:center; color: #b00;">
        Este vehículo aún no fue vendido.
    </td></tr>
EOD;
}

$html .= <<<EOD
</table>

<div class="footer">
    Todos los derechos reservados para la empresa <strong>P&Q Automotores S.A.</strong><br>
    <em>Versión 1.2 - Año 2025</em>
</div>
EOD;

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output("reporte_vehiculo_{$vehiculo->codigo}.pdf", 'D');

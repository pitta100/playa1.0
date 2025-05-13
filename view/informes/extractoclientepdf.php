<?php

//require_once('plugins/tcpdf/pdf/tcpdf_include.php');
require_once('plugins/tcpdf2/tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->AddPage('P', 'A4');

$fechahoy = date("d/m/Y");
$horahoy = date("H:i", strtotime("-4 HOUR"));

$cliente = $_REQUEST['cli'];
$id_cliente = $_REQUEST['id'];

$html1 = <<<EOF
    <br>
    <h1 align="center">Deudas de $cliente</h1>
    <h4 align="center">Generado el $fechahoy a las $horahoy</h4>
EOF;

$pdf->writeHTML($html1, false, false, false, false, '');

$totalDeuda = 0;
foreach($this->model->Listar_cliente($_REQUEST['id']) as $d):
    // Obtener las fechas y valores
    $fecha = date("d/m/Y", strtotime($d->fecha));
    $monto = number_format($d->monto, 0, ",", ".");
    $saldo = number_format($d->saldo, 0, ",", ".");
    
    // Para los refuerzos
    $Monto_Refuerzo = number_format($d->montoRefuerzo, 0, ",", ".");
    $cantidad_refuerzo = number_format($d->cantidadRefuerzo, 0, ",", ".");
    $fecha_refuerzo = date("d/m/Y", strtotime($d->fecha_refuerzo)); // Formateamos la fecha de refuerzo
    
    // Nuevas variables para cuotas y vencimiento
    $cuotas_pagadas = number_format($d->cuotas, 0, ",", ".");
    $vencimiento_cuota = date("d/m/Y", strtotime($d->vencimiento)); // Formateamos la fecha de vencimiento

    // Aquí agregamos la tabla con los productos
    $html1 = <<<EOF
        &nbsp;
        <h4>Fecha $fecha</h4>
        <table width="100%" style="border: 1px solid #333; font-size:10px;background-color: #348993; color: white">
            <tr align="center">
                <td width="50%" style="border-left-width:1px ; border-right-width:1px">Producto</td>
                <td width="10%" style="border-left-width:1px ; border-right-width:1px">Cantidad</td>
                <td width="20%" style="border-left-width:1px ; border-right-width:1px">Precio</td>
                <td width="20%" style="border-left-width:1px ; border-right-width:1px">Total</td>
            </tr>
        </table>
    EOF;
    $pdf->writeHTML($html1, false, false, false, false, '');

    // Mostrar productos de la venta
    foreach($this->venta->Listar($d->id_venta) as $r):
        $total = number_format($r->subtotal, 0, ",", ".");
        $precio = number_format($r->precio_venta, 0, ",", ".");
        $html1 = <<<EOF
            <table width="100%" style="border: 1px solid #333; font-size:9px">
                <tr align="center">
                    <td width="50%" style="border-left-width:1px ; border-right-width:1px">$r->producto $r->codigo</td>
                    <td width="10%" style="border-left-width:1px ; border-right-width:1px">$r->cantidad</td>
                    <td width="20%" style="border-left-width:1px ; border-right-width:1px">$precio</td>
                    <td width="20%" style="border-left-width:1px ; border-right-width:1px">$total</td>
                </tr>
            </table>
        EOF;

        $pdf->writeHTML($html1, false, false, false, false, '');
    endforeach;

    // Detalles de deuda (sin cambios)
    $html1 = <<<EOF
        <table width="100%" style="border: 1px solid #333; font-size:10px">
            <tr align="right">
                <td style="border-left-width:1px ; border-right-width:1px">$d->concepto</td>
            </tr>
            <tr align="right">
                <td style="border-left-width:1px ; border-right-width:1px">Monto: <b>$monto Gs.</b></td>
            </tr>
            <tr align="right">
                <td style="border-left-width:1px ; border-right-width:1px">Saldo: <b>$saldo Gs.</b></td>
            </tr>
            <tr align="right">
                <td style="border-left-width:1px ; border-right-width:1px">Cuotas por Pagar : <b>$cuotas_pagadas</b></td>
            </tr>
            <tr align="right">
                <td style="border-left-width:1px ; border-right-width:1px">Vencimiento Cuota: <b>$vencimiento_cuota</b></td>
            </tr>
        </table>
    EOF;

    $pdf->writeHTML($html1, false, false, false, false, '');
    $totalDeuda += $d->saldo;

    // Tabla de refuerzos (si existen)
    if (!empty($Monto_Refuerzo)) {
        $htmlRefuerzo = <<<EOF
            <h4>Refuerzo</h4>
            <table width="100%" style="border: 1px solid #333; font-size:10px;background-color: #348993; color: white">
                <tr align="center">
                    <td width="33%" style="border-left-width:1px ; border-right-width:1px">Monto Refuerzo</td>
                    <td width="33%" style="border-left-width:1px ; border-right-width:1px">Cantidad Refuerzo</td>
                    <td width="33%" style="border-left-width:1px ; border-right-width:1px">Fecha Refuerzo</td>
                </tr>
            </table>
            <table width="100%" style="border: 1px solid #333; font-size:10px">
                <tr align="center">
                    <td width="33%" style="border-left-width:1px ; border-right-width:1px">$Monto_Refuerzo</td>
                    <td width="33%" style="border-left-width:1px ; border-right-width:1px">$cantidad_refuerzo</td>
                    <td width="33%" style="border-left-width:1px ; border-right-width:1px">$fecha_refuerzo</td>
                </tr>
            </table>
        EOF;
        $pdf->writeHTML($htmlRefuerzo, false, false, false, false, '');
    }

endforeach;

// Calcular el total
$deuda = number_format($totalDeuda, 0, ",", ".");
$html1 = <<<EOF
    &nbsp;
    <h1 align="center">Saldo total = $deuda Gs</h1>
EOF;
$pdf->writeHTML($html1, false, false, false, false, '');

// Finaliza el PDF
$pdf->Output('cierre.pdf', 'I');
?>

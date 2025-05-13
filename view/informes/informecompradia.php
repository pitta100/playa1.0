<?php

//require_once('plugins/tcpdf/pdf/tcpdf_include.php');
require_once('plugins/tcpdf2/tcpdf.php');


$moneda = $this->compra_tmp->ObtenerMoneda();


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->AddPage('P', 'A4');

$fechahoy = date("d/m/Y");
$fechaInforme = date("d-m-Y", strtotime($_REQUEST['fecha']));
$horahoy = date("H:i");

$inicial=number_format($moneda->monto_inicial,0,",",".");
$caja_inicial = $moneda->monto_inicial;
$real=number_format($moneda->reales,0,",",".");
$dolar=number_format($moneda->dolares,0,",",".");

$html1 = <<<EOF
		<h1 align="center">Informe de la fecha $fechaInforme</h1>
		<p>Generado a las $horahoy de la fecha $fechahoy</p>
		<div>
		<table width="100%">
		<tr>
		<td>
		<table width="60%" style="border: 1px solid #333; float:right">
			<tr>
                <th style="border: 1px solid #333; font-size:12px; background-color: #7dcd5d ; color: white; text-align:center" colspan="2">Cotización del día</th>
        	</tr>
			<tr>
				<td style="border-left-width:1px ; border-right-width:1px ; border-bottom-width:1px; text-align:center">Real </td>
				<td style="border-left-width:1px ; border-bottom-width:1px; border-right-width:1px; text-align:center">$real</td>
			</tr>
			<tr>
				<td style="border-left-width:1px ; border-right-width:1px ;  text-align:center">Dolar </td>
				<td style="border-left-width:1px ; border-right-width:1px; text-align:center">$dolar</td>
			</tr>
		</table>
		</td>
		<td>
		
		</td>
		</tr>
		</table>
		</div>

EOF;


$pdf->writeHTML($html1, false, false, false, false, '');

$totalCredito = 0;
$totalContado = 0;
$totalCosto = 0;
$totalVenta = 0;

foreach($this->model->ListarDiaSinAnular($_REQUEST['fecha']) as $r):

$total=number_format($r->total,0,",",".");
//$costo=number_format($r->costo,0,",",".");
$ganancia=number_format(($r->total - $r->costo),0,",",".");
$hora = date("H:i", strtotime($r->fecha_venta));
$html1 = <<<EOF
		
		
EOF;

$pdf->writeHTML($html1, false, false, false, false, '');

$totalCosto += $r->costo;
$totalVenta += $r->total;

if($r->contado=='contado'){
    $totalContado += $r->total;
}else{
    $totalCredito += $r->total;
}
endforeach;

$totalCostoV = number_format($totalCosto,0,",",".");
$totalVentaV = number_format($totalVenta,0,",",".");
$totalGananciaV = number_format(($totalVenta - $totalCosto),0,",",".");

$html1 = <<<EOF
		
	
EOF;

$pdf->writeHTML($html1, false, false, false, false, '');

$totalContadoV=number_format($totalContado,0,",",".");
$totalCreditoV=number_format($totalCredito,0,",",".");

$html1 = <<<EOF
		

EOF;
$pdf->writeHTML($html1, false, false, false, false, '');

$html1 = <<<EOF
		

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');

$totalIngreso = 0;

/*foreach($this->ingreso->ListarSinVenta($_REQUEST['fecha']) as $i):

$monto=number_format($i->monto,2,",",".");
$html1 = <<<EOF
		
		<table width"100%" style="border: 1px solid #333; font-size:10px">
			<tr align="center">
				<td style="border-left-width:1px ; border-right-width:1px">$i->concepto</td>
				<td style="border-left-width:1px ; border-right-width:1px">$monto</td>
			</tr>
		</table>

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');
$totalIngreso += $i->monto;
endforeach;*/
$ingreso=number_format($totalIngreso,0,",",".");
$html1 = <<<EOF
		

EOF;
$pdf->writeHTML($html1, false, false, false, false, '');

$html1 = <<<EOF
        <br>
		<h1 align="center"> sector Compras</h1>

		<table width"100%" style="border: 1px solid #333; font-size:12px; background-color: #7dcd5d ; color: white">
			<tr align="center">
                <th style="border-left-width:1px ; border-right-width:1px">Cliente</th>
             	<th style="border-left-width:1px ; border-right-width:1px">Total (USD)</th>
             	<th style="border-left-width:1px ; border-right-width:1px">Vendedor</th>
				<th style="border-left-width:1px ; border-right-width:1px">Método</th>
             	<th style="border-left-width:1px ; border-right-width:1px">Pago</th>
			</tr>
		</table>

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');

$totalCreditoCompra = 0;
$totalContadoCompra = 0;

foreach($this->compra->ListarDiaSinAnular($_REQUEST['fecha']) as $r):

$total=number_format($r->subtotal,0,",",".");
$html1 = <<<EOF
		
		<table width"100%" style="border: 1px solid #333; font-size:10px">
			<tr align="center">
				<td style="border-left-width:1px ; border-right-width:1px">$r->nombre_cli $r->apellido_cli</td>
				<td style="border-left-width:1px ; border-right-width:1px">$total</td>
				<td style="border-left-width:1px ; border-right-width:1px">$r->vendedor</td>
				<td style="border-left-width:1px ; border-right-width:1px">$r->metodo</td>
				<td style="border-left-width:1px ; border-right-width:1px">$r->contado</td>
			</tr>
		</table>

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');



if($r->contado=='Contado'){
    $totalContadoCompra += $r->total;
}else{
    $totalCreditoCompra += $r->total;
}
endforeach;

$totalContadoCompraV=number_format($totalContadoCompra,0,",",".");
$totalCreditoCompraV=number_format($totalCreditoCompra,0,",",".");

$html1 = <<<EOF
		<table width"100%" style="border: 1px solid #333">
			<tr align="center">
				<td style="border-left-width:1px ; border-right-width:1px ; text-align:right" colspan="3">Total Contado (Gs): </td>
				<td style="border-left-width:1px ; border-right-width:1px">$totalContadoCompraV</td>
			</tr>
			<tr align="center">
				<td style="border-left-width:1px ; border-right-width:1px ; text-align:right" colspan="3">Total Crédito (Gs): </td>
				<td style="border-left-width:1px ; border-right-width:1px">$totalCreditoCompraV</td>
			</tr>
		</table>

EOF;
$pdf->writeHTML($html1, false, false, false, false, '');

$html1 = <<<EOF
		
EOF;

$pdf->writeHTML($html1, false, false, false, false, '');

$totalEgreso = 0;

foreach($this->egreso->ListarSinCompra($_REQUEST['fecha']) as $e):

$monto=number_format($e->monto,0,",",".");
$html1 = <<<EOF
		
		<table width"100%" style="border: 1px solid #333; font-size:10px">
			<tr align="center">
				<td style="border-left-width:1px ; border-right-width:1px">$e->concepto</td>
				<td style="border-left-width:1px ; border-right-width:1px">$monto</td>
			</tr>
		</table>

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');
$totalEgreso += $e->monto;
endforeach;


$pdf->writeHTML($html1, false, false, false, false, '');

$html1 = <<<EOF
        <br>
		<h1 align="center"> sector Resumen</h1>
		<table width"100%" style="border: 1px solid #333">
			<tr align="center">
				<td style="border-left-width:1px ; border-right-width:1px ; text-align:right" colspan="3">Venta al contado: </td>
				<td style="border-left-width:1px ; border-right-width:1px">$totalContadoV</td>
			</tr>
			
			<tr align="center">
				<td style="border-left-width:1px ; border-right-width:1px ; text-align:right" colspan="3">Otros ingresos: </td>
				<td style="border-left-width:1px ; border-right-width:1px">$ingreso</td>
			</tr>
			<tr align="center">
				<td style="border-left-width:1px ; border-right-width:1px ; text-align:right" colspan="3">Compra al contado: </td>
				<td style="border-left-width:1px ; border-right-width:1px">$totalContadoCompraV</td>
			</tr>
			<tr align="center">
				<td style="border-left-width:1px ; border-right-width:1px ; text-align:right" colspan="3">Compra a crédito: </td>
				<td style="border-left-width:1px ; border-right-width:1px">$totalCreditoCompraV</td>
			</tr>
			
		</table>
		<h5 align="center">  P & Q AUTOMOTORES SA.::Todos los derechos reservados  tesh 2025</h5>

EOF;
$pdf->writeHTML($html1, false, false, false, false, '');

ob_end_clean();
$pdf->Output('cierre.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+
  ?>
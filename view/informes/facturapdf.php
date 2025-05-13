<?php

// PRUEBA

/**
 * Clase que implementa un coversor de números
 * a letras.
 *
 * Soporte para PHP >= 5.4
 * Para soportar PHP 5.3, declare los arreglos
 * con la función array.
 *
 * @author AxiaCore S.A.S
 *
 */

class NumeroALetras
{
    private static $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];

    private static $DECENAS = [
        'VENTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];

    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];

    public static function convertir($number, $moneda = '', $centimos = '', $forzarCentimos = false)
    {
        $converted = '';
        $decimales = '';

        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }

        $div_decimales = explode('.',$number);

        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                $decCientos = substr($decNumberStrFill, 6);
                $decimales = self::convertGroup($decCientos);
            }
        }
        else if (count($div_decimales) == 1 && $forzarCentimos){
            $decimales = 'CERO ';
        }

        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);

        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }

        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }

        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }

        if(empty($decimales)){
            $valor_convertido = $converted . strtoupper($moneda);
        } else {
            $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . $decimales . ' ' . strtoupper($centimos);
        }

        return $valor_convertido;
    }

    private static function convertGroup($n)
    {
        $output = '';

        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];   
        }

        $k = intval(substr($n,1));

        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }

        return $output;
    }
}



// FIN  PRUEBA 


//require_once('plugins/tcpdf/pdf/tcpdf_include.php');
require_once('plugins/tcpdf2/tcpdf.php');


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$medidas = array(210, 347); // Ajustar aqui segun los milimetros necesarios;
$pdf = new TCPDF('P', 'mm', $medidas, true, 'UTF-8', false);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

$pdf->AddPage();

$id_venta = $_GET['id'];
$gift = $this->ingreso->ObtenerGift($id_venta);

if($gift!=''){
    $descuento_gift = $gift->monto;
}else{
    $descuento_gift = 0;
}
foreach($this->venta->Listar($id_venta) as $r){
    $cliente = $r->nombre_cli;
    $ruc = $r->ruc;
    $fecha = date("d/m/Y", strtotime($r->fecha_venta));
    $telefono = $r->telefono;
    $direccion = $r->direccion;
    $vendedor = $r->vendedor;
    $contado = "";
    $credito = "";
    if($r->contado=="Contado"){
        $contado = "Contado";
    }else{
        $contado = "Credito";
    }
}


$header = <<<EOF
    <h1> </h1>
	<table width ="100%" style="text-align:center; line-height: 15px; font-size:9px">
	    <tr>
          <td width="100%" style="font-size:23px"></td>
        </tr>
	    <tr>
          <td width="15%" style="font-size:8px"></td>
          <td width="68%" align="left" style="font-size:8px" > $fecha </td>
          <td width="7%" align="left"style="font-size:8px">$contado</td>
          <td width="10%" align="left"style="font-size:8px"></td>
        </tr>
        <tr>
          <td width="17%" style="font-size:8px"></td>
          <td width="65%" align="left" style="font-size:8px" nowrap>$cliente</td>
          <td width="10%" align="left" style="font-size:8px"></td>
          <td width="5%" style="font-size:8px"></td>
        </tr>
        <tr align="left">
          <td width="10%"style="font-size:8px"></td>
          <td width="70%"style="font-size:8px">$ruc</td>
          <td width="25%"style="font-size:8px"></td>
          <td width="5%"style="font-size:8px"></td>
        </tr>
    </table>
    <table>
		<tr>
			<td width="10%" style="font-size:8px"></td>
			<td width="70%" style="font-size:8px">$direccion</td>
			<td width="25%"style="font-size:8px"></td>
            <td width="5%"style="font-size:8px"></td>
		</tr>
	</table>
	<table>
		<tr nowrap="nowrap" style="font-size:10px;">
			<td width="7%" ></td>
			<td width="44%"></td>
			<td width="12%" align="right"></td>
			<td width="12%"></td>
			<td width="12%" align="right"></td>
			<td width="12%" align="right"></td>
		</tr>
	</table>
EOF;

$pdf->writeHTML($header, false, false, false, false, '');

$sumaTotal = 0;
$cantidad = 0;

foreach($this->venta->Listar($id_venta) as $r){
$cantidad++;
if ($r->iva==5){
  //$sumaTotal5 += ((($r->precio_venta*$r->cantidad)-($r->precio_venta*$r->cantidad*($r->descuento/100))));
  //$iva5+=($r->precio_venta*$r->cantidad)-(($r->precio_venta*$r->cantidad)/1.05);
  $iva5P=number_format(((($r->precio_venta*$r->cantidad)-($r->precio_venta*$r->cantidad*($r->descuento/100)))), 0, "," , ".");
  $iva10P = "";
} 
else
{
  //$sumaTotal10 += ((($r->precio_venta*$r->cantidad)-($r->precio_venta*$r->cantidad*($r->descuento/100))));
  //$iva10+=($r->precio_venta*$r->cantidad)/11;
  $iva10P=number_format(((($r->precio_venta*$r->cantidad)-($r->precio_venta*$r->cantidad*($r->descuento/100)))), 0, "," , ".");
  $iva5P="";
}

$subTotal = number_format(($r->precio_venta), 0, "," , ".");
$descuento = ($r->precio_venta)-($r->precio_venta*($r->descuento/100)); //precio con descuento
$descuento = number_format($descuento, 0, "," , ".");
$total = (($r->precio_venta*$r->cantidad)-($r->precio_venta*$r->cantidad*($r->descuento/100)));
$total =  number_format($total, 0, "," , ".");




if($cantidad>12){
    
$pdf->writeHTML($items, false, false, false, false, '');

$c=(12-$cantidad);

for($i=0;$i<$c;$i++){
    
$espacio .= <<<EOF

		<table>
			<tr nowrap="nowrap" style="font-size:7px;">
				<td width="7%" ></td>
				<td width="44%"></td>
				<td width="12%"></td>
				<td width="12%"></td>
				<td width="12%"></td>
				<td width="12%"></td>
			</tr>
		</table>

EOF;

}

$pdf->writeHTML($espacio, false, false, false, false, '');


$letrasDecimal = "";
if($sumaTotal != intval($sumaTotal)){
    $decimal = ($sumaTotal - intval($sumaTotal))*100;
    $letrasDecimal = 'CON '.NumeroALetras::convertir($decimal).' CENTAVOS';
}
$letras = NumeroALetras::convertir($sumaTotal);
$sumaTotalV =  number_format($sumaTotal, 0, "," , ".");
$sumaTotal5V =  number_format($sumaTotal5, 0, "," , ".");
$sumaTotal10V =  number_format($sumaTotal10, 0, "," , ".");
$iva5V=number_format($iva5, 0, "," , ".");
$iva10V=number_format($iva10, 0, "," , ".");
$ivaTotal = number_format(($iva5 + $iva10), 0, "," , ".");
$footer = <<<EOF
	
<table width="100%" style="text-align:center; line-height: 15px; font-size:8px">
		<tr align="right">
		  <td width="5%"style="font-size:8px"></td>
		  <td width="65%"style="font-size:8px"></td>
	      <td width="12%" style="font-size:8px"><b>$sumaTotal5V</b></td>
	      <td width="19%"style="font-size:8px"><b>$sumaTotal10V</b></td>
	    </tr>
	    <tr>
		  <td width="36%"style="font-size:8px"></td>
	      <td width="53%" align="left" style="font-size:8px">Guaraníes $letras</td>
	      <td width="11%" align="right"style="font-size:8px"><b>$sumaTotalV</b></td>
	    </tr>
	</table>
    <table width="100%" style="text-align:left; line-height: 15px">
	    <tr>
	      <td width="36%" align='left' style="font-size:9px"></td>
	      <td width="20%" align='left' style="font-size:9px">$iva5V</td>
	      <td width="23%" align='left' style="font-size:9px">$iva10V</td>
	      <td width="11%" align='left' style="font-size:9px">$ivaTotal</td>
	    </tr>
	</table>
	<table width="100%" style="text-align:center; line-height: 15px">
	    <tr>
	      <td width="100%" align='center' style="font-size:10px"></td>
	    </tr>
	</table>
EOF;

$pdf->writeHTML($footer, false, false, false, false, '');

//DUPLICADO

$pdf->writeHTML($header, false, false, false, false, '');
$pdf->writeHTML($items, false, false, false, false, '');
$pdf->writeHTML($espacio, false, false, false, false, '');
$pdf->writeHTML($footer, false, false, false, false, '');

//TRIPLICADO

$pdf->writeHTML($header, false, false, false, false, '');
$pdf->writeHTML($items, false, false, false, false, '');
$pdf->writeHTML($espacio, false, false, false, false, '');
$pdf->writeHTML($footer, false, false, false, false, '');
$pdf->AddPage();
$pdf->writeHTML($header, false, false, false, false, '');

$items = "";
$cantidad=1;
$sumaTotal5 = 0;
$iva5 = 0;
$sumaTotal10 = 0;
$iva10 = 0;
$cantidad_total = 0;
$sumaTotal = 0;

}

if ($r->iva==5){
  $sumaTotal5 += ((($r->precio_venta*$r->cantidad)-($r->precio_venta*$r->cantidad*($r->descuento/100))));
  $iva5+=($r->precio_venta*$r->cantidad)-(($r->precio_venta*$r->cantidad)/1.05);
} 
else
{
  $sumaTotal10 += ((($r->precio_venta*$r->cantidad)-($r->precio_venta*$r->cantidad*($r->descuento/100))));
  $iva10+=($r->precio_venta*$r->cantidad)/11;
}

$cantidad_total += $r->cantidad;
$sumaTotal += (($r->precio_venta*$r->cantidad)-($r->precio_venta*$r->cantidad*($r->descuento/100)));

$items .= <<<EOF

		<table>
			<tr nowrap="nowrap" style="font-size:7px;">
		    	<td width="9%" align="rigth" >$r->codigo </td>
				<td width="4%" align="rigth" >$r->cantidad</td>
				<td width="36%" align="center">$r->producto</td>
				<td width="12%" align="right" >$subTotal</td>
				<td width="16%"></td>
				<td width="12%" align="right">$iva5P</td>
				<td width="12%" align="right">$iva10P</td>
			</tr>
		</table>

EOF;

}

if($cantidad<=12){
    
$pdf->writeHTML($items, false, false, false, false, '');

$c=12-$cantidad;


for($i=0;$i<$c;$i++){
    
$espacio .= <<<EOF

		<table>
			<tr nowrap="nowrap" style="font-size:7px;">
				<td width="7%"></td>
				<td width="44%"></td>
				<td width="12%"></td>
				<td width="12%"></td>
				<td width="12%"></td>
				<td width="12%"></td>
			</tr>
		</table>

EOF;
}

$pdf->writeHTML($espacio, false, false, false, false, '');


$letrasDecimal = "";
if($sumaTotal != intval($sumaTotal)){
    $decimal = ($sumaTotal - intval($sumaTotal))*100;
    $letrasDecimal = 'CON '.NumeroALetras::convertir($decimal).' CENTAVOS';
}
$letras = NumeroALetras::convertir($sumaTotal);
$sumaTotalV =  number_format($sumaTotal, 0, "," , ".");
$sumaTotal5V =  number_format($sumaTotal5, 0, "," , ".");
$sumaTotal10V =  number_format($sumaTotal10, 0, "," , ".");
$iva5V=number_format($iva5, 0, "," , ".");
$iva10V=number_format($iva10, 0, "," , ".");
$ivaTotal = number_format(($iva5 + $iva10), 0, "," , ".");
$footer = <<<EOF
	
	<table width="100%" style="text-align:center; line-height: 15px; font-size:8px">
	    
		<tr align="right">
		  <td width="5%"style="font-size:8px"></td>
		  <td width="65%"style="font-size:8px"></td>
	      <td width="12%" style="font-size:8px"><b>$sumaTotal5V</b></td>
	      <td width="19%"style="font-size:8px"><b>$sumaTotal10V</b></td>
	    </tr>
	    <tr>
		  <td width="36%"style="font-size:8px"></td>
	      <td width="53%" align="left" style="font-size:8px">Guaraníes $letras</td>
	      <td width="11%" align="right"style="font-size:8px"><b>$sumaTotalV</b></td>
	    </tr>
	</table>
    <table width="100%" style="text-align:left; line-height: 15px">
	    <tr>
	      <td width="36%" align='left' style="font-size:9px"></td>
	      <td width="20%" align='left' style="font-size:9px">$iva5V</td>
	      <td width="23%" align='left' style="font-size:9px">$iva10V</td>
	      <td width="11%" align='left' style="font-size:9px">$ivaTotal</td>
	    </tr>
	</table>
	<table width="100%" style="text-align:center; line-height: 15px">
	    <tr>
	      <td width="100%" align='center' style="font-size:12px"></td>
	    </tr>
	</table>
	
EOF;

$pdf->writeHTML($footer, false, false, false, false, '');

//DUPLICADO

$pdf->writeHTML($header, false, false, false, false, '');
$pdf->writeHTML($items, false, false, false, false, '');
$pdf->writeHTML($espacio, false, false, false, false, '');
$pdf->writeHTML($footer, false, false, false, false, '');

//TRIPLICADO

$pdf->writeHTML($header, false, false, false, false, '');
$pdf->writeHTML($items, false, false, false, false, '');
$pdf->writeHTML($espacio, false, false, false, false, '');
$pdf->writeHTML($footer, false, false, false, false, '');

}
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
ob_end_clean();
$pdf->Output('uin.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+
  ?>
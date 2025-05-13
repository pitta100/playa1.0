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

$medidas = array(210, 340); // Ajustar aquí según los milímetros necesarios
$pdf = new TCPDF('P', 'mm', $medidas, true, 'UTF-8', false); 
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

$pdf->AddPage();

$id = $_GET['id'];

$ingreso = $this->model->ListarVenta($id);

$meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

$dia = date("d", strtotime($ingreso->fecha));
$mes = date("m", strtotime($ingreso->fecha));
$anho = date("Y", strtotime($ingreso->fecha));

$monto = number_format($ingreso->monto, 0, ",", ".");

// Convertir a letras
$letras = NumeroALetras::convertir($ingreso->monto);

// Cálculo del IVA (10% del total)
$iva = $ingreso->monto * 0.10;
$iva = number_format($iva, 2, ',', '.'); // Darle formato a los números con decimales

// El total recibido es el monto más el IVA
$totalRecibido = $ingreso->monto + $iva;
$totalRecibido = number_format($totalRecibido, 2, ',', '.'); // Dar formato al total recibido

if($ingreso->forma_pago == "Efectivo "){
    $efectivo = "X";
    $cheque = "";
    $nroCheque = "";
    $banco = "";
}elseif($ingreso->forma_pago == "Cheque"){
    $efectivo = "";
    $cheque = "X";
    $nroCheque = $ingreso->comprobante;
    $banco = $ingreso->banco;
}

$mes = $meses[$mes-1];

// Diseño del recibo

$header = <<<EOF
    <table width="100%" style="text-align:center; line-height: 18px; font-size:9px; border: 1px solid #000; padding: 5px;">
        <tr>
            <td colspan="6" style="text-align:center; font-size:14px; font-weight:bold;">RECIBO DE PAGO</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:left; border-bottom: 1px solid #000;">FORMA DE PAGO: $ingreso->forma_pago</td>
            <td colspan="2" style="border-bottom: 1px solid #000; text-align:left;">RUC: $ingreso->ruc</td>
            <td colspan="2" style="border-bottom: 1px solid #000; text-align:left;">$ingreso->comprobante</td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom: 1px solid #000; text-align:left;">CORREO: $ingreso->correo</td>
            <td colspan="2" style="border-bottom: 1px solid #000; text-align:left;">FECHA: $dia-$mes-$anho</td>
            <td colspan="2" style="border-bottom: 1px solid #000; text-align:left;">TEL: $ingreso->telefono</td>
        </tr>
        <tr>
            <!-- Cambié el estilo de esta fila para poner el fondo negro y el texto blanco -->
            <td colspan="3" style="text-align:left; background-color: #000; color: #fff; font-weight: bold; border-bottom: 1px solid #000;">RECIBÍ DE:</td>
            <td colspan="3" style="text-align:left; background-color: #000; color: #fff; font-weight: bold; border-bottom: 1px solid #000;">POR CONCEPTO DE:</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:left; border-bottom: 1px solid #000;">$ingreso->nombre</td>
            <td colspan="3" style="text-align:left; border-bottom: 1px solid #000;">$ingreso->concepto</td>
        </tr>
        <tr>
            <!-- Modificado para que "LA SUMA DE:" y "QUINIENTOS MIL" estén en la misma línea -->
            <td colspan="3" style="text-align:left; background-color: #000; color: #fff; font-weight: bold; border-bottom: 1px solid #000; padding-right: 10px;">LA SUMA DE:</td>
            <td colspan="3" style="text-align:left; border-bottom: 1px solid #000;">$letras</td>
        </tr>
        <tr>
            <td colspan="3" style="border-bottom: 1px solid #000; text-align:left;">DIR: $ingreso->direccion</td>
            <td colspan="2" style="border-bottom: 1px solid #000; text-align:left;">MONEDA: GUARANIES</td>
            <td colspan="1" style="border-bottom: 1px solid #000; text-align:right;">TOTAL: $monto</td>
        </tr>
        <tr>
            <td colspan="3" style="border-bottom: 1px solid #000; text-align:left;">IVA (10%): $iva</td>
            <td colspan="2" style="border-bottom: 1px solid #000; text-align:left;">RETENCIONES (15%): $retenciones</td>
            <td colspan="1" style="border-bottom: 1px solid #000; text-align:right;">TOTAL RECIBIDO: $monto</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:center; border-bottom: 1px solid #000;">FIRMADO</td>
        </tr>
        <tr>
            <!-- Separamos las firmas con un espacio más grande -->
            <td style="text-align:center;background-color: #000; color: #fff; font-weight: bold; width: 45%;">Firma de Recibido</td>
            <td style="text-align:center; background-color: #000; color: #fff; font-weight: bold; width: 10%;">&nbsp;</td> <!-- Espacio vacío entre las dos firmas -->
            <td style="text-align:center; background-color: #000; color: #fff; font-weight: bold; width: 45%;">Firma de Entregado</td>
        </tr>
    </table>
EOF;




// Imprimir el primer ejemplar
$pdf->writeHTML($header, false, false, false, false, '');

// Crear espacio entre los dos ejemplares
$espacio = <<<EOF
    <p> </p>
EOF;
$pdf->writeHTML($espacio, false, false, false, false, '');

// Imprimir el segundo ejemplar
$pdf->writeHTML($header, false, false, false, false, '');


ob_end_clean();
$pdf->Output('uin.pdf', 'I');




//============================================================+
// END OF FILE
//============================================================+
  ?>
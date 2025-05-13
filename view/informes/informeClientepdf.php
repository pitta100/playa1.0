<?php 
require_once('plugins/tcpdf2/tcpdf.php');

// Obtener los datos del cliente por ID
$id = $_REQUEST['id'];
$cliente = $this->model->ObtenerClienteConVentas($id); // Esto nos devuelve tanto los datos del cliente como las ventas asociadas

// Crear nuevo PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('TuApp');
$pdf->SetAuthor('TuApp');
$pdf->SetTitle('Resumen de Cliente');
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(TRUE, 10); // Habilitar salto de página automático
$pdf->AddPage();

// Título de la empresa
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'P & Q AUTOMOTORES SA', 0, 1, 'C');  // Nombre de la empresa
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 8, 'Dirección: Super Carretera 123, Ciudad', 0, 1, 'C');  // Dirección de la empresa
$pdf->Cell(0, 8, 'Teléfono: (123) 456-7890', 0, 1, 'C');  // Teléfono de la empresa
$pdf->Cell(0, 8, 'Correo: contacto@empresa.com', 0, 1, 'C');  // Correo de la empresa
$pdf->Ln(10);

// Título del resumen del cliente
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Resumen del Cliente', 0, 1, 'C');
$pdf->Ln(5);

// Información básica del cliente
$pdf->SetFont('helvetica', '', 11);

// Si el cliente existe, extraemos la información básica
if ($cliente) {
    $info = [
        'RUC' => $cliente->ruc,
        'Nombre' => $cliente->nombre,
        'Correo' => $cliente->correo,
        'Teléfono' => $cliente->telefono,
        'Direccion Residencia ' => $cliente->residencia_url,
        'Dirección' => $cliente->direccion,
        'Dirección Laboral' => $cliente->adressWork,
        'Teléfono Laboral' => $cliente->phoneWork,
    ];

    // Información del cliente
    foreach ($info as $label => $valor) {
        $pdf->MultiCell(0, 8, "$label: $valor", 0, 1);
    }

    // Título de "Datos de Compra"
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'B', 13);
    $pdf->Cell(0, 10, 'Datos del producto comprado', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10); // Reducir tamaño de fuente

    // Comienza la tabla para mostrar las ventas
    $pdf->Ln(5);

    // Establecemos las cabeceras de la tabla con columnas más estrechas
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(25, 8, 'PROD', 1, 0, 'C');
    $pdf->Cell(25, 8, 'VALOR', 1, 0, 'C');
    $pdf->Cell(25, 8, 'COMPRO', 1, 0, 'C');
    $pdf->Cell(25, 8, 'NRO', 1, 0, 'C');
    $pdf->Cell(25, 8, 'F/COMPRA', 1, 0, 'C');
    $pdf->Cell(25, 8, 'METODO', 1, 0, 'C');
    $pdf->Cell(25, 8, 'FORMA', 1, 1, 'C'); // Nueva línea

    // Ahora iteramos sobre las ventas y mostramos cada una en una fila de la tabla
    $pdf->SetFont('helvetica', '', 9); // Reducir más el tamaño de la fuente

    // Verificamos si el cliente tiene ventas
    if (!empty($cliente->ventas)) {
        foreach ($cliente->ventas as $venta) {
            // Aquí vamos a mostrar los datos de cada venta
            $pdf->Cell(25, 8, $venta->id_producto, 1, 0, 'C');
            $pdf->Cell(25, 8, number_format($venta->precio_venta, 0), 1, 0, 'C');
            $pdf->Cell(25, 8, $venta->comprobante, 1, 0, 'C');
            $pdf->Cell(25, 8, $venta->nro_comprobante, 1, 0, 'C');
            $pdf->Cell(25, 8, date('d/m/Y', strtotime($venta->fecha_venta)), 1, 0, 'C'); // Formato de fecha
            $pdf->Cell(25, 8, $venta->metodo, 1, 0, 'C');
            $pdf->Cell(25, 8, $venta->contado, 1, 1, 'C'); // Nueva línea
        }
    } else {
        // Si no hay ventas, mostramos un mensaje
        $pdf->Cell(0, 8, 'No se encontraron ventas para este cliente.', 1, 1, 'C');
    }

    // Línea divisoria
    $pdf->Ln(3);
    $pdf->SetDrawColor(50, 50, 50);
    $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->getPageWidth() - 15, $pdf->GetY());
    $pdf->Ln(5);

    // Documentos adjuntos (si los hay)
    $pdf->SetFont('helvetica', 'B', 13);
    $pdf->Cell(0, 10, 'Datos Adjuntos del Cliente', 0, 1, 'C');

    $pdf->SetFont('helvetica', '', 11);

    $documentos = [
        'Comprobante de Ingreso' => $cliente->comprobanteIngreso,
        'Cédula Tributaria' => $cliente->cedulaTributaria,
        'Facturas Legales Emitidas' => $cliente->facturasLegalesEmitidas,
        'Cédula de Identidad' => $cliente->cedulaIdentidad,
         'Estructura Juridica' => $cliente->estructuraJuridica,
          'Beneficiario Final' => $cliente->beneficiarioFinal,
           'Otros Documentos' => $cliente->varios,
    ];

    foreach ($documentos as $label => $archivo) {
        if (!empty($archivo)) {
            $ruta = 'http://localhost/playa/assets/documentos/clientes/' . $archivo;
            $pdf->Write(8, "$label: ", '', false, 'L');
            $pdf->SetTextColor(0, 0, 255);
            $pdf->Write(8, $archivo, $ruta);
            $pdf->Ln();
            $pdf->SetTextColor(0, 0, 0);
        } else {
            $pdf->MultiCell(0, 8, "$label: No adjunto", 0, 1);
        }
    }

    // Línea divisoria
    $pdf->Ln(3);
    $pdf->SetDrawColor(50, 50, 50);
    $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->getPageWidth() - 15, $pdf->GetY());
    $pdf->Ln(5);
}

// Salida del PDF
$pdf->Output('cliente_resumen_' . $cliente->id . '.pdf', 'I');

?>

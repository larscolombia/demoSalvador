<?php
require 'db.php';
$id = $_GET['id'] ?? 0;
$stmt = $db->prepare('SELECT f.id, c.nombre, c.nit, f.fecha, f.total FROM facturas f JOIN clientes c ON f.cliente_id = c.id WHERE f.id=:id');
$stmt->bindValue(':id', $id);
$factura = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
if (!$factura) { exit('Factura no encontrada'); }


$itemsQuery = $db->query("SELECT producto, cantidad, precio FROM factura_items WHERE factura_id = $id");
$items = [];
while ($row = $itemsQuery->fetchArray(SQLITE3_ASSOC)) {
    $items[] = $row;
}

function pdf_escape($txt) {
    return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $txt);
}

function generar_pdf($factura, $items) {
    $lines = [];
    $lines[] = "Factura #" . $factura['id'];
    $lines[] = "Cliente: " . $factura['nombre'];
    $lines[] = "NIT: " . $factura['nit'];
    $lines[] = "Fecha: " . $factura['fecha'];
    $lines[] = '';
    foreach ($items as $it) {
        $lines[] = $it['producto'] . " - " . $it['cantidad'] . " x " . $it['precio'];
    }
    $lines[] = '';
    $lines[] = "Total: " . $factura['total'];

    $y = 750; // posicion inicial
    $stream = "BT\n/F1 12 Tf\n";
    foreach ($lines as $line) {
        $stream .= "50 " . $y . " Td (" . pdf_escape($line) . ") Tj\n";
        $y -= 14;
    }
    $stream .= "ET";
    $len = strlen($stream);

    $pdf = "%PDF-1.4\n";
    $objs = [];
    $objs[1] = "<< /Type /Catalog /Pages 2 0 R >>";
    $objs[2] = "<< /Type /Pages /Kids [3 0 R] /Count 1 >>";
    $objs[3] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>";
    $objs[4] = "<< /Length $len >>\nstream\n$stream\nendstream";
    $objs[5] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";

    $offsets = [];
    $pos = strlen($pdf);
    foreach ($objs as $i => $obj) {
        $offsets[$i] = $pos;
        $pdf .= "$i 0 obj\n$obj\nendobj\n";
        $pos = strlen($pdf);
    }
    $xref = strlen($pdf);
    $pdf .= "xref\n0 " . (count($objs)+1) . "\n";
    $pdf .= "0000000000 65535 f \n";
    foreach ($offsets as $off) {
        $pdf .= sprintf('%010d 00000 n ', $off) . "\n";
    }
    $pdf .= "trailer << /Root 1 0 R /Size " . (count($objs)+1) . " >>\nstartxref\n$xref\n%%EOF";

    return $pdf;
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="factura_' . $id . '.pdf"');

echo generar_pdf($factura, $items);


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
    $row['subtotal'] = $row['cantidad'] * $row['precio'];
    $items[] = $row;
}

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="factura_' . $id . '.csv"');

$out = fopen('php://output', 'w');

fputcsv($out, ["Factura #" . $factura['id']]);
fputcsv($out, ["Cliente", $factura['nombre']]);
fputcsv($out, ["NIT", $factura['nit']]);
fputcsv($out, ["Fecha", $factura['fecha']]);
fputcsv($out, []); // blank line
fputcsv($out, ['Producto', 'Cantidad', 'Precio', 'Subtotal']);
foreach ($items as $it) {
    fputcsv($out, [$it['producto'], $it['cantidad'], $it['precio'], number_format($it['subtotal'], 2)]);
}
fputcsv($out, []);
fputcsv($out, ['Total', '', '', number_format($factura['total'],2)]);

fclose($out);
?>

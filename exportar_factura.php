<?php
require 'db.php';
$id = $_GET['id'] ?? 0;
$stmt = $db->prepare('SELECT f.id, c.nombre, c.nit, f.fecha, f.total FROM facturas f JOIN clientes c ON f.cliente_id = c.id WHERE f.id=:id');
$stmt->bindValue(':id', $id);
$factura = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
if (!$factura) { exit('Factura no encontrada'); }

$items = $db->query("SELECT producto, cantidad, precio FROM factura_items WHERE factura_id = $id");
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="factura_'.$id.'.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['Factura #', $factura['id']]);
fputcsv($out, ['Cliente', $factura['nombre']]);
fputcsv($out, ['NIT', $factura['nit']]);
fputcsv($out, ['Fecha', $factura['fecha']]);
fputcsv($out, []);
fputcsv($out, ['Producto','Cantidad','Precio']);
while ($it = $items->fetchArray(SQLITE3_ASSOC)) {
    fputcsv($out, [$it['producto'], $it['cantidad'], $it['precio']]);
}
fputcsv($out, []);
fputcsv($out, ['Total', $factura['total']]);
?>

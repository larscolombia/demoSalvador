<?php
require 'db.php';
if (!empty($_POST['cliente_id'])) {
    $db->exec('BEGIN');
    $stmt = $db->prepare('INSERT INTO facturas(cliente_id, fecha, total) VALUES (:c, :f, :t)');
    $total = 0;
    foreach ($_POST['producto'] as $idx => $prod) {
        $cant = (float)$_POST['cantidad'][$idx];
        $precio = (float)$_POST['precio'][$idx];
        $total += $cant * $precio;
    }
    $stmt->bindValue(':c', $_POST['cliente_id']);
    $stmt->bindValue(':f', date('Y-m-d')); 
    $stmt->bindValue(':t', $total);
    $stmt->execute();
    $factura_id = $db->lastInsertRowID();

    $stmtItem = $db->prepare('INSERT INTO factura_items(factura_id, producto, cantidad, precio) VALUES (:f,:p,:c,:pr)');
    foreach ($_POST['producto'] as $idx => $prod) {
        if ($prod === '') continue;
        $stmtItem->bindValue(':f', $factura_id);
        $stmtItem->bindValue(':p', $prod);
        $stmtItem->bindValue(':c', $_POST['cantidad'][$idx]);
        $stmtItem->bindValue(':pr', $_POST['precio'][$idx]);
        $stmtItem->execute();
    }
    $db->exec('COMMIT');
}
header('Location: facturas.php');

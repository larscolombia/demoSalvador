<?php
require 'db.php';
$query = 'SELECT f.id, c.nombre, f.fecha, f.total FROM facturas f JOIN clientes c ON f.cliente_id = c.id ORDER BY f.id DESC';
$facturas = $db->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturas</title>
</head>
<body>
    <h1>Listado de facturas</h1>
    <table border="1">
        <tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Acciones</th></tr>
        <?php while ($f = $facturas->fetchArray()) : ?>
        <tr>
            <td><?=$f['id']?></td>
            <td><?=$f['nombre']?></td>
            <td><?=$f['fecha']?></td>
            <td><?=number_format($f['total'],2)?></td>
            <td><a href="exportar_factura.php?id=<?=$f['id']?>">Exportar</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p><a href="index.php">Volver al inicio</a></p>
</body>
</html>

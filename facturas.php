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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h1 class="mb-4">Listado de facturas</h1>
    <table class="table table-bordered">
        <tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Acciones</th></tr>
        <?php while ($f = $facturas->fetchArray()) : ?>
        <tr>
            <td><?=$f['id']?></td>
            <td><?=$f['nombre']?></td>
            <td><?=$f['fecha']?></td>
            <td><?=number_format($f['total'],2)?></td>
            <td><a href="exportar_factura.php?id=<?=$f['id']?>" class="btn btn-sm btn-outline-secondary">Exportar PDF</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a class="btn btn-secondary" href="index.php">Volver al inicio</a>
</body>
</html>

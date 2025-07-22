<?php
require 'db.php';
if (!empty($_POST)) {
    $stmt = $db->prepare('INSERT INTO clientes(nombre, nit, telefono, correo) VALUES (:n,:ni,:t,:c)');
    $stmt->bindValue(':n', $_POST['nombre']);
    $stmt->bindValue(':ni', $_POST['nit']);
    $stmt->bindValue(':t', $_POST['telefono']);
    $stmt->bindValue(':c', $_POST['correo']);
    $stmt->execute();
    header('Location: clientes.php');
    exit;
}
$clientes = $db->query('SELECT * FROM clientes');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h1 class="mb-4">Registrar cliente</h1>
    <form method="post" class="mb-4">
        <div class="mb-3">
            <label class="form-label">Nombre
                <input type="text" name="nombre" class="form-control" required>
            </label>
        </div>
        <div class="mb-3">
            <label class="form-label">NIT
                <input type="text" name="nit" class="form-control" required>
            </label>
        </div>
        <div class="mb-3">
            <label class="form-label">Teléfono
                <input type="text" name="telefono" class="form-control">
            </label>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo
                <input type="email" name="correo" class="form-control">
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
    <h2 class="mb-3">Clientes registrados</h2>
    <ul class="list-group mb-3">
    <?php while ($c = $clientes->fetchArray()) : ?>
        <li class="list-group-item"><?="{$c['nombre']} - {$c['nit']}"?></li>
    <?php endwhile; ?>
    </ul>
    <a class="btn btn-secondary" href="index.php">Volver al inicio</a>
</body>
</html>

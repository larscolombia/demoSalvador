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
</head>
<body>
    <h1>Registrar cliente</h1>
    <form method="post">
        <label>Nombre: <input type="text" name="nombre" required></label><br>
        <label>NIT: <input type="text" name="nit" required></label><br>
        <label>Teléfono: <input type="text" name="telefono"></label><br>
        <label>Correo: <input type="email" name="correo"></label><br>
        <button type="submit">Guardar</button>
    </form>
    <h2>Clientes registrados</h2>
    <ul>
    <?php while ($c = $clientes->fetchArray()) : ?>
        <li><?="{$c['nombre']} - {$c['nit']}"?></li>
    <?php endwhile; ?>
    </ul>
    <p><a href="index.php">Volver al inicio</a></p>
</body>
</html>

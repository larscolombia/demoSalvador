<?php
require 'db.php';
$clientes = $db->query('SELECT * FROM clientes');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva factura</title>
    <script>
    function calcularTotal() {
        let total = 0;
        for (let i = 1; i <= 5; i++) {
            const cant = parseFloat(document.getElementById('cantidad'+i).value) || 0;
            const precio = parseFloat(document.getElementById('precio'+i).value) || 0;
            total += cant * precio;
        }
        document.getElementById('total').textContent = total.toFixed(2);
    }
    </script>
</head>
<body>
    <h1>Crear factura</h1>
    <form method="post" action="guardar_factura.php" oninput="calcularTotal()">
        <label>Cliente:
            <select name="cliente_id" required>
                <option value="">Seleccione...</option>
                <?php while ($c = $clientes->fetchArray()) : ?>
                    <option value="<?=$c['id']?>"><?=$c['nombre']?></option>
                <?php endwhile; ?>
            </select>
        </label>
        <h2>Productos</h2>
        <table border="1">
            <tr><th>Producto</th><th>Cantidad</th><th>Precio</th></tr>
            <?php for ($i=1;$i<=5;$i++): ?>
            <tr>
                <td><input type="text" name="producto[]"></td>
                <td><input type="number" step="any" name="cantidad[]" id="cantidad<?=$i?>"></td>
                <td><input type="number" step="any" name="precio[]" id="precio<?=$i?>"></td>
            </tr>
            <?php endfor; ?>
        </table>
        <p>Total: <span id="total">0.00</span></p>
        <button type="submit">Guardar factura</button>
    </form>
    <p><a href="index.php">Volver al inicio</a></p>
</body>
</html>

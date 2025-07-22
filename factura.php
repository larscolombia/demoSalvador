<?php
require 'db.php';
$clientes = $db->query('SELECT * FROM clientes');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva factura</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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

<body class="container py-4">
    <h1 class="mb-4">Crear factura</h1>
    <form method="post" action="guardar_factura.php" oninput="calcularTotal()" class="mb-4">
        <div class="mb-3">
            <label class="form-label">Cliente
                <select name="cliente_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <?php while ($c = $clientes->fetchArray()) : ?>
                        <option value="<?=$c['id']?>"><?=$c['nombre']?></option>
                    <?php endwhile; ?>
                </select>
            </label>
        </div>
        <h2 class="h4">Productos</h2>
        <table class="table table-bordered">
            <tr><th>Producto</th><th>Cantidad</th><th>Precio</th></tr>
            <?php for ($i=1;$i<=5;$i++): ?>
            <tr>
                <td><input type="text" name="producto[]" class="form-control"></td>
                <td><input type="number" step="any" name="cantidad[]" id="cantidad<?=$i?>" class="form-control"></td>
                <td><input type="number" step="any" name="precio[]" id="precio<?=$i?>" class="form-control"></td>
            </tr>
            <?php endfor; ?>
        </table>
        <p class="fw-bold">Total: <span id="total">0.00</span></p>
        <button type="submit" class="btn btn-primary">Guardar factura</button>
    </form>
    <a class="btn btn-secondary" href="index.php">Volver al inicio</a>

</body>
</html>

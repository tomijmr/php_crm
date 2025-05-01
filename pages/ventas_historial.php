<?php
session_start();
require_once "../includes/db.php";
require_once "../includes/auth.php";

// Filtro de fechas
$fecha_inicio = $_GET['inicio'] ?? date('Y-m-01');
$fecha_fin = $_GET['fin'] ?? date('Y-m-d');

$stmt = $conn->prepare("
    SELECT v.id AS venta_id, v.fecha, p.nombre, vd.cantidad, vd.precio_unitario
    FROM ventas v
    JOIN ventas_detalle vd ON v.id = vd.venta_id
    JOIN productos p ON vd.producto_id = p.id
    WHERE DATE(v.fecha) BETWEEN ? AND ?
    ORDER BY v.fecha DESC
");
$stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
$stmt->execute();
$resultado = $stmt->get_result();

$ventas = [];
while ($row = $resultado->fetch_assoc()) {
    $ventas[$row['venta_id']]['fecha'] = $row['fecha'];
    $ventas[$row['venta_id']]['items'][] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Ventas</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Reset básico */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f9;
    padding: 20px;
    color: #333;
}

/* Títulos */
h1, h2, h3, h4 {
    margin-bottom: 10px;
    color: #2c3e50;
}

/* Formularios */
form {
    margin-bottom: 30px;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

input[type="text"],
input[type="number"],
input[type="password"],
input[type="date"],
button {
    display: block;
    width: 100%;
    max-width: 300px;
    margin-top: 10px;
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

button {
    background-color: #3498db;
    color: white;
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #2980b9;
}

/* Tablas */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #ecf0f1;
}

tr:hover {
    background-color: #f0f9ff;
}

/* Navegación */
nav ul {
    list-style: none;
    margin-top: 20px;
}

nav ul li {
    margin-bottom: 10px;
}

nav a {
    text-decoration: none;
    color: #3498db;
    font-weight: bold;
}

nav a:hover {
    text-decoration: underline;
}

/* Mensajes */
p {
    margin-top: 10px;
}

.success {
    background-color: #dff0d8;
    color: #3c763d;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
}

    </style>
</head>
<body>
    <h2>Historial de Ventas</h2>

    <form method="get">
        <label>Desde: <input type="date" name="inicio" value="<?= $fecha_inicio ?>"></label>
        <label>Hasta: <input type="date" name="fin" value="<?= $fecha_fin ?>"></label>
        <button type="submit">Filtrar</button>
    </form>

    <?php if (empty($ventas)): ?>
        <p>No se encontraron ventas en ese período.</p>
    <?php else: ?>
        <?php foreach ($ventas as $venta_id => $venta): ?>
            <hr>
            <h4>Venta #<?= $venta_id ?> - <?= date("d/m/Y H:i", strtotime($venta['fecha'])) ?></h4>
            <table border="1">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
                <?php
                    $total = 0;
                    foreach ($venta['items'] as $item):
                        $subtotal = $item['cantidad'] * $item['precio_unitario'];
                        $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                        <td>$<?= number_format($item['precio_unitario'], 2) ?></td>
                        <td>$<?= number_format($subtotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>$<?= number_format($total, 2) ?></strong></td>
                </tr>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>

    <p><a href="dashboard.php">Volver al Dashboard</a></p>
</body>
</html>

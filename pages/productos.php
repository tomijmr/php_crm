<?php
session_start();
require_once "../includes/db.php";
require_once "../includes/auth.php"; // Verifica que el usuario esté logueado

// Agregar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar'])) {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("INSERT INTO productos (codigo, nombre, precio, stock) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $codigo, $nombre, $precio, $stock);
    $stmt->execute();
    header("Location: productos.php");
    exit;
}

// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: productos.php");
    exit;
}

// Obtener productos
$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
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
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
    color: #333;
}

.container {
    width: 100%;
    max-width: 800px;
    margin: auto;
}

/* Títulos */
h1, h2, h3, h4 {
    margin-bottom: 20px;
    color: #2c3e50;
    text-align: center;
}

/* Formularios */
form {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

input[type="text"],
input[type="number"],
input[type="password"],
input[type="date"],
button {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
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
    text-align: center;
}

tr:hover {
    background-color: #f0f9ff;
}

/* Navegación */
nav ul {
    list-style: none;
    padding: 0;
    text-align: center;
}

nav ul li {
    margin-bottom: 10px;
}

nav a {
    text-decoration: none;
    color: #3498db;
    font-weight: bold;
    font-size: 18px;
}

nav a:hover {
    text-decoration: underline;
}

/* Mensajes */
p {
    margin-top: 10px;
    text-align: center;
}

.success {
    background-color: #dff0d8;
    color: #3c763d;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: center;
}

    </style>
</head>
<body>
    <div class="container">
    <h2>Gestión de Productos</h2>

    <form method="post">
        <h3>Agregar nuevo producto</h3>
        <input type="text" name="codigo" placeholder="Código" required>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="number" step="0.01" name="precio" placeholder="Precio" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <button type="submit" name="agregar">Agregar</button>
    </form>

    <h3>Lista de productos</h3>
    <table border="1">
        <tr>
            <th>Código</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Acción</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['codigo']) ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= number_format($row['precio'], 2) ?></td>
            <td><?= $row['stock'] ?></td>
            <td><a href="?eliminar=<?= $row['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">Eliminar</a></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <p><a href="dashboard.php">Volver al Dashboard</a></p>

</div>
</body>
</html>

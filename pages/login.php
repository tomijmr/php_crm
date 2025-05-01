<?php
session_start();
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    $stmt = $conn->prepare("SELECT id, clave FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();
        if (password_verify($clave, $hash)) {
            $_SESSION["usuario_id"] = $id;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Clave incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!-- HTML de login -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
        <center>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="post">
        <label>Usuario:</label><br>
        <input type="text" name="usuario" required><br>
        <label>Clave:</label><br>
        <input type="password" name="clave" required><br>
        <button type="submit">Ingresar</button>
    </form></center>
</div>
</body>
</html>

<?php
require_once "includes/db.php";

$usuario = 'toto';
$clave = password_hash('1234', PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuarios (usuario, clave) VALUES (?, ?)");
$stmt->bind_param("ss", $usuario, $clave);
$stmt->execute();
echo "Usuario creado.";
?>

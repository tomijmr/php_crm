<?php
$host = "localhost";
$db = "c2611613_crm_db";
$user = "c2611613";
$pass = "SI42dakize";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

<?php
$host = "localhost";
$db = "crm_db";
$user = "c2611613_gescred";
$pass = "SI42dakize";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

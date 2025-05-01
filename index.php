<?php
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION["usuario_id"])) {
    header("Location: pages/dashboard.php");
    exit;
}

// Si no, redirigir al login
header("Location: pages/login.php");
exit;

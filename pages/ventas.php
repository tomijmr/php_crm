<?php
session_start();
require_once "../includes/db.php";
require_once "../includes/auth.php";

// Obtener lista de productos
$productos = $conn->query("SELECT * FROM productos ORDER BY nombre ASC")->fetch_all(MYSQLI_ASSOC);
// $productoJSON = htmlspecialchars(json_encode($prod), ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Venta</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .producto {
            display: flex;
            justify-content: center;
            padding: 10px;
            margin-bottom: 5px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .carrito-item {
            display: flex;
            justify-content: space-between;
            background: #e8f5e9;
            margin-bottom: 5px;
            padding: 10px;
            border-radius: 5px;
        }

        .btn {
            padding: 5px 10px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #27ae60;
        }

        #total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            text-align: right;
        }

        input[type="search"] {
            margin-bottom: 15px;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #aaa;
        }
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

.producto strong {
    padding-right: 20px;
}

    </style>
</head>
<body>
<div class="container">
    <h2>Registrar Venta</h2>

    <!-- 🧺 Carrito -->
    <div id="carrito-container">
        <h3>Carrito</h3>
        <div id="carrito"></div>
        <p id="total">Total: $0.00</p>
        <form method="post" onsubmit="return finalizarVenta();" id="venta-form">
            <input type="hidden" name="json" id="jsonCarrito">
            <button type="submit" class="btn">Finalizar Venta</button>
        </form>
    </div>

    <hr>

    <!-- 🔎 Buscador -->
    <input type="search" id="buscador" placeholder="Buscar por nombre o código...">

    <!-- 📦 Lista de Productos -->
    <div id="lista-productos">
        <?php foreach ($productos as $prod): ?>
            <div class="producto" data-nombre="<?= strtolower($prod['nombre']) ?>" data-codigo="<?= strtolower($prod['codigo']) ?>">
                <!-- <strong><?= htmlspecialchars($prod['nombre']) ?></strong>
                <span>Código: <?= htmlspecialchars($prod['codigo']) ?> | Stock: <?= $prod['stock'] ?> | $<?= number_format($prod['precio'], 2) ?></span> -->
                <div class="producto"
                    data-id="<?= $prod['id'] ?>"
                    data-nombre="<?= htmlspecialchars($prod['nombre']) ?>"
                    data-precio="<?= $prod['precio'] ?>"
                    data-stock="<?= $prod['stock'] ?>"
                    data-codigo="<?= strtolower($prod['codigo']) ?>">
                    <strong><?= htmlspecialchars($prod['nombre']) ?></strong>
                    <span>  Código: <?= htmlspecialchars($prod['codigo']) ?> | Stock: <?= $prod['stock'] ?> | $<?= number_format($prod['precio'], 2) ?></span>
                    <button class="btn agregar-btn">Agregar</button>
                </div>


            </div>
        <?php endforeach; ?>
    </div>

    <p><a href="dashboard.php">← Volver al Dashboard</a></p>
</div>

<!-- 💻 JS para manejar carrito -->
<script>
    let carrito = [];

    function agregarAlCarrito(producto) {
        const cantidad = 1;
        const existe = carrito.find(p => p.id === producto.id);
        if (existe) {
            existe.cantidad += cantidad;
        } else {
            producto.cantidad = cantidad;
            carrito.push(producto);
        }
        renderCarrito();
    }

    function eliminarDelCarrito(id) {
        carrito = carrito.filter(p => p.id !== id);
        renderCarrito();
    }

    function renderCarrito() {
        const container = document.getElementById("carrito");
        container.innerHTML = "";
        let total = 0;

        carrito.forEach(p => {
            const subtotal = p.cantidad * p.precio;
            total += subtotal;
            container.innerHTML += `
                <div class="carrito-item">
                    ${p.nombre} - ${p.cantidad} x $${p.precio.toFixed(2)} = $${subtotal.toFixed(2)}
                    <button onclick="eliminarDelCarrito(${p.id})" class="btn" style="background:#e74c3c;">X</button>
                </div>
            `;
        });

        document.getElementById("total").innerText = "Total: $" + total.toFixed(2);
        document.getElementById("jsonCarrito").value = JSON.stringify(carrito);
    }

    function finalizarVenta() {
        if (carrito.length === 0) {
            alert("El carrito está vacío.");
            return false;
        }
        return true;
    }

    // 🔍 Buscador
    document.getElementById("buscador").addEventListener("input", function () {
        const filtro = this.value.toLowerCase();
        document.querySelectorAll(".producto").forEach(el => {
            const nombre = el.dataset.nombre;
            const codigo = el.dataset.codigo;
            el.style.display = (nombre.includes(filtro) || codigo.includes(filtro)) ? "flex" : "none";
        });
    });

    // Delegar clicks en todos los botones "Agregar"
document.querySelectorAll('.agregar-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const parent = this.parentElement;

        const producto = {
            id: parseInt(parent.dataset.id),
            nombre: parent.dataset.nombre,
            precio: parseFloat(parent.dataset.precio),
            stock: parseInt(parent.dataset.stock),
            cantidad: 1
        };

        agregarAlCarrito(producto);
    });
});

</script>

<?php
// 🧾 Lógica para guardar la venta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['json'])) {
    $items = json_decode($_POST['json'], true);

    if ($items && is_array($items)) {
        $conn->begin_transaction();
        try {
            $conn->query("INSERT INTO ventas (fecha) VALUES (NOW())");
            $venta_id = $conn->insert_id;

            $stmt = $conn->prepare("INSERT INTO ventas_detalle (venta_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
            $update = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");

            foreach ($items as $item) {
                $stmt->bind_param("iiid", $venta_id, $item['id'], $item['cantidad'], $item['precio']);
                $stmt->execute();

                $update->bind_param("ii", $item['cantidad'], $item['id']);
                $update->execute();
            }

            $conn->commit();
            echo "<script>alert('¡Venta registrada con éxito!'); window.location.href = 'ventas.php';</script>";
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            echo "<p>Error al registrar la venta: " . $e->getMessage() . "</p>";
        }
    }
}
?>
</body>
</html>

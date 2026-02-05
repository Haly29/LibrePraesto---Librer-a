<?php
session_start();
include('../../Config/conexion.php');

$conexion = new connect();
$conn = $conexion->getConnection();

// Verificar si hay un usuario autenticado
$nombre_usuario = $_SESSION['libreria'] ?? null;

if (!$nombre_usuario) {
    header("Location: login_cliente.php");
    exit();
}

// Obtener el id_cliente basado en el nombre de usuario
$sql_cliente = "SELECT id_cliente FROM clientes WHERE usuario = ?";
$stmt_cliente = $conn->prepare($sql_cliente);
$stmt_cliente->bind_param('s', $nombre_usuario);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();
$row_cliente = $result_cliente->fetch_assoc();

if (!$row_cliente) {
    // Si no se encuentra el cliente, redirige al login
    header("Location: login_cliente.php");
    exit();
}

$id_cliente = $row_cliente['id_cliente'];

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Manejo de formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Actualizar cantidad de libros
    if (isset($_POST['actualizar_cantidad'], $_POST['id_libro'], $_POST['cantidad_disponible'])) {
        $libro_id = intval($_POST['id_libro']);
        $cantidad = max(1, intval($_POST['cantidad_disponible']));
        $_SESSION['carrito'][$libro_id] = $cantidad;
    }

    // Eliminar libro del carrito
    if (isset($_POST['eliminar_id'])) {
        $eliminar_id = intval($_POST['eliminar_id']);
        unset($_SESSION['carrito'][$eliminar_id]);
    }

    // Confirmar compra
    if (isset($_POST['confirmar_compra']) && !empty($_SESSION['carrito'])) {
        $sucursal = intval($_POST['sucursal']); // Sucursal seleccionada
        $fecha_venta = date('Y-m-d H:i:s');

        // Preparar consulta para insertar la compra
        $stmt = $conn->prepare("INSERT INTO compras (cliente_id, libro_id, cantidad, sucursal_id, precio_total, fecha_compra) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($_SESSION['carrito'] as $libro_id => $cantidad) {
            $sql = "SELECT precio FROM libros WHERE id_libro = ?";
            $precio_stmt = $conn->prepare($sql);
            $precio_stmt->bind_param('i', $libro_id);
            $precio_stmt->execute();
            $precio_result = $precio_stmt->get_result();
            $precio_row = $precio_result->fetch_assoc();
        
            if ($precio_row) {
                $precio_unitario = $precio_row['precio'];
                $subtotal = $precio_unitario * $cantidad;
        
                // Insertar la compra en la base de datos
                $stmt->bind_param('iiidss', $id_cliente, $libro_id, $cantidad, $sucursal, $subtotal, $fecha_venta);
                if ($stmt->execute()) {
                    echo "Compra registrada correctamente.";
                } else {
                    echo "Error al registrar la compra.";
                }
            }
        }

        $stmt->close();

        // Vaciar el carrito después de la compra
        $_SESSION['carrito'] = [];
        header("Location: ../Vistas/HistorialCompra.php");
        exit();
    }

    // Redirigir después de actualizar/eliminar libros
    header("Location: carrito.php");
    exit();
}

// Mostrar los libros del carrito
$carrito = $_SESSION['carrito'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" type="text/css" href="../../Public/CSS/normalize.css">
	<link rel="stylesheet" type="text/css" href="../../Public/CSS/estilo.css">
</head>
<body>
            <!-- Encabezado de la página -->
		<header>
			<div class="logo_menu">
				<img src="../../Public/Imagenes/Logo.png" alt="Libre Praesto Logo">
			</div>
			<!-- Menú de navegación -->
			<nav>
				<ul>
					<li><a href="../Vistas/inicio.php">Inicio</a></li>
					<li><a href="../Vistas/carrito.php">Carrito</a></li>
					<li><a href="../Vistas/HistorialCompra.php">Historial de Compra</a></li>
					<li><a href="../Vistas/soporte.php">Soporte</a></li>

					<li>
						<a href="../Vistas/Catalogo.php" class="btn-pedir-libro">Catálogo</a>
					</li>
				</ul>
			</nav>
		</header>
    <main class="carrito">
        <h1 class="page-title">Carrito de Compras</h1>
        <?php
        if (!empty($carrito)) {
            $ids = implode(',', array_map('intval', array_keys($carrito)));
            $total_compra = 0;

            $sql = "SELECT * FROM libros WHERE id_libro IN ($ids)";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $libro_id = $row['id_libro'];
                    $cantidad = $carrito[$libro_id];
                    $subtotal = $row['precio'] * $cantidad;
                    $total_compra += $subtotal;
        ?>
                    <div class="carrito-item">
                        <img src="../../Public/Imagenes/<?= htmlspecialchars($row['imagen_ubicacion'] . $row['imagen_original']) ?>" alt="Portada del libro" class="portada-carrito">
                        <div class="carrito-content">
                            <h2><?= htmlspecialchars($row['nombre']) ?></h2>
                            <p><?= htmlspecialchars($row['descripcion']) ?></p>
                            <p id="precio--carrito">Precio: $<?= number_format($row['precio'], 2) ?></p>
                            <form method="post" action="carrito.php">
                                <div id="cantidad">
                                    <label for="cantidad_<?= $libro_id ?>">Cantidad:</label>
                                    <input type="number" name="cantidad_disponible" value="<?= $cantidad ?>" min="1">
                                    <input type="hidden" name="id_libro" value="<?= $libro_id ?>">
                                    <button class="button_book" type="submit" name="actualizar_cantidad">Actualizar</button>
                                </div>
                        </div>
                                <button id="buttons-carrito" type="submit" name="eliminar_id" value="<?= $libro_id ?>">Eliminar</button>
                            </form>
                        
                    </div>                   
        <?php
                }
        ?>
                <div class="total-compra">
                    <h2>Total de la compra: $<?= number_format($total_compra, 2) ?></h2>
                    <form method="post" action="carrito.php">
                        <label for="sucursal">Seleccionar sucursal:</label>
                        <select name="sucursal" id="sucursal" required>
                            <option value="1"<?php echo isset($stmt['sucursal_id']) && $stmt['sucursal_id'] ==1 ?'selected': ''; ?>>Central</option>
                            <option value="2"<?php echo isset($stmt['sucursal_id']) && $stmt['sucursal_id'] ==2 ?'selected': ''; ?>>Este</option>
                        </select>
                        <button id="button-confirmar" type="submit" name="confirmar_compra">Confirmar Compra</button>
                    </form>
                </div>

        <?php
            } else {
                echo "<p>No se encontraron libros en el carrito.</p>";
            }
        } else {
            echo "<p>El carrito está vacío.</p>";
        }
        ?>
    </main>
        <!-- Pie de página -->
        <footer>
			<div class="logo_footer">
				<img src="../../Public/Imagenes/Logo.png" alt="Libre Praesto Logo">
			</div>
            <nav id="PiePag">
                <ul id="Submenu">
                    <li class="LinkSubmenu"><a href="../Vistas/inicio.php">Inicio</a></li>
                    <li class="LinkSubmenu"><a href="../Vistas/carrito.php">Carrito</a></li>
                    <li class="LinkSubmenu"><a href="../Vistas/HistorialCompra.php">Historial de Compra</a></li>
                    <li class="LinkSubmenu"><a href="../Vistas/soporte.php">Soporte</a></li>
                </ul>
				<a href="../../App/Modelos/modulo_salir.php" class="logout-link">Cerrar sesión</a>
				<p id="copyright">© 2024 Libre Praesto. Todos los derechos reservados.</p>	
			</nav>
		</footer>
</body>
</html>
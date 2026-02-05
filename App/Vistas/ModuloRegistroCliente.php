<?php
session_start();

if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: ../../App/Vistas/login_administrador.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'];

require_once '../../Config/conexion.php';
require_once '../Modelos/clase_cliente.php';

$database = new connect();
$clientes = new Cliente($database);

$sucursales = $clientes->getSucursales();

$sucursal_id = isset($_GET['sucursal']) ? $_GET['sucursal'] : null;
// Obtener los datos filtrados
$records_per_page = 4;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$result = $clientes->getClientes($search, $page, $records_per_page, $sucursal_id);
$total_records = $clientes->getTotalRecords($search, $sucursal_id);
$total_pages = ceil($total_records / $records_per_page);
$offset = ($page - 1) * $records_per_page;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libre Praesto - Modulo Registro Cliente</title>
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
                <li><a href="../Vistas/MenuAdmin.php">Menú</a></li>
                <li><a href="../Vistas/ModuloUsuario.php">Usuario</a></li>
                <li><a href="../Vistas/ModuloRegistroCliente.php">Registro cliente</a></li>
                <li><a href="../Vistas/ModuloDeVentas.php">Ventas</a></li>
                <li><a href="../Vistas/modulo_inventario.php">Inventario</a></li>
                <li><a href="../Vistas/modulo_libros.php">Libros</a></li>
                <li>
                    <a class="btn-pedir-libro"><?php echo $nombre_usuario ?></a>
                </li>
			</ul>
		</nav>
	</header>

    <!-- Sección principal con un banner -->
    <main id="Titulo--banner">
        <img src="../../Public/Imagenes/banner de inicio.jpeg" alt="Banner de la página soporte">
        <div class="Cuadrado_titutlo--banner">
            <p>Cliente</p>
        </div>
	</main>

    <div class="content">
        <h1 class="page-title">Modulo Registro Cliente</h1>
        <div id="cliente-container">
            <div class="search-container">
                <form method="GET" action="">
                    <input type="text" name="search" class="search-box" 
                        value="<?php echo htmlspecialchars($search); ?>" 
                        placeholder="Buscar por el primer nombre o cedula...">
                </form>
            </div>

            <div id="filtro-cliente">
                <label for="sucursal">Sucursal</label>
                <select id="sucursal" name="sucursal" onchange="cargarFiltro(this.value)">
                    <option value="">Todas las sucursales</option>
                    <?php
                        if ($sucursales) {
                            while ($row = $sucursales->fetch_assoc()) {
                                $selected = ($row['sucursal_id'] == $sucursal_id) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row['sucursal_id']) . '" ' . $selected . '>' . 
                                htmlspecialchars($row['nombre']) . '</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>

        <table id="Tabla-Usuario">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Primer Nombre</th>
                    <th>Segundo Nombre</th>
                    <th>Primer Apellidp</th>
                    <th>Segundo Apellido</th>
                    <th>Fecha Nacimiento</th>
                    <th>Sucursal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $count = $offset + 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['cip']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['primer_nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['segundo_nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['primer_apellido']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['segundo_apellido']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_nacimiento']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td><a href='../Vistas/EditarCliente.php?id_cliente=" . htmlspecialchars($row['id_cliente']) . "' class='button-editar'>Editar</a></td>";
                        echo "</tr>";
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron registros</td></tr>";
                }
                ?>
            </tbody>
            <script>
            function cargarFiltro(sucursalId) {
                // Obtener el valor actual de búsqueda
                var searchValue = document.querySelector('input[name="search"]').value;
                
                // Construir la URL con los parámetros
                var url = '?';
                if (searchValue) {
                    url += 'search=' + encodeURIComponent(searchValue) + '&';
                }
                if (sucursalId) {
                    url += 'sucursal=' + encodeURIComponent(sucursalId);
                }
                
                // Redirigir a la página con los filtros
                window.location.href = url;
            }
            </script>
        </table>

        <div class="pagination">
            <?php
            for ($i = 1; $i <= $total_pages; $i++) {
                $url_params = [];
                if (!empty($search)) {
                    $url_params[] = "search=$search";
                }
                if (!empty($sucursal_id)) {
                    $url_params[] = "sucursal=$sucursal_id";
                }
                $url_params[] = "page=$i";
                
                $url = '?' . implode('&', $url_params);
                
                echo "<a href='$url'" . 
                     ($page == $i ? " class='active'" : "") . ">$i</a>";
            }
            ?>
        </div>
    </div>
    <!-- Pie de página -->
    <footer>
		<div class="logo_footer">
			<img src="../../Public/Imagenes/Logo.png" alt="Libre Praesto Logo">
		</div>
        <nav id="PiePag">
		    <a href="../../App/Modelos/modulo_salir.php" class="logout-link">Cerrar sesión</a>
			<p id="copyright">© 2024 Libre Praesto. Todos los derechos reservados.</p>	
		</nav>
	</footer>
    <script>
        // Prevenir navegación atrás después de logout
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
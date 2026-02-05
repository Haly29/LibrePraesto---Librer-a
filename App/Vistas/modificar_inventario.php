<?php
require_once '../Modelos/clase_inventario.php';

$inventario = new ClaseInventario();

// Verificar si se recibió un código
if (isset($_GET['codigo'])) {
    $codigoLibro = $_GET['codigo'];
    $datosLibro = $inventario->obtenerLibroPorCodigo($codigoLibro);
} else {
    die("Código de libro no proporcionado.");
}

// Procesar la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datosLibro = [
        'codigo' => $_POST['codigo'],
        'nombre' => $_POST['nombre'],
        'categoria' => $_POST['categoria'],
        'autor' => $_POST['autor'],
        'precio' => $_POST['precio'],
    ];
    $datosInventario = [
        'sucursal_id' => $_POST['sucursal'],
        'cantidad_disponible' => $_POST['cantidad_disponible']
    ];

    try {
        $inventario->actualizarLibroInventario($datosLibro, $datosInventario);
        echo "¡Libro e inventario actualizados correctamente!";
        header("Location: modulo_inventario.php");
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Inventario</title>
    <link rel="stylesheet" type="text/css" href="../../Public/CSS/normalize.css">
    <link rel="stylesheet" type="text/css" href="../../Public/CSS/estilo.css">
</head>
<body id="Background">
<section class="registro flex flex--row flex--center">
<article id="cuadrado--Registrocliente">
    <h1 id="Subtítulo">Modificar Inventario</h1>
    <form class="flex flex--column" method="POST">
    <div class="input-cliente">
        <label class="input-label-Regcliente">Código:</label>
        <input class="input-Regcliente" type="text" name="codigo" value="<?= htmlspecialchars($datosLibro['codigo']) ?>" readonly><br>
    </div>
    <div class="input-cliente">
        <label class="input-label-Regcliente">Nombre:</label>
        <input class="input-Regcliente" type="text" name="nombre" value="<?= htmlspecialchars($datosLibro['nombre']) ?>" required><br>
    </div>
    <div class="input-cliente">
        <label class="input-label-Regcliente">Categoría:</label>
        <select class="regis-pos" name="categoria">
            <option value="fantasia" <?= $datosLibro['categoria'] == 'fantasia' ? 'selected' : '' ?>>Fantasía</option>
            <option value="terror" <?= $datosLibro['categoria'] == 'terror' ? 'selected' : '' ?>>Terror</option>
            <option value="novela" <?= $datosLibro['categoria'] == 'novela' ? 'selected' : '' ?>>Novela</option>
            <option value="no-ficcion" <?= $datosLibro['categoria'] == 'no-ficcion' ? 'selected' : '' ?>>No Ficción</option>
        </select><br>
    </div>
    <div class="input-cliente">
        <label class="input-label-Regcliente">Autor:</label>
        <input class="input-Regcliente" type="text" name="autor" value="<?= htmlspecialchars($datosLibro['autor']) ?>" required><br>
    </div>
    <div class="input-cliente">
        <label class="input-label-Regcliente">Precio:</label>
        <input class="input-Regcliente" type="number" name="precio" step="0.01" value="<?= $datosLibro['precio'] ?>" required><br>
    </div>
    <div class="input-cliente">
        <label class="input-label-Regcliente">Sucursal:</label>
        <select class="regis-pos" name="sucursal">
                    <option value="1" <?php echo isset($datosLibro['sucursal_id']) && $datosLibro['sucursal_id'] == 1 ? 'selected' : ''; ?>>Central</option>
                    <option value="2" <?php echo isset($datosLibro['sucursal_id']) && $datosLibro['sucursal_id'] == 2 ? 'selected' : ''; ?>>Este</option>
        </select>
    </div>
    <div class="input-cliente">
        <label class="input-label-Regcliente">Cantidad Disponible:</label>
        <input class="input-Regcliente" type="number" name="cantidad_disponible" value="<?php echo isset($datosLibro['cantidad_disponible']) && $datosLibro['cantidad_disponible']?>" required><br>
    </div>
        <button id="buttons" type="submit">Actualizar</button>
    </form>
</article>
</section>
</body>
</html>

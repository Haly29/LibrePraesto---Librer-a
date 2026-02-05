<?php
require_once '../../Config/conexion.php';
require_once '../Modelos/clase_inventario.php';

$inventario = new ClaseInventario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $datosLibro = [
            'codigo' => $_POST['codigo'],
            'nombre' => $_POST['nombre'],
            'descripcion' => $_POST['descripcion'],
            'autor' => $_POST['autor'],
            'categoria' => $_POST['categoria'],
            'cantidad_disponible' => $_POST['cantidad_disponible'],
            'precio' => $_POST['precio']
        ];

        $sucursalId = $_POST['sucursal'];
        $archivoImagen = $_FILES['imagen'];

        $inventario->registrarLibroCompleto($datosLibro, $sucursalId, $archivoImagen);

        echo "Libro registrado exitosamente.";
         // Redirigir a modulo_libro después de registrar
         header("Location: modulo_inventario.php");
         exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Libro</title>
    <link rel="stylesheet" type="text/css" href="../../Public/CSS/normalize.css">
    <link rel="stylesheet" type="text/css" href="../../Public/CSS/estilo.css">
    <script>
        function validarFormulario() {
            const cantidad = document.querySelector('input[name="cantidad_disponible"]');
            const precio = document.querySelector('input[name="precio"]');
            const archivo = document.querySelector('input[name="imagen"]');

            // Validar cantidad no negativa
            if (parseInt(cantidad.value) < 0) {
                alert("La cantidad disponible no puede ser un número negativo.");
                return false;
            }

            // Validar precio con formato correcto
            const precioRegex = /^\d+(\.\d{2})?$/;
            if (!precioRegex.test(precio.value)) {
                alert("El precio debe tener el formato 199.00.");
                return false;
            }

            // Validar que se seleccionó una imagen
            if (!archivo.files.length) {
                alert("Debe seleccionar una imagen.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body id="Background">
<section class="registro flex flex--row flex--center">
<article id="cuadrado--lib">
    <h1 id="Subtítulo">Registrar un nuevo libro</h1>
    <form class="flex flex--column" action="" method="post" enctype="multipart/form-data" onsubmit="return validarFormulario()">
    <div class="input-cliente">
        <label class="input-label-Regcliente">Código:</label>
        <input class="input-lib" type="text" name="codigo" required>
    </div>
    <div class="input-cliente">
        <label class="input-label-Regcliente">Nombre:</label>
        <input class="input-lib" type="text" name="nombre" required>
    </div>
    <div class="input-cliente">
        <label class="input-label-Regcliente">Sucursal:</label>
        <select class="regis-pos" name="sucursal" required>
            <option value="1">Central</option>
            <option value="2">Este</option>
        </select>
        </div>
        <div class="input-cliente">
        <label class="input-label-Regcliente">Descripción:</label>
        <textarea class="input-lib" name="descripcion"></textarea>
        </div>
        <div class="input-cliente">
        <label class="input-label-Regcliente">Autor:</label>
        <input class="input-lib" type="text" name="autor" required>
        </div>
        <div class="input-cliente">
        <label class="input-label-Regcliente">Categoría:</label>
        <select class="regis-pos" name="categoria" required>
            <option value="fantasia">Fantasía</option>
            <option value="terror">Terror</option>
            <option value="novela">Novela</option>
            <option value="no-ficcion">No Ficción</option>
        </select>
        </div>
        <div class="input-cliente">
        <label class="input-label-Regcliente">Cantidad Disponible:</label>
        <input class="input-lib" type="number" name="cantidad_disponible" required>
        </div>
        <div class="input-cliente">
        <label class="input-label-Regcliente">Precio:</label>
        <input class="input-lib" type="text" name="precio" required placeholder="199.00">
        </div>
        <div class="input-cliente">
        <label class="input-label-Regcliente">Subir Imagen:</label>
        <input id="subir-archivo" type="file" name="imagen" accept=".jpg,.jpeg,.png,.gif" required>
        </div>
        
        <button id="buttons" type="submit">Registrar</button>
    </form>
</article>
</section>
</body>
</html>

<?php
include('database/connection.php');
include('util/alert.php');
include('util/util.php');
// Insertar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action']);

    if ($action == 'agregar') {
        // Obtener los datos enviados por POST
        $nombre = trim($_POST['nombre'] ?? '');
        $tipo_id = trim($_POST['tipo_id'] ?? '');
        $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
        $peso = trim($_POST['peso'] ?? '');
        $foto = $_FILES['foto']['tmp_name'];
        $nombreArchivo = $_FILES['foto']['name'];

        $directorioDestino = 'uploads/';

        // Verificar si el directorio no existe y crearlo
        if (!is_dir($directorioDestino)) {
            if (!mkdir($directorioDestino, 0777, true) && !is_dir($directorioDestino)) {
                echo 'No se pudo crear el directorio de destino.';
                exit;
            }
        }



        $extesionArchivo = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $foto_url = 'uploads/' . generateRandomString() . '_' . trim($_POST['nombre'] ?? '') . '.' . $extesionArchivo;

        if (copy($foto, $foto_url)) {
            // Archivo movido con éxito
            showUpdateAlert('Archivo subido correctamente.');
        } else {
            // Error al mover el archivo
            showUpdateAlert('Error al subir el archivo.');
        }
        // Verificar si se seleccionó una foto
        if (!empty($foto)) {
            // Leer el contenido de la foto
            $foto_data = file_get_contents($foto);

            // Escapar los caracteres especiales en el contenido de la foto
            $foto_data = mysqli_real_escape_string($conn, $foto_data);

            // Preparar la consulta SQL para insertar el registro
            $query = "INSERT INTO mascota (nombre, tipo_id, fecha_nacimiento, peso, foto, foto_url) VALUES ('$nombre', $tipo_id, '$fecha_nacimiento', $peso, '$foto_data', '$foto_url')";
        } else {
            // Preparar la consulta SQL para insertar el registro sin la foto
            $query = "INSERT INTO mascota (nombre, tipo_id, fecha_nacimiento, peso, foto_url) VALUES ('$nombre', $tipo_id, '$fecha_nacimiento', $peso, '$foto_url')";
        }

        try {
            // Ejecutar la consulta
            if (mysqli_query($conn, $query)) {
                showUpdateAlert("Inserción exitosa en la tabla mascota.");
            } else {
                showUpdateAlert("Error al insertar en la tabla mascota: " . mysqli_error($conn));
            }
        } catch (mysqli_sql_exception $e) {
            $error_message = "Error al insertar en la tabla mascota: " . $e->getMessage();
            echo '<div class="error">' . $error_message . '</div>';
        }
    } else if ($action == "actualizar") {
        // Obtener los datos enviados por POST
        $id = trim($_POST['id']);
        $nombre = trim($_POST['nombre'] ?? '');
        $tipo_id = trim($_POST['tipo_id'] ?? '');
        $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
        $peso = trim($_POST['peso'] ?? '');
        $foto = $_FILES['foto']['tmp_name'];
        $directorioDestino = 'uploads/';

        // Verificar si el directorio no existe y crearlo
        if (!is_dir($directorioDestino)) {
            if (!mkdir($directorioDestino, 0777, true) && !is_dir($directorioDestino)) {
                echo 'No se pudo crear el directorio de destino.';
                exit;
            }
        }



        $extesionArchivo = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $foto_url = 'uploads/' . generateRandomString() . '_' . trim($_POST['nombre'] ?? '') . '.' . $extesionArchivo;

        if (copy($foto, $foto_url)) {
            // Archivo movido con éxito
            showUpdateAlert('Archivo subido correctamente.');
        } else {
            // Error al mover el archivo
            showUpdateAlert('Error al subir el archivo.');
        }

        // Verificar si se seleccionó una foto
        if (!empty($foto)) {
            // Leer el contenido de la foto
            $foto_data = file_get_contents($foto);

            // Escapar los caracteres especiales en el contenido de la foto
            $foto_data = mysqli_real_escape_string($conn, $foto_data);

            // Preparar la consulta SQL para actualizar el registro con la foto
            $query = "UPDATE mascota SET nombre='$nombre', tipo_id=$tipo_id, fecha_nacimiento='$fecha_nacimiento', peso=$peso, foto='$foto_data', foto_url='$foto_url' WHERE id=$id";
        } else {
            // Preparar la consulta SQL para actualizar el registro sin la foto
            // Preparar la consulta SQL para actualizar el registro sin la foto
            $query = "UPDATE mascota SET nombre='$nombre', tipo_id=$tipo_id, fecha_nacimiento='$fecha_nacimiento', peso=$peso, foto_url='$foto_url' WHERE id=$id";
        }

        try {
            // Ejecutar la consulta
            if (mysqli_query($conn, $query)) {
                showUpdateAlert("Actualización exitosa en la tabla mascota.");
            } else {
                showUpdateAlert("Error al actualizar en la tabla mascota: " . mysqli_error($conn));
            }
        } catch (mysqli_sql_exception $e) {
            $error_message = "Error al actualizar en la tabla mascota: " . $e->getMessage();
            echo '<div class="error">' . $error_message . '</div>';
        }
    } else if ($action == "eliminar") {
        // Obtener los datos enviados por POST
        $id = trim($_POST['id']);

        // Preparar la consulta SQL para eliminar el registro
        $query = "DELETE FROM mascota WHERE id=$id";

        try {
            // Ejecutar la consulta
            if (mysqli_query($conn, $query)) {
                showUpdateAlert("Eliminación exitosa en la tabla mascota.");
            } else {
                showUpdateAlert("Error al eliminar en la tabla mascota: " . mysqli_error($conn));
            }
        } catch (mysqli_sql_exception $e) {
            $error_message = "Error al eliminar en la tabla mascota: " . $e->getMessage();
            echo '<div class="error">' . $error_message . '</div>';
        }
    }
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta SQL para obtener el registro por ID
    $query = "SELECT * FROM mascota WHERE id = $id";

    // Ejecutar la consulta
    $result = mysqli_query($conn, $query);

    // Verificar si se obtuvieron resultados
    if (mysqli_num_rows($result) > 0) {
        // Obtener los datos del registro
        $row = mysqli_fetch_assoc($result);

        // Acceder a los campos específicos del registro
        $nombre = $row['nombre'];
        $tipoId = $row['tipo_id'];
        $fechaNacimiento = $row['fecha_nacimiento'];
        $peso = $row['peso'];
        $foto = $row['foto'];
        $fotoUrl = $row['foto_url'];

        // Mostrar la imagen en HTML
        if (!empty($fotoUrl)) {
            echo '<img src="' . $fotoUrl . '" alt="Imagen de la mascota">';
        } elseif (!empty($foto)) {
            echo '<img src="uploads/' . $foto . '" alt="Imagen de la mascota">';
        }

        // Resto de los campos de la mascota
        echo 'Nombre: ' . $nombre . '<br>';
        echo 'Tipo ID: ' . $tipoId . '<br>';
        echo 'Fecha de Nacimiento: ' . $fechaNacimiento . '<br>';
        echo 'Peso: ' . $peso . '<br>';
    } else {
        echo 'No se encontró ningún registro de mascota con el ID proporcionado.';
    }

    // Liberar los resultados y cerrar la conexión a la base de datos
    mysqli_free_result($result);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin mascota</title>
</head>

<body>
    <form action="mascota.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>">
        <input type="hidden" name="action" value="<?php echo isset($_GET['id']) ? 'actualizar' : 'agregar'; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo isset($_GET['id']) ? $nombre : ''; ?>"><br><br>
        <label for="tipo_id">Tipo ID:</label>
        <input type="text" name="tipo_id"><br><br>
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento"><br><br>
        <label for="peso">Peso:</label>
        <input type="text" name="peso"><br><br>
        <label for="foto">Foto:</label>
        <input type="file" name="foto"><br><br>
        <input type="submit" value="<?php echo isset($_GET['id']) ? 'Actualizar' : 'Agregar'; ?>">
    </form>
</body>

</html>


<?php
// Obtener todas las mascotas de la tabla
$query = "SELECT * FROM mascota";

// Ejecutar la consulta
$result = mysqli_query($conn, $query);

// Verificar si se obtuvieron resultados
if (mysqli_num_rows($result) > 0) {
    // Mostrar los registros
    while ($row = mysqli_fetch_assoc($result)) {
        // Obtener los datos específicos de la mascota
        $id = $row['id'];
        $nombre = $row['nombre'];
        $tipo_id = $row['tipo_id'];
        $fecha_nacimiento = $row['fecha_nacimiento'];
        $peso = $row['peso'];
        $foto_url = $row['foto_url'];

        // Mostrar los datos de la mascota
        echo "<label>ID: $id</label><br>";
        echo "Nombre: $nombre<br>";
        echo "Tipo ID: $tipo_id<br>";
        echo "Fecha de Nacimiento: $fecha_nacimiento<br>";
        echo "Peso: $peso<br>";
        echo "Foto URL: $foto_url<br>";

        // Agregar enlaces para editar y eliminar
        echo "<a href='mascota.php?id=$id'>Editar</a> ";
        echo "<form action='mascota.php' method='post' style='display:inline-block;'>
                <input type='hidden' name='id' value='$id'>
                <input type='hidden' name='action' value='eliminar'>
                <input type='submit' value='Eliminar'>
              </form>";

        echo "<br><br>";
    }
} else {
    echo "No se encontraron mascotas en la base de datos.";
}

// Liberar los resultados y cerrar la conexión a la base de datos
mysqli_free_result($result);
mysqli_close($conn);

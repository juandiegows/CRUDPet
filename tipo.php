<?php
include('database/connection.php');

include('util/alert.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // recorrer lo que trae el post
    foreach ($_POST as $key => $value) {
        echo $key . ': ' . $value . '<br>';
    }
    // pintarlo como json
    $json = json_encode($_POST);
    echo $json;
}

// insertar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener los datos enviados por POST
    $id = trim($_POST['id']);
    $nombre = trim($_POST['nombre'] ?? '');
    $action = trim($_POST['action']);

    if ($action == 'agregar') {
        // Preparar la consulta SQL
        $query = "INSERT INTO tipo (nombre) VALUES ('$nombre')";
        try {
            // Ejecutar la consulta
            if (mysqli_query($conn, $query)) {
                showUpdateAlert("Inserción exitosa en la tabla tipo.");
            } else {
                showUpdateAlert("Error al insertar en la tabla tipo: " . mysqli_error($conn));
            }
        } catch (mysqli_sql_exception $e) {
            $error_message = "Error al insertar en la tabla tipo: " . $e->getMessage();
            echo '<div class="error">' . $error_message . '</div>';
        }
        // Cerrar la conexión a la base de datos

    } else if ($action == "actualizar") {
        // Preparar la consulta SQL para actualizar el registro
        $query = "UPDATE tipo SET nombre='$nombre' WHERE id=$id";
        try {
            // Ejecutar la consulta
            if (mysqli_query($conn, $query)) {
                showUpdateAlert("Actualización exitosa en la tabla tipo");
            } else {
                showUpdateAlert("Error al actualizar en la tabla tipo: " . mysqli_error($conn));
            }
        } catch (mysqli_sql_exception $e) {
            $error_message = "Error al actualizar en la tabla tipo: " . $e->getMessage();
        }
    } else if ($action == "eliminar") {

        // Preparar la consulta SQL para actualizar el registro
        $query = "DELETE FROM tipo WHERE id=$id";

        try {
            // Ejecutar la consulta
            if (mysqli_query($conn, $query)) {
                showUpdateAlert("eliminado exitosa en la tabla tipo");
            } else {
                showUpdateAlert("Error al eliminar en la tabla tipo: " . mysqli_error($conn));
            }
        } catch (mysqli_sql_exception $e) {
            $error_message = "Error al eliminar en la tabla tipo: " . $e->getMessage();
        }
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta SQL para obtener el registro por ID
    $query = "SELECT * FROM tipo WHERE id = $id";

    // Ejecutar la consulta
    $result = mysqli_query($conn, $query);

    // Verificar si se obtuvieron resultados
    if (mysqli_num_rows($result) > 0) {
        // Obtener los datos del registro
        $row = mysqli_fetch_assoc($result);

        // Acceder a los campos específicos del registro
        $nombre = $row['nombre'];
    } else {
        echo "No se encontró ningún registro con el ID proporcionado.";
    }

    // Liberar los resultados y cerrar la conexión a la base de datos
    mysqli_free_result($result);
}
// Cerrar la conexión a la base de datos

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin tipo</title>
</head>

<body>
    <form action="tipo.php" method="post">
        <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>">
        <input type="hidden" name="action" value="<?php echo isset($_GET['id']) ? 'actualizar' : 'agregar'; ?>">
        <label for="nombre"></label>
        <input type="text" name="nombre" value="<?php echo isset($_GET['id']) ? $nombre : ''; ?>">
        <input type="submit" value="<?php echo isset($_GET['id']) ? 'Actualizar' : 'Agregar'; ?>">
    </form>
</body>

</html>

<?php
// Consulta SQL para obtener todos los datos de la tabla tipo
$query = "SELECT * FROM tipo";

// Ejecutar la consulta
$result_all = mysqli_query($conn, $query);

// Verificar si se obtuvieron resultados
if (mysqli_num_rows($result_all) > 0) {
    // Iterar sobre los resultados y mostrar los datos
    while ($row = mysqli_fetch_assoc($result_all)) {
?>
        <!-- forma 1 -->
        <label>ID :
            <?php echo $row['id'] ?>
        </label>
        <?php
        // forma 2
        echo "ID: " . $row['id'] . "<br>";
        echo "Nombre: " . $row['nombre'] . "<br>";
        echo "<br>";
        echo "<a href='tipo.php?id=" . $row['id'] . "'>Editar</a>   ";
        echo "<br>";
        ?>
        <form action="tipo.php" method="post">
            <input type="hidden" name="id" value="<?php echo  $row['id']  ?>">
            <input type="hidden" name="action" value="eliminar">
            <input type="submit" value="Eliminar">
        </form>
<?php

    }
} else {
    echo "No se encontraron datos en la tabla tipo.";
}

// Liberar los resultados y cerrar la conexión a la base de datos
mysqli_free_result($result_all);
mysqli_close($conn);
?>
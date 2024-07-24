<?php
// siempre asi
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_base_de_datos"; //el nombre de tu base de datos

// por defecto
$conn = new mysqli($servername, $username, $password, $dbname);

// por defecto validacion
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar que el ID ha sido enviado
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Eliminar el registro con el id asociado de la base de datos
    $sql = "DELETE FROM mi_tabla WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
//validacion
    if ($stmt->execute()) {
        echo "Registro eliminado correctamente.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// por defecto
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Formulario de Ejemplo</title>
<!--esto es el js para borrar-->
    <script>
    function deleteRecord(id) {
        var xhr = new XMLHttpRequest(); //Crea una solicitud "HTTP POST" usando "XMLHttpRequest"
        xhr.open("POST", "delete.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); //Configura la solicitud para enviar datos en formato

        xhr.onreadystatechange = function() { //aca hacemos una funcion que se activa cuando la conexion de la solicitud funciono 
                                              //para avisar y eliminar la fila de la tabla
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById('row-' + id).remove(); // Elimina la fila de la tabla
                    //alert("se elimino bien");
                } else {
                   // alert("se fue a la piuta");
                }
            }
        };

        xhr.send("id=" + id); //Envia el id del registro que se desea eliminar al servidor (delete.php)
    }
    </script>
</head>
<body>
    <h1>Formulario de Ejemplo</h1>

    <?php
    // Habilitar la visualización de errores (ignora esto, le pedi a chat gpt pq me exploto todo(tengo miedo(todo terminara mañana)))
    //ini_set('display_errors', 1);
    //ini_set('display_startup_errors', 1);
    //error_reporting(E_ALL);

    // Configuración de la base de datos POR DEFECTO SIEMPRE
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mi_base_de_datos";

    // Crear conexion POR DEFECTO SIEMPRE
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexion POR DEFECTO SIEMPRE
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['name'])) {
            // esto tipo inserta a la tablita si el nombre no es null 
            $nombre = $_POST['name']; //de variable a variable
            $email = $_POST['email'];
            $mensaje = $_POST['message'];
            // y aca cargamos
            $sql = "INSERT INTO mi_tabla (nombre, email, mensaje) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nombre, $email, $mensaje);
            //mas validacion
            if ($stmt->execute()) {
                echo "Datos guardados correctamente.<br>";
            } else {
                echo "Error: " . $stmt->error;
            }
            //pone esto o explota jiji (es como no cerrar un escaner en java)
            $stmt->close();
        }
    }
    ?>

    <!-- el formulario de html ya-->
    <form action="" method="post">
        <label for="name">Nombre:</label><br>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="email">Correo electrónico:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="message">Mensaje:</label><br>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>
        
        <input type="submit" value="Enviar">
    </form>

    <?php
    // esto trae los datos de la tabla para mostrar
    $sql = "SELECT id, nombre, email, mensaje FROM mi_tabla";
    $result = $conn->query($sql);
    //si tipo las columnas no son 0 osea ninguna, imprime eso
    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Nombre</th><th>Correo Electrónico</th><th>Mensaje</th><th>Acción</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr id='row-".$row["id"]."'><td>".$row["id"]."</td><td>".$row["nombre"]."</td><td>".$row["email"]."</td><td>".$row["mensaje"]."</td>";
            echo "<td>
                    <button onclick='deleteRecord(".$row["id"].")'>Borrar</button>
                  </td></tr>";
        }
        echo "</table>";
    } else {//si no hay nada, no imoprime nada
        echo "0 resultados";
    }

    // Cerrar la conexion tmb, de fabrica siempre aca, al final
    $conn->close();
    ?>
</body>
</html>

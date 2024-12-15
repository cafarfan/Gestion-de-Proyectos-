<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $miembros = isset($_POST['miembros']) ? $_POST['miembros'] : [];  // Usamos 'miembros' en lugar de 'usuario'
    $proyecto = htmlspecialchars($_POST['proyecto']);

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($miembros) || empty($proyecto)) {
        echo '<p style="color:red; text-align:center;">Todos los campos son obligatorios.</p>';
        echo '<a href="index.html">Volver al formulario</a>';
        exit();
    }

    // Crear el array de datos a enviar a Firebase
    $data = [
        'nombre' => $nombre,
        'usuarios' => $miembros, 
        'proyecto' => $proyecto
    ];

    // Codificar los datos a formato JSON
    $jsonData = json_encode($data);

    // URL de la base de datos de Firebase
    $url = "https://gestion-proyectos-a6c76-default-rtdb.firebaseio.com/equipos.json";

    // Inicializar cURL
    $ch = curl_init($url);

    // Configurar cURL para enviar los datos en POST
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    // Ejecutar la solicitud cURL
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Manejar errores de cURL
    if (curl_errno($ch)) {
        echo '<p style="color:red; text-align:center;">Error en la conexión: ' . curl_error($ch) . '</p>';
    } elseif ($http_code !== 200) {
        echo '<p style="color:red; text-align:center;">Error al insertar los datos. Código HTTP: ' . $http_code . '</p>';
    } else {
        echo '<p style="color:green; text-align:center;">Equipo registrado.</p>';
    }

    // Cerrar cURL
    curl_close($ch);

    // Botón para regresar al formulario de creación de proyectos
    echo '<div style="text-align: center;">';
    echo '<a href="index.html" class="btn btn-primary btn-lg">Volver al formulario</a>';
    echo '</div>';
} else {
    echo '<p style="color:red; text-align:center;">Acceso no autorizado.</p>';
}
?>

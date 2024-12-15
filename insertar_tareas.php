<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $proyecto = htmlspecialchars($_POST['proyecto']);
    $tarea = htmlspecialchars($_POST['tarea']);
    $estado = htmlspecialchars($_POST['estado']);
    $fecha_inicio = htmlspecialchars($_POST['fecha_inicio']);
    $fecha_fin = htmlspecialchars($_POST['fecha_fin']);
    $subtareas = isset($_POST['subtarea']) ? $_POST['subtarea'] : [];

    // Validar que los campos no estén vacíos
    if (empty($proyecto) || empty($tarea) || empty($estado) || empty($fecha_inicio) || empty($fecha_fin) || empty($subtareas)) {
        echo '<p style="color:red; text-align:center;">Todos los campos son obligatorios.</p>';
        echo '<a href="index.html">Volver al formulario</a>';
        exit();
    }

    // Crear el array de datos a enviar a Firebase
    $data = [
        'proyecto' => $proyecto,
        'tarea' => $tarea,
        'estado' => $estado,
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin,
        'subtareas' => $subtareas
    ];

    // Codificar los datos a formato JSON
    $jsonData = json_encode($data);

    // URL de la base de datos de Firebase
    $url = "https://gestion-proyectos-a6c76-default-rtdb.firebaseio.com/tareas.json";

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
        echo '<p style="color:green; text-align:center;">Tarea registrada correctamente.</p>';
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

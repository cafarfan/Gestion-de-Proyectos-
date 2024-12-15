<?php
// Validar que el formulario fue enviado con el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recolectar información del formulario
    $nombre = htmlspecialchars($_POST['proyecto']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $responsable = htmlspecialchars($_POST['responsable']);
    $fecha_inicio = htmlspecialchars($_POST['fecha_inicio']);
    $fecha_fin = htmlspecialchars($_POST['fecha_fin']);
    
    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($descripcion) || empty($responsable) || empty($fecha_inicio) || empty($fecha_fin)) {
        echo '<p style="color:red; text-align:center;">Todos los campos son obligatorios.</p>';
        echo '<a href="index.html">Volver al formulario</a>';
        exit();
    }
    
    // Crear el array de datos a enviar a Firebase
    $data = [
        'proyecto' => $nombre,
        'descripcion' => $descripcion,
        'responsable' => $responsable,
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin
    ];
    
    // Codificar los datos a formato JSON
    $jsonData = json_encode($data);
    
    // URL de la base de datos de Firebase (Realtime Database)
    $url = 'https://gestion-proyectos-a6c76-default-rtdb.firebaseio.com/proyectos.json';
    
    // Inicializar cURL para realizar la solicitud POST
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    // Ejecutar la solicitud
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        echo '<p style="color:red; text-align:center;">Error en la conexión: ' . curl_error($ch) . '</p>';
    } elseif ($http_code !== 200) {
        echo '<p style="color:red; text-align:center;">Error al insertar los datos. Código HTTP: ' . $http_code . '</p>';
    } else {
        echo '<p style="color:green; text-align:center;">Proyecto creado con éxito.</p>';
    }
    
    // Cerrar la conexión cURL
    curl_close($ch);
    
    // Botón para regresar al formulario de creación de proyectos
    echo '<div style="text-align: center;">';
    echo '<a href="index.html" class="btn btn-primary btn-lg">Volver al formulario</a>';
    echo '</div>';
} else {
    echo '<p style="color:red; text-align:center;">Acceso no autorizado.</p>';
}

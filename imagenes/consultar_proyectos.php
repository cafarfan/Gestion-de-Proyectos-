
<?php
// URL de la base de datos de Firebase (Realtime Database)
$url = 'https://gestion-proyectos-a6c76-default-rtdb.firebaseio.com/proyectos.json';

// Inicializar cURL para realizar la solicitud GET
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Ejecutar la solicitud
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo '<p style="color:red; text-align:center;">Error en la conexión: ' . curl_error($ch) . '</p>';
} elseif ($http_code < 200 || $http_code >= 300) { // Verificar que el código de respuesta HTTP esté en el rango 200-299
    echo '<p style="color:red; text-align:center;">Error al consultar los datos. Código HTTP: ' . $http_code . '</p>';
    echo '<p style="color:red; text-align:center;">Respuesta: ' . htmlspecialchars($response) . '</p>'; // Mostrar la respuesta para depuración
} else {
    // Decodificar la respuesta JSON en un array asociativo
    $proyectos = json_decode($response, true);
    
    // Crear el formulario de selección de proyectos
    echo '<select class="form-control" name="proyecto" id="consulProyecto">';
    echo '<option value="">Seleccione un proyecto</option>';
    
    if ($proyectos) {
        // Iterar sobre cada proyecto y mostrarlo como opción en el select
        foreach ($proyectos as $id => $proyecto) {
            echo '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($proyecto['proyecto']) . '</option>';
        }
        echo '</select>';
    } else {
        echo '<p style="color:red; text-align:center;">No hay proyectos registrados en la base de datos.</p>';
    }
}

// Cerrar la conexión cURL
curl_close($ch);
?>

<div style="text-align: center; margin-top: 20px;">
    <a href="index.html" class="btn btn-primary btn-lg">Volver al formulario</a>
</div>




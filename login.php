<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $usuario = isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Comprobar que los campos no están vacíos
    if (empty($usuario) || empty($password)) {
        echo '<p style="color:red; text-align:center;">Por favor, ingrese usuario y contraseña</p>';
        exit;
    }

    // URL de la base de datos de Firebase (Realtime Database)
    $url = 'https://gestion-proyectos-a6c76-default-rtdb.firebaseio.com/usuarios.json';

    // Inicializar cURL para realizar la solicitud GET
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        echo '<p style="color:red; text-align:center;">Error en la conexión: ' . curl_error($ch) . '</p>';
    } elseif ($http_code < 200 || $http_code >= 300) {
        echo '<p style="color:red; text-align:center;">Error al consultar los datos. Código HTTP: ' . $http_code . '</p>';
    } else {
        // Decodificar la respuesta JSON en un array asociativo
        $usuarios = json_decode($response, true);

        if ($usuarios) {
            // Validar las credenciales
            $usuario_valido = false;
            foreach ($usuarios as $id => $usuario_data) {
                // Comparar usuario y verificar la contraseña de forma segura
                if ($usuario_data['usuario'] == $usuario && password_verify($password, $usuario_data['password'])) {
                    $usuario_valido = true;
                    // iniciar sesión y redirigir al usuario
                    session_start();
                    $_SESSION['usuario_id'] = $id;
                    $_SESSION['usuario'] = $usuario_data['usuario'];
                    header('Location: index.html');
                    exit;
                }
                
            }

            if (!$usuario_valido) {
                echo '<p style="color:red; text-align:center;">Usuario o contraseña incorrectos</p>';
            }
        } else {
            echo '<p style="color:red; text-align:center;">No se encontraron usuarios en la base de datos</p>';
        }
    }

    curl_close($ch);
}
?>




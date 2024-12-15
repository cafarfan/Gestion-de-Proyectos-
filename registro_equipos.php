<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Equipos de Trabajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('imagenes/equipos.JPG');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
        }

        .card {
            background-color: rgba(155, 208, 233, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #0288d1;
            color: white;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #0288d1;
            border-color: #0277bd;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #0277bd;
            border-color: #01579b;
        }

        .form-control:focus {
            border-color: #0288d1;
            box-shadow: 0 0 0 0.2rem rgba(2, 136, 209, 0.25);
        }

        label {
            color: #16181a;
        }

        .img-container img {
            width: 60%;
            height: auto;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .form-group input {
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .miembros-container {
            margin-bottom: 20px;
        }

        .remove-member-btn {
            cursor: pointer;
            color: red;
        }

        .btn-back a {
            background-color: #0288d1;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h2>Registro - Equipos de Trabajo</h2>
            </div>

            <div class="card-body">
                <div class="img-container text-center">
                    <img src="imagenes/equipos_1.JPG" alt="Imagen de Registro">
                </div>

                <!-- Formulario de registro -->
                <form action="insertardatos_equipo.php" method="POST">

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>

                    <div id="miembros-container" class="miembros-container">
                        <div class="form-group">
                            <label for="miembros">Usuarios:</label>
                            <select class="form-control" id="miembros" name="miembros[]" required>
                                <option value="">Seleccione Usuarios</option>
                                <?php
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
                                    echo '<p style="color:red; text-align:center;">Respuesta: ' . htmlspecialchars($response) . '</p>';
                                } else {
                                    // Decodificar la respuesta JSON en un array asociativo
                                    $usuarios = json_decode($response, true);

                                    if ($usuarios) {
                                        // Iterar sobre cada usuario y mostrarlo como opción en el select
                                        foreach ($usuarios as $id => $usuario) {
                                            echo '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($usuario['usuario']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No hay usuarios disponibles</option>';
                                    }
                                }

                                // Cerrar la conexión cURL
                                curl_close($ch);
                                ?>
                            </select>
                        </div>
                    </div>

                    <button type="button" id="add-member-btn" class="btn btn-secondary btn-sm">Agregar otro miembro</button>

                    <div class="form-group">
                        <label for="proyecto">Proyecto:</label>
                        <select class="form-control" id="proyecto" name="proyecto" required>
                            <option value="">Seleccione un proyecto</option>

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
                            } elseif ($http_code < 200 || $http_code >= 300) {
                                echo '<p style="color:red; text-align:center;">Error al consultar los datos. Código HTTP: ' . $http_code . '</p>';
                                echo '<p style="color:red; text-align:center;">Respuesta: ' . htmlspecialchars($response) . '</p>';
                            } else {
                                // Decodificar la respuesta JSON en un array asociativo
                                $proyectos = json_decode($response, true);

                                if ($proyectos) {
                                    // Iterar sobre cada proyecto y mostrarlo como opción en el select
                                    foreach ($proyectos as $id => $proyecto) {
                                        echo '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($proyecto['proyecto']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay proyectos disponibles</option>';
                                }
                            }

                            // Cerrar la conexión cURL
                            curl_close($ch);
                            ?>

                        </select>
                    </div>

                    <button type="submit" name="registrar" class="btn btn-primary">Registrar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Funcionalidad para agregar más miembros
        document.getElementById('add-member-btn').addEventListener('click', function() {
            const miembrosContainer = document.getElementById('miembros-container');
            const newSelect = document.createElement('div');
            newSelect.classList.add('form-group');
            newSelect.innerHTML = `
                <label for="miembros">Usuarios:</label>
                <select class="form-control" name="miembros[]" required>
                    <option value="">Seleccione Usuarios</option>
                    <?php
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
                        echo '<p style="color:red; text-align:center;">Respuesta: ' . htmlspecialchars($response) . '</p>';
                    } else {
                        // Decodificar la respuesta JSON en un array asociativo
                        $usuarios = json_decode($response, true);

                        if ($usuarios) {
                            // Iterar sobre cada usuario y mostrarlo como opción en el select
                            foreach ($usuarios as $id => $usuario) {
                                echo '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($usuario['usuario']) . '</option>';
                            }
                        } else {
                            echo '<option value="">No hay usuarios disponibles</option>';
                        }
                    }

                    // Cerrar la conexión cURL
                    curl_close($ch);
                    ?>
                </select>
                <span class="remove-member-btn" onclick="this.parentElement.remove()">Eliminar</span>
            `;
            miembrosContainer.appendChild(newSelect);
        });
    </script>

    <br>
    <br>

    <div class="btn-back">
        <center><a href="index.html">Volver al formulario</a></center>
    </div>

</body>

</html>
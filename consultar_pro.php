<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Proyectos</title>
    <style>
        
        body {
            background-image: url('imagenes/proyectos_3.JPG');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            margin: 5% auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 90%;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 2.5em;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #0288d1;
            color: white;
            font-size: 1.1em;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 20px;
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

        .btn-back a:hover {
            background-color: #0277bd;
        }

        .error {
            color: red;
            text-align: center;
            font-size: 1.2em;
            margin-top: 20px;
        }

        .no-proyectos {
            color: #f39c12;
            font-size: 1.1em;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Listado de Proyectos</h1>

        <?php
        error_reporting (0);
        $url = 'https://gestion-proyectos-a6c76-default-rtdb.firebaseio.com/proyectos.json';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            echo '<p class="error">Error en la conexión: ' . curl_error($ch) . '</p>';
        } elseif ($http_code < 200 || $http_code >= 300) {
            echo '<p class="error">Error al consultar los datos. Código HTTP: ' . $http_code . '</p>';
            echo '<p class="error">Respuesta: ' . htmlspecialchars($response) . '</p>';
        } else {
            $proyectos = json_decode($response, true);

            if ($proyectos) {
                echo '<table>';
                echo '<tr><th>Proyecto</th><th>Descripción</th><th>Responsable</th><th>Fecha de Inicio</th><th>Fecha de Fin</th></tr>';

                foreach ($proyectos as $id => $proyecto) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($proyecto['proyecto']) . '</td>';
                    echo '<td>' . htmlspecialchars($proyecto['descripcion']) . '</td>';
                    echo '<td>' . htmlspecialchars($proyecto['responsable']) . '</td>';
                    echo '<td>' . htmlspecialchars($proyecto['fecha_inicio']) . '</td>';
                    echo '<td>' . htmlspecialchars($proyecto['fecha_fin']) . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<p class="no-proyectos">No hay proyectos registrados en la base de datos.</p>';
            }
        }

        curl_close($ch);
        ?>

        <div class="btn-back">
            <a href="index.html">Volver al formulario</a>
        </div>
    </div>

</body>

</html>

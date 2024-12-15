<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero Kanban - Gestión de Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }

        .container {
            margin-top: 30px;
        }

        .kanban-board {
            display: flex;
            justify-content: space-between;
        }

        .kanban-column {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 30%;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }

        .kanban-column h3 {
            text-align: center;
            color: #0288d1;
        }

        .kanban-card {
            background-color: #fff;
            border-radius: 6px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            padding: 10px;
            cursor: pointer;
        }

        .kanban-card:hover {
            background-color: #f1f1f1;
        }

        .card-title {
            font-weight: bold;
        }

        .add-card {
            margin-top: 15px;
            text-align: center;
        }

        .add-card button {
            background-color: #0288d1;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-card button:hover {
            background-color: #0277bd;
        }

        /* Estilo para el informe */
        .report-table {
            margin-top: 30px;
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th,
        .report-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .report-table th {
            background-color: #0288d1;
            color: white;
        }

        .report-button {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Tablero Kanban - Gestión de Proyecto</h1>

        <div class="form-group mb-3">
            <label for="proyecto">Proyecto</label>
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

        <br>

        <!-- Botón para generar el informe -->
        <div class="report-button">
            <button class="btn btn-success" id="generarInforme" disabled>Generar Informe</button>
        </div>

        <!-- Informe generado -->
        <table class="report-table" id="tablaInforme" style="display: none;">
            <thead>
                <tr>
                    <th>Tarea</th>
                    <th>Estado</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Subtareas</th>
                </tr>
            </thead>
            <tbody id="informeCuerpo"></tbody>
        </table>

        <br>
        <br>

        <div class="kanban-board">
            <!-- Columna "Por hacer" -->
            <div class="kanban-column" id="por_hacer">
                <h3>Por hacer</h3>
            </div>

            <!-- Columna "En progreso" -->
            <div class="kanban-column" id="en_progreso">
                <h3>En progreso</h3>
            </div>

            <!-- Columna "Completado" -->
            <div class="kanban-column" id="completado">
                <h3>Completado</h3>
            </div>
        </div>
    </div>

    <script>
        let tareas = [];

        document.getElementById('proyecto').addEventListener('change', function () {
            const proyectoId = this.value;
            const generarInformeBtn = document.getElementById('generarInforme');
            if (proyectoId) {
                generarInformeBtn.disabled = false; // Habilitar el botón de generar informe

                // Obtener todas las tareas
                fetch('https://gestion-proyectos-a6c76-default-rtdb.firebaseio.com/tareas.json')
                    .then(response => response.json())
                    .then(data => {
                        // Limpiar las columnas y el informe
                        document.getElementById('por_hacer').innerHTML = '<h3>Por hacer</h3>';
                        document.getElementById('en_progreso').innerHTML = '<h3>En progreso</h3>';
                        document.getElementById('completado').innerHTML = '<h3>Completado</h3>';
                        tareas = []; // Limpiar tareas

                        // Filtrar las tareas del proyecto seleccionado
                        for (let tareaId in data) {
                            const tarea = data[tareaId];
                            if (tarea.proyecto === proyectoId) {
                                const taskElement = document.createElement('div');
                                taskElement.classList.add('kanban-card');
                                taskElement.innerHTML = `
                                    <div class="card-title">${tarea.tarea}</div>
                                    <div class="card-body">
                                        Fecha inicio: ${tarea.fecha_inicio}<br>
                                        Fecha fin: ${tarea.fecha_fin}<br>
                                        Estado: ${tarea.estado}<br>
                                        Subtareas: <ul>${tarea.subtareas ? tarea.subtareas.map(subtarea => `<li>${subtarea}</li>`).join('') : ''}</ul>
                                    </div>`;

                                // Clasificar las tareas según su estado
                                if (tarea.estado === 'pendiente') {
                                    document.getElementById('por_hacer').appendChild(taskElement);
                                } else if (tarea.estado === 'en_progreso') {
                                    document.getElementById('en_progreso').appendChild(taskElement);
                                } else if (tarea.estado === 'completado') {
                                    document.getElementById('completado').appendChild(taskElement);
                                }

                                // Guardar tarea para el informe
                                tareas.push(tarea);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar las tareas:', error);
                    });
            }
        });

        // Generar el informe cuando se hace clic en el botón
        document.getElementById('generarInforme').addEventListener('click', function () {
            const tablaInforme = document.getElementById('tablaInforme');
            const informeCuerpo = document.getElementById('informeCuerpo');

            // Limpiar la tabla
            informeCuerpo.innerHTML = '';

            // Mostrar las tareas en la tabla de informe
            tareas.forEach(tarea => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${tarea.tarea}</td>
                    <td>${tarea.estado}</td>
                    <td>${tarea.fecha_inicio}</td>
                    <td>${tarea.fecha_fin}</td>
                    <td><ul>${tarea.subtareas ? tarea.subtareas.map(subtarea => `<li>${subtarea}</li>`).join('') : ''}</ul></td>
                `;
                informeCuerpo.appendChild(row);
            });

            // Mostrar la tabla
            tablaInforme.style.display = 'table';
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>

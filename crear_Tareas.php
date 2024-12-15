<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Tareas</title>
  <!-- Enlace a Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <style>
    body {
      background-image: url('imagenes/tareas.JPG');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      font-family: Arial, sans-serif;
    }

    .container {
      max-width: 800px;
      margin-top: 50px;
    }

    .card-header {
      background-color: rgba(0, 123, 255, 0.8);
      color: white;
    }

    .card-body {
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 8px;
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #004085;
    }

    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .subtarea {
      margin-top: 10px;
    }

    .btn-link {
      font-size: 0.9em;
      color: #007bff;
    }

    .btn-link:hover {
      text-decoration: underline;
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

  <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-database.js"></script>
</head>

<body>

  <div class="container">
    <div class="card shadow-lg">
      <div class="card-header text-center">
        <h4>Crear Tareas y Subtareas</h4>
      </div>
      <div class="card-body">
        <form action="insertar_tareas.php" method="POST">
          <!-- Selección del proyecto -->
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

          <!-- Campo para tarea principal -->
          <div class="form-group mb-3">
            <label for="tarea">Tarea Principal</label>
            <input type="text" class="form-control" id="tarea" name="tarea" required>
          </div>

          <div class="form-group mb-3">
            <label for="estado">Estado</label>
            <select class="form-control" id="estado" name="estado" required>
              <option value="pendiente">Pendiente</option>
              <option value="en_progreso">En Progreso</option>
              <option value="completado">Completado</option>
            </select>
          </div>

          <!-- Fechas de inicio y fin de la tarea -->
          <div class="form-group mb-3">
            <label for="fecha_inicio">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
          </div>
          <div class="form-group mb-3">
            <label for="fecha_fin">Fecha de Fin</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
          </div>

          <!-- Campo para subtareas -->
          <div class="form-group mb-3">
            <label for="subtarea">Subtareas</label>
            <div id="subtareas">
              <input type="text" class="form-control subtarea" name="subtarea[]" placeholder="Subtarea 1">
              <input type="text" class="form-control subtarea mt-2" name="subtarea[]" placeholder="Subtarea 2">
              <input type="text" class="form-control subtarea mt-2" name="subtarea[]" placeholder="Subtarea 3">
            </div>
            <button type="button" class="btn btn-link mt-2" onclick="agregarSubtarea()">Agregar otra subtarea</button>
          </div>

          <br>
          <button type="submit" class="btn btn-primary btn-block">Crear Tarea</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

  <script>
    // Función para agregar nuevas subtareas
    function agregarSubtarea() {
      const subtareasDiv = document.getElementById("subtareas");
      const nuevoCampo = document.createElement("input");
      nuevoCampo.type = "text";
      nuevoCampo.className = "form-control subtarea mt-2";
      nuevoCampo.name = "subtarea[]";
      nuevoCampo.placeholder = "Nueva subtarea";
      subtareasDiv.appendChild(nuevoCampo);
    }
  </script>

  <br>
  <br>

  <div class="btn-back">
    <center><a href="index.html">Volver al formulario</a></center>
  </div>

</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creación de proyectos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    body {
      background-image: url('imagenes/crear_proyecto.JPG');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .container {
      max-width: 600px;
      margin-top: 50px;
    }

    .card {
      background-color: #a9bde2;
    }

    .btn-primary {
      background-color: #0288d1;
      border-color: #0277bd;
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
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    footer {
      background-color: #343a40;
      color: white;
      padding: 15px 0;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="card">
      <div class="card-header text-center">
        <h4>Formulario - Creación de Proyecto</h4>
      </div>
      <div class="card-body">
        <div class="img-container mb-4">
          <img src="imagenes/registro_info.JPG" alt="Imagen de Registro">
        </div>
        <form action="insertardatos_proyecto.php" method="POST">
          <div class="form-group mb-3">
            <label for="proyecto">Nombre del Proyecto</label>
            <input type="text" class="form-control" id="proyecto" name="proyecto" required>
          </div>
          <div class="form-group mb-3">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
          </div>
          
          <div class="form-group mb-3">
            <label for="responsable">Responsable</label>
            <select class="form-control" id="responsable" name="responsable" required>
              <option value="">Seleccione Usuario</option>
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
          <div class="form-group mb-3">
            <label for="fecha_inicio">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
          </div>
          <div class="form-group mb-3">
            <label for="fecha_fin">Fecha de Finalización</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Crear Proyecto</button>
        </form>
      </div>
    </div>
  </div>

  <br>
  <br>

  <footer class="text-center">
    <p>2024 Plataforma de Gestión de Proyectos. <a href="index.html" class="text-white">Volver al formulario</a></p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

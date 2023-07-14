<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tabla de Estudiantes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
    <header class="centered-header">
            <h1>Universidad de las Fuerzas Armadas "ESPE"</h1>
        </header>
        
        <a href="index.php?action=create" class="btn btn-primary mb-3">Insertar</a>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Horario</th>
                    <th>NRC</th>
                    <th>Asignatura</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $estudiante) : ?>
                    <tr>
                        <td><?php echo $estudiante['id']; ?></td>
                        <td><?php echo $estudiante['nombre']; ?></td>
                        <td><?php echo $estudiante['apellido']; ?></td>
                        <td><?php echo $estudiante['correo']; ?></td>
                        <td><?php echo $estudiante['horario']; ?></td>
                        <td><?php echo $estudiante['nrc_nombre']; ?></td>
                        <td><?php echo isset($estudiante['asignatura_nrc_nombre']) ? $estudiante['asignatura_nrc_nombre'] : 'No disponible'; ?></td>
                        <td>
                            <a href="index.php?action=edit&id=<?php echo $estudiante['id']; ?>" class="btn btn-info btn-sm">Editar</a>
                            <a href="index.php?action=delete&id=<?php echo $estudiante['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

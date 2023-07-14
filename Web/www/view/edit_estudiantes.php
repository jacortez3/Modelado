<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Estudiante</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <header class="centered-header">
            <h1>Universidad de las Fuerzas Armadas "ESPE"</h1>
        </header>
        <h1>Modificar</h1>

        <form action="index.php?action=update" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo $estudiante['nombre']; ?>">
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" id="apellido" class="form-control" value="<?php echo $estudiante['apellido']; ?>">
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" name="correo" id="correo" class="form-control" value="<?php echo $estudiante['correo']; ?>">
            </div>
            <div class="form-group">
                <label for="nrc">NRC:</label>
                <select name="nrc" id="nrc" class="form-control">
                    <?php foreach ($nrcs as $nrc) : ?>
                        <option value="<?php echo $nrc['nrc_nombre']; ?>" <?php echo $estudiante['nrc_nombre'] == $nrc['nrc_nombre'] ? 'selected' : ''; ?>>
                            <?php echo $nrc['nrc_nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="horario">Horario:</label>
                <input type="text" name="horario" id="horario" class="form-control" value="<?php echo $estudiante['horario']; ?>">
            </div>
            <input type="hidden" name="id" value="<?php echo $estudiante['id']; ?>">
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

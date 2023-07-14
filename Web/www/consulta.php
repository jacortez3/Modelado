<?php
include 'config.php';

$nrc = $_POST['nrc']; // Valor del atributo NRC enviado por el formulario
$asignatura = $_POST['asignatura']; // Valor del atributo Asignatura enviado por el formulario
$estudiantes = $_POST['estudiantes']; // Valor del atributo Estudiantes enviado por el formulario

$sql = "SELECT * FROM data WHERE nrc = :nrc AND asignatura = :asignatura AND estudiantes = :estudiantes";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':nrc', $nrc);
$stmt->bindParam(':asignatura', $asignatura);
$stmt->bindParam(':estudiantes', $estudiantes);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Mostrar los resultados de la consulta
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Acceder a los valores de cada fila
        $id = $row['id'];
        $nombre = $row['nombre'];
        $apellido = $row['apellido'];
        // ... y así sucesivamente para los demás campos
        echo "ID: $id, Nombre: $nombre, Apellido: $apellido<br>";
    }
} else {
    echo "No se encontraron resultados.";
}
?>

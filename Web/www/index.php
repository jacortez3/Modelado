<?php
include 'config.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'create':
        createEstudiante();
        break;
    case 'store':
        storeEstudiante();
        break;
    case 'edit':
        editEstudiante();
        break;
    case 'update':
        updateEstudiante();
        break;
    case 'delete':
        deleteEstudiante();
        break;
    case 'search':
        searchEstudiantes();
        break;
    default:
        $estudiantes = listEstudiantes();
        $nrcList = obtenerNRCList(); // Agregar esta línea para obtener la lista de NRC
        include 'view/list_estudiantes.php';
        break;
}

function obtenerNRCList()
{
    $conn = connectDB();

    $sql = "SELECT nrc_nombre, nrc_hora FROM nrc";
    $result = pg_query($conn, $sql);

    $nrcList = [];

    if ($result && pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $nrcList[] = $row;
        }
    }

    pg_free_result($result);
    pg_close($conn);

    return $nrcList;
}

function listEstudiantes()
{
    $conn = connectDB();

    $sql = "SELECT e.id, e.nombre, e.apellido, e.correo, e.horario, e.nrc_nombre, a.asignatura_nombre 
        FROM Estudiante e 
        LEFT JOIN Asignatura a ON e.nrc_nombre = a.nrc_nombre";


    $result = pg_query($conn, $sql);

    $estudiantes = [];

    if ($result && pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $estudiantes[] = $row;
        }
    }

    pg_close($conn);

    return $estudiantes;
}

function obtenerAsignaturaNombre($nrc_nombre)
{
    $conn = connectDB();

    $sql = "SELECT asignatura_nombre FROM Asignatura WHERE nrc_nombre = '$nrc_nombre'";
    $result = pg_query($conn, $sql);

    if ($result) {
        $row = pg_fetch_assoc($result);
        $asignatura_nombre = $row['asignatura_nombre'];
    } else {
        $asignatura_nombre = 'No disponible';
    }

    pg_close($conn);

    return $asignatura_nombre;
}

function createEstudiante()
{
    include 'view/create_estudiantes.php';
}

function storeEstudiante()
{
    $conn = connectDB();

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $nrc_nombre = $_POST['nrc_nombre'];

    $sql = "INSERT INTO Estudiante (nombre, apellido, correo, nrc_nombre) VALUES ('$nombre', '$apellido', '$correo', '$nrc_nombre')";
    pg_query($conn, $sql);

    pg_close($conn);

    header('Location: index.php');
}

function editEstudiante()
{
    $conn = connectDB();

    $id = $_GET['id'];

    $sql = "SELECT e.id, e.nombre, e.apellido, e.correo, e.horario, n.nrc_nombre, a.asignatura_nombre FROM Estudiante e LEFT JOIN NRC n ON e.nrc_nombre = n.nrc_nombre LEFT JOIN Asignatura a ON n.nrc_nombre = a.nrc_nombre WHERE e.id = $id";
    $result = pg_query($conn, $sql);
    $estudiante = pg_fetch_assoc($result);

    // Obtener lista de NRCs para el formulario
    $nrcs = getNRCs();

    include 'view/edit_estudiantes.php';

    pg_close($conn);
}

function updateEstudiante()
{
    $conn = connectDB();

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $nrc = $_POST['nrc'];
    $horario = $_POST['horario'];

    $sql = "UPDATE Estudiante SET nombre = '$nombre', apellido = '$apellido', correo = '$correo', nrc_nombre = '$nrc', horario = '$horario' WHERE id = $id";
    $result = pg_query($conn, $sql);

    if ($result) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error al actualizar el estudiante: " . pg_last_error($conn);
    }

    pg_close($conn);
}

function deleteEstudiante()
{
    $conn = connectDB();

    $id = $_GET['id'];

    $sql = "DELETE FROM Estudiante WHERE id = $id";
    pg_query($conn, $sql);

    pg_close($conn);

    header('Location: index.php');
}

function searchEstudiantes()
{
    // Implementa la función de búsqueda de estudiantes si es necesario
}

function getNRCs()
{
    $conn = connectDB();

    $sql = "SELECT nrc_nombre FROM NRC";
    $result = pg_query($conn, $sql);

    $nrcs = [];

    if ($result && pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $nrcs[] = $row;
        }
    }

    pg_close($conn);

    return $nrcs;
}


function connectDB()
{
    $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
    $conn = pg_connect($conn_string);

    if (!$conn) {
        die("Connection failed: " . pg_last_error());
    }

    return $conn;
}
?>

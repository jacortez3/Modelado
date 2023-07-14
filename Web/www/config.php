<?php

define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'data');
define('DB_USER', 'postgres');
define('DB_PASSWORD', '12345');

try {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Resto del código utilizando la conexión PDO
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>

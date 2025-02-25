<?php
// Zeitzone auf "Europe/Berlin" setzen
date_default_timezone_set('Europe/Berlin');

// Haupt-PHP-Datei

// Konfigurationsdatei einbinden
$config = require '../inc/config.php';

// Verbindung zur Datenbank
try {
    $pdo = new PDO($config['dsn'], $config['username'], $config['password'], $config['options']);
} catch (PDOException $e) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
}

// Dateiname erfassen
$file = basename(__FILE__);

// Aktuelles Jahr berechnen
$currentYear = date('Y');

// Aktuelles Datum im Format YYYY-MM berechnen
$accessDate = date('Y-m');

// Tabelle dynamisch basierend auf dem aktuellen Jahr erstellen
$tableName = "redirects_" . $currentYear;

// SQL-Query mit dynamischem Tabellennamen
$query = "INSERT INTO $tableName (file_name, access_date, count)
          VALUES (:file_name, :access_date, 1)
          ON DUPLICATE KEY UPDATE count = count + 1";

$stmt = $pdo->prepare($query);
$stmt->execute([
    'file_name' => $file,
    'access_date' => $accessDate
]);

// Weiterleitung zum Ziel-Link
header("Location: https://www.sc-deutsch-launcher.de/update/");
exit();
?>

<?php
require_once __DIR__.'/../src/PDOQuantik.php';
use quantiketape1\PDOQuantik;
// Charger les variables d'environnement à partir du fichier .env
function loadEnv($file)
{
    if (!file_exists($file)) {
        throw new Exception('Le fichier ' . $file . ' n\'existe pas.');
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!array_key_exists($key, $_SERVER)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}
loadEnv(__DIR__ . '/.env');
PDOQuantik::initPDO($_ENV['sgbd'], $_ENV['host'], $_ENV['database'], $_ENV['user'], $_ENV['password']);

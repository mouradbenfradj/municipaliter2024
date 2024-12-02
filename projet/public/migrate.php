<?php

// public/migrate.php

// Mot de passe simple pour sécuriser l'accès
$password = 'TonMotDePasseUltraSecurise';

// Vérifie le mot de passe
if (!isset($_GET['password']) || $_GET['password'] !== $password) {
    http_response_code(403);
    echo 'Accès refusé';
    exit;
}

// Charge les composants Symfony et exécute les migrations
require __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Dotenv\Dotenv;

// Charge les variables d'environnement
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env.local');

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Console\Application;

$kernel = new \App\Kernel('prod', false);
$kernel->boot();

$application = new Application($kernel);
$application->setAutoExit(false);

// Exécute la commande doctrine:migrations:migrate
$inputMigrate = new ArrayInput([
    'command' => 'doctrine:migrations:migrate',
    '--no-interaction' => true,
]);
$outputMigrate = new BufferedOutput();

$application->run($inputMigrate, $outputMigrate);

echo nl2br($outputMigrate->fetch());

// Exécute la commande doctrine:schema:update
$inputSchemaUpdate = new ArrayInput([
    'command' => 'doctrine:schema:update',
    '--force' => true, // Force l'exécution sans confirmation
    '--no-interaction' => true, // Pas d'interaction
]);
$outputSchemaUpdate = new BufferedOutput();

$application->run($inputSchemaUpdate, $outputSchemaUpdate);

echo nl2br($outputSchemaUpdate->fetch());

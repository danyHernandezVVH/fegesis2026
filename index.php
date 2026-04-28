<?php
declare(strict_types=1);

session_start();

$config = require __DIR__ . '/config/config.php';

/**
 * Autoload simple por convención (sin composer por ahora)
 */
spl_autoload_register(function (string $class): void {
  $paths = [
    __DIR__ . '/core/' . $class . '.php',
    __DIR__ . '/app/controllers/' . $class . '.php',
    __DIR__ . '/app/models/' . $class . '.php',
  ];
  foreach ($paths as $file) {
    if (file_exists($file)) {
      require_once $file;
      return;
    }
  }
});

$isLocal = (($config['app']['env'] ?? 'production') === 'local');

ini_set('display_errors', $isLocal ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

try {
  $router = new Router($config);

  // Carga TODAS las rutas aquí (sitio, admin, api)
  require __DIR__ . '/config/routes.php';

  $router->dispatch(
    $_SERVER['REQUEST_URI'] ?? '/',
    $_SERVER['REQUEST_METHOD'] ?? 'GET'
  );
} catch (Throwable $e) {
  http_response_code(500);

  if ($isLocal) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "500\n" . $e->getMessage() . "\n\n" . $e->getTraceAsString();
  } else {
    echo "Ha ocurrido un error interno.";
  }
}

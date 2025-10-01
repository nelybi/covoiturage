<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Buki\Router\Router;

$router = new Router([
  'base_folder' => '',
  'main_method' => 'index',
  'paths' => [
    'controllers' => __DIR__ . '/../src/Controllers',
  ],
  'namespaces' => [
    'controllers' => 'Elayoubi\\Covoiturage\\Controllers',
  ],
  'debug' => true,
]);

// Routes
$router->get('/', 'TrajetController@index');

$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');


$router->get('/trajets/nouveau', 'TrajetController@createForm'); // Formulaire
$router->post('/trajets', 'TrajetController@store');             // Traitement (INSERT)
// Trajets : formulaire déjà ajouté + création (store)
// ➕ Réservation d'une place
$router->post('/trajets/:id/reserver', 'TrajetController@reserve');

// Admin : gestion des agences
$router->get('/admin/agences', 'AgenceController@index');
$router->post('/admin/agences', 'AgenceController@store');
$router->post('/admin/agences/{id}/delete', 'AgenceController@destroy');

$router->run();

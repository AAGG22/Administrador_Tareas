<?php
namespace Config;

use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();

// Deshabilitar autorutas para evitar conflictos
$routes->setAutoRoute(false);

// Página principal
$routes->get('/', 'Home::index');

// Registro
$routes->get('register', 'SignupController::index');
$routes->post('register', 'SignupController::store');

// Login
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');

// Perfil
$routes->get('profile', 'UserController::profile');

// Logout
$routes->get('logout', 'Auth::logout');

// Rutas para Gestor de Tareas
$routes->group('tasks', function ($routes) {
    $routes->get('/', 'Tasks::index');
    $routes->get('create', 'Tasks::create');
    $routes->post('store', 'Tasks::store');
    $routes->get('view/(:num)', 'Tasks::view/$1');
    $routes->get('edit/(:num)', 'Tasks::edit/$1');
    $routes->post('update/(:num)', 'Tasks::update/$1');
    $routes->get('delete/(:num)', 'Tasks::delete/$1');
    $routes->post('addSubtask/(:num)', 'Tasks::addSubtask/$1');
    $routes->get('editSubtask/(:num)', 'Tasks::editSubtask/$1');
    $routes->post('updateSubtask/(:num)', 'Tasks::updateSubtask/$1');
    $routes->post('toggleSubtask/(:num)', 'Tasks::toggleSubtask/$1');
    $routes->get('compartir/(:num)', 'Tasks::compartir/$1');
});

// Rutas para PruebaDB
$routes->get('probar-db', 'PruebaDBController::index');

// Rutas adicionales
$routes->get('form', 'Form::getIndex');
$routes->post('form/exito', 'Form::postExito');

// Cargar rutas específicas del entorno
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

// Ruta para Mostrar
$routes->get('tasks/compartir/(:num)', 'Tasks::compartir/$1');
$routes->post('tasks/compartir/(:num)', 'Tasks::compartir/$1');

$routes->get('tasks/toggleSubtask/(:num)', 'Tasks::toggleSubtask/$1'); // Ruta explícita para toggleSubtask

$routes->get('tasks/archived', 'Tasks::archived');


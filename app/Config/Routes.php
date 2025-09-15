<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ------------------------
// ROTAS GET
// ------------------------
$routes->get('/', 'Home::index');
$routes->get('computadores', 'ComputerController::index');

$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);
$routes->get('dashboard/add-user', 'DashboardController::viewFormToAddUser', ['filter' => 'auth:admin']);
$routes->get('dashboard/add-machine', 'DashboardController::viewFormToAddMachine', ['filter' => 'auth']);
$routes->get('dashboard/maintenance', 'DashboardController::viewFormToCreateMaintenance', ['filter' => 'auth']);

$routes->get('user/logout', 'LoginController::logOut');
$routes->get('users/see-all', 'AdminController::listAllUsers', ['filter' => 'auth:admin']);

$routes->get('machines/see-all', 'MachineController::listAllMachines', ['filter' => 'auth:admin']);


// ROTA DE ERRO DE PRIVILEGIOS INSUFICIENTES
$routes->get('error/permission-denied', 'ErrorController::permissionDenied');

// ------------------------
// ROTAS POST
// ------------------------

$routes->post('login', 'LoginController::login');

$routes->post('admin/add-user', 'AdminController::addUser', ['filter' => 'auth:admin']);
$routes->post('admin/edit-user', 'AdminController::editUser', ['filter' => 'auth:admin']);
$routes->post('admin/delete-user', 'AdminController::softDeleteUser', ['filter' => 'auth:admin']);

// MÁQUINAS
$routes->post('admin/add-machine', 'MachineController::addMachine', ['filter' => 'auth:admin']);
$routes->post('admin/edit-machine', 'MachineController::editMachine', ['filter' => 'auth:admin']);
$routes->post('admin/delete-machine', 'MachineController::softDeleteMachine', ['filter' => 'auth:admin']);
$routes->post('machine/maintenance', 'MaintenanceController::createMaintenance', ['filter' => 'auth']);
$routes->post('machine/maintenance/finish-maintenance', 'MaintenanceController::finishMaintenance', ['filter' => 'auth']);

// $routes->post('machines/maintenance', 'MachineController::maintenance');

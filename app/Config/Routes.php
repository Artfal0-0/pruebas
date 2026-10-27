<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('transporte', 'TransporteController::index');
$routes->get('transporte/create', 'TransporteController::create');
$routes->post('transporte/delete/(:num)', 'TransporteController::delete/$1');

$routes->get('pedido', 'PedidoController::index');
$routes->get('pedido/create', 'PedidoController::create');
$routes->post('pedido/store', 'PedidoController::store');
$routes->post('pedido/delete/(:num)', 'PedidoController::delete/$1');

$routes->get('vehiculos', 'VehiculosController::index');
$routes->get('vehiculos/create', 'VehiculosController::create');
$routes->post('vehiculos/store', 'VehiculosController::store');
$routes->post('vehiculos/delete/(:num)', 'VehiculosController::delete/$1');

$routes->get('proveedores', 'ProveedoresController::index');
$routes->get('proveedores/create', 'ProveedoresController::create');
$routes->post('proveedores/delete/(:num)', 'ProveedoresController::delete/$1');

$routes->get('clientes', 'ClientesController::index');
$routes->get('clientes/create', 'ClientesController::create');
$routes->post('clientes/delete/(:num)', 'ClientesController::delete/$1');

$routes->get('producto', 'ProductoController::index');
$routes->get('producto/create', 'ProductoController::create');
$routes->post('producto/store', 'ProductoController::store');
$routes->post('producto/delete/(:num)', 'ProductoController::delete/$1');

$routes->get('factura', 'FacturaController::index');
$routes->get('factura/create', 'FacturaController::create');
$routes->post('factura/create', 'FacturaController::create');
$routes->get('factura/detalle/(:num)', 'FacturaController::detalle/$1');
$routes->post('factura/delete/(:num)', 'FacturaController::delete/$1');
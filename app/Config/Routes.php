<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->post('login/google', 'Auth::googleLogin');

$routes->get('signup', 'Auth::signup');
$routes->post('signup', 'Auth::attemptSignup');

$routes->get('dashboard', 'Dashboard::index');
$routes->get('logout', 'Auth::logout');

$routes->get('admin/dashboard', 'AdminDashboard::index');
$routes->get('admin/users', 'AdminUsers::index');
$routes->get('admin/users/(:segment)', 'AdminUsers::role/$1');

$routes->get('ceo/dashboard', 'CeoDashboard::index');
$routes->get('ceo/laporan', 'CeoDashboard::laporan');

$routes->post('admin/users/store', 'AdminUsers::store');
$routes->post('admin/users/update-role/(:num)', 'AdminUsers::updateRole/$1');
$routes->post('admin/users/reset-password/(:num)', 'AdminUsers::resetPassword/$1');
$routes->post('admin/users/delete/(:num)', 'AdminUsers::delete/$1');

$routes->get('salesman', 'Salesman::index');
$routes->post('salesman/save', 'Salesman::save');
$routes->post('salesman/delete/(:num)', 'Salesman::delete/$1');

$routes->get('kriteria', 'Kriteria::index');
$routes->post('kriteria/save', 'Kriteria::save');
$routes->post('kriteria/delete/(:num)', 'Kriteria::delete/$1');

$routes->get('penilaian', 'Penilaian::index');
$routes->post('penilaian/save', 'Penilaian::save');
$routes->get('penilaian/detail/(:num)/(:segment)', 'Penilaian::detail/$1/$2');
$routes->get('penilaian/edit', 'Penilaian::index');

$routes->get('perhitungan', 'Perhitungan::index');
$routes->post('perhitungan/process', 'Perhitungan::process');

$routes->get('cetak', 'Cetak::index');
$routes->get('cetak/download-pdf', 'Cetak::downloadPdf');

$routes->get('forgot-password', 'AuthController::forgotPassword');
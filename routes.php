<?php
/**
 * Definición de rutas del sistema
 */

// Ruta principal
$router->get('', 'HomeController@index');
$router->get('/', 'HomeController@index');

// Autenticación
$router->get('login', 'AuthController@showLogin');
$router->post('login', 'AuthController@login');
$router->get('logout', 'AuthController@logout');

// Dashboard
$router->get('dashboard', 'DashboardController@index');

// Empresas
$router->get('empresas', 'EmpresaController@index');
$router->get('empresas/nueva', 'EmpresaController@create');
$router->post('empresas/guardar', 'EmpresaController@store');
$router->get('empresas/editar/{id}', 'EmpresaController@edit');
$router->post('empresas/actualizar/{id}', 'EmpresaController@update');
$router->post('empresas/eliminar/{id}', 'EmpresaController@delete');

// Plan Contable
$router->get('plan-contable', 'PlanContableController@index');
$router->get('plan-contable/crear', 'PlanContableController@create');
$router->post('plan-contable/guardar', 'PlanContableController@store');
$router->get('plan-contable/editar/{id}', 'PlanContableController@edit');
$router->post('plan-contable/actualizar/{id}', 'PlanContableController@update');
$router->post('plan-contable/eliminar/{id}', 'PlanContableController@delete');
$router->get('plan-contable/cargar-pcge', 'PlanContableController@loadPCGE');

// Asientos Contables
$router->get('asientos', 'AsientoController@index');
$router->get('asientos/nuevo', 'AsientoController@create');
$router->post('asientos/guardar', 'AsientoController@store');
$router->get('asientos/ver/{id}', 'AsientoController@show');
$router->get('asientos/editar/{id}', 'AsientoController@edit');
$router->post('asientos/actualizar/{id}', 'AsientoController@update');
$router->post('asientos/anular/{id}', 'AsientoController@anular');

// Comprobantes
$router->get('comprobantes', 'ComprobanteController@index');
$router->get('comprobantes/nuevo', 'ComprobanteController@create');
$router->post('comprobantes/guardar', 'ComprobanteController@store');
$router->get('comprobantes/ver/{id}', 'ComprobanteController@show');
$router->post('comprobantes/anular/{id}', 'ComprobanteController@anular');

// Libros Contables
$router->get('libros/diario', 'LibroController@diario');
$router->get('libros/mayor', 'LibroController@mayor');
$router->get('libros/mayor/{cuenta_id}', 'LibroController@mayorDetalle');
$router->get('libros/caja-bancos', 'LibroController@cajaBancos');

// PLE
$router->get('ple', 'PLEController@index');
$router->post('ple/generar', 'PLEController@generar');
$router->get('ple/descargar/{archivo}', 'PLEController@descargar');

// Reportes
$router->get('reportes/balance-general', 'ReporteController@balanceGeneral');
$router->get('reportes/estado-resultados', 'ReporteController@estadoResultados');
$router->get('reportes/flujo-efectivo', 'ReporteController@flujoEfectivo');
$router->get('reportes/estado-cambios-patrimonio', 'ReporteController@estadoCambiosPatrimonio');

// Períodos Contables
$router->get('periodos', 'PeriodoController@index');
$router->post('periodos/crear', 'PeriodoController@create');
$router->post('periodos/cerrar/{id}', 'PeriodoController@cerrar');
$router->post('periodos/reabrir/{id}', 'PeriodoController@reabrir');

// Usuarios (administración)
$router->get('usuarios', 'UsuarioController@index');
$router->get('usuarios/nuevo', 'UsuarioController@create');
$router->post('usuarios/guardar', 'UsuarioController@store');
$router->get('usuarios/editar/{id}', 'UsuarioController@edit');
$router->post('usuarios/actualizar/{id}', 'UsuarioController@update');
$router->post('usuarios/eliminar/{id}', 'UsuarioController@delete');

// API (opcional)
$router->get('api/cuentas/buscar', 'ApiController@buscarCuentas');
$router->get('api/comprobantes/tipos', 'ApiController@tiposComprobante');

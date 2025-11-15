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
$router->post('empresas/seleccionar/{id}', 'EmpresaController@seleccionar');
$router->get('empresas/editar/{id}', 'EmpresaController@edit');
$router->post('empresas/actualizar/{id}', 'EmpresaController@update');
$router->post('empresas/eliminar/{id}', 'EmpresaController@delete');

// Terceros (Clientes y Proveedores)
$router->get('terceros', 'TerceroController@index');
$router->get('terceros/nuevo', 'TerceroController@create');
$router->post('terceros/guardar', 'TerceroController@store');
$router->get('terceros/editar/{id}', 'TerceroController@edit');
$router->post('terceros/actualizar/{id}', 'TerceroController@update');
$router->post('terceros/eliminar/{id}', 'TerceroController@delete');

// Registro de Ventas
$router->get('registro-ventas', 'RegistroVentasController@index');
$router->get('registro-ventas/nuevo', 'RegistroVentasController@create');
$router->post('registro-ventas/guardar', 'RegistroVentasController@store');
$router->get('registro-ventas/exportar-ple', 'RegistroVentasController@exportarPLE');

// Registro de Compras
$router->get('registro-compras', 'RegistroComprasController@index');
$router->get('registro-compras/nuevo', 'RegistroComprasController@create');
$router->post('registro-compras/guardar', 'RegistroComprasController@store');
$router->get('registro-compras/exportar-ple', 'RegistroComprasController@exportarPLE');

// Cuentas por Cobrar
$router->get('cuentas-por-cobrar', 'CuentasPorCobrarController@index');
$router->get('cuentas-por-cobrar/nuevo', 'CuentasPorCobrarController@create');
$router->post('cuentas-por-cobrar/guardar', 'CuentasPorCobrarController@store');
$router->get('cuentas-por-cobrar/ver/{id}', 'CuentasPorCobrarController@show');
$router->post('cuentas-por-cobrar/registrar-pago', 'CuentasPorCobrarController@registrarPago');
$router->get('cuentas-por-cobrar/antiguedad', 'CuentasPorCobrarController@antiguedadSaldos');
$router->get('cuentas-por-cobrar/vencidas', 'CuentasPorCobrarController@vencidas');
$router->get('cuentas-por-cobrar/por-vencer', 'CuentasPorCobrarController@porVencer');

// Cuentas por Pagar
$router->get('cuentas-por-pagar', 'CuentasPorPagarController@index');
$router->get('cuentas-por-pagar/nuevo', 'CuentasPorPagarController@create');
$router->post('cuentas-por-pagar/guardar', 'CuentasPorPagarController@store');
$router->get('cuentas-por-pagar/ver/{id}', 'CuentasPorPagarController@show');
$router->post('cuentas-por-pagar/registrar-pago', 'CuentasPorPagarController@registrarPago');
$router->get('cuentas-por-pagar/antiguedad', 'CuentasPorPagarController@antiguedadSaldos');
$router->get('cuentas-por-pagar/vencidas', 'CuentasPorPagarController@vencidas');
$router->get('cuentas-por-pagar/por-vencer', 'CuentasPorPagarController@porVencer');

// Bancos y Cajas
$router->get('bancos', 'BancoController@index');
$router->get('bancos/nuevo', 'BancoController@create');
$router->post('bancos/guardar', 'BancoController@store');
$router->get('bancos/editar/{id}', 'BancoController@edit');
$router->post('bancos/actualizar/{id}', 'BancoController@update');
$router->post('bancos/toggle-activo/{id}', 'BancoController@toggleActivo');
$router->get('bancos/movimientos/{id}', 'BancoController@movimientos');
$router->get('bancos/liquidez', 'BancoController@liquidez');

// Movimientos de Caja
$router->get('movimientos-caja', 'MovimientoCajaController@index');
$router->get('movimientos-caja/ingreso', 'MovimientoCajaController@ingreso');
$router->post('movimientos-caja/guardar-ingreso', 'MovimientoCajaController@storeIngreso');
$router->get('movimientos-caja/egreso', 'MovimientoCajaController@egreso');
$router->post('movimientos-caja/guardar-egreso', 'MovimientoCajaController@storeEgreso');
$router->get('movimientos-caja/flujo-diario', 'MovimientoCajaController@flujoDiario');
$router->get('movimientos-caja/flujo-mensual', 'MovimientoCajaController@flujoMensual');
$router->post('movimientos-caja/eliminar/{id}', 'MovimientoCajaController@delete');

// Tipos de Cambio
$router->get('tipos-cambio', 'TipoCambioController@index');
$router->get('tipos-cambio/nuevo', 'TipoCambioController@create');
$router->post('tipos-cambio/guardar', 'TipoCambioController@store');
$router->post('tipos-cambio/importar-sunat', 'TipoCambioController@importarSUNAT');
$router->get('tipos-cambio/por-fecha', 'TipoCambioController@getPorFecha');
$router->get('tipos-cambio/promedio-mensual', 'TipoCambioController@promedioMensual');
$router->get('tipos-cambio/cierre', 'TipoCambioController@tipoCambioCierre');
$router->post('tipos-cambio/eliminar/{id}', 'TipoCambioController@delete');

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

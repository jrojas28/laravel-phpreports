<?php
Route::get('/reports/', 'Jailuis\PHPReports\PHPReportsController@index');
Route::get('/report-list-json/', 'Jailuis\PHPReports\PHPReportsController@reportListJson');
Route::get('/report/', 'Jailuis\PHPReports\PHPReportsController@report');
Route::get('/report/{format}', 'Jailuis\PHPReports\PHPReportsController@reportWithFormat');
Route::get('/dashboards/', 'Jailuis\PHPReports\PHPReportsController@dashboards');
Route::get('/dashboard/{name}', 'Jailuis\PHPReports\PHPReportsController@dashboardWithName');
Route::get('/edit/', 'Jailuis\PHPReports\PHPReportsController@edit');
Route::get('/set-environment/', 'Jailuis\PHPReports\PHPReportsController@setEnv');
Route::get('/email/', 'Jailuis\PHPReports\PHPReportsController@email');
?>

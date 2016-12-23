<?php
Route::get('/reports/', 'Pushmotion\PHPReports\PHPReportsController@index');
Route::get('/report-list-json/', 'Pushmotion\PHPReports\PHPReportsController@reportListJson');
Route::get('/report/', 'Pushmotion\PHPReports\PHPReportsController@report');
Route::get('/report/{format}', 'Pushmotion\PHPReports\PHPReportsController@reportWithFormat');
Route::get('/dashboards/', 'Pushmotion\PHPReports\PHPReportsController@dashboards');
Route::get('/dashboard/{name}', 'Pushmotion\PHPReports\PHPReportsController@dashboardWithName');
Route::get('/edit/', 'Pushmotion\PHPReports\PHPReportsController@edit');
Route::get('/set-environment/', 'Pushmotion\PHPReports\PHPReportsController@setEnv');
Route::get('/email/', 'Pushmotion\PHPReports\PHPReportsController@email');
?>

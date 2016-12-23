<?php

namespace Pushmotion\PHPReports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;

class PHPReportsController extends BaseController
{
    public function __construct() {
        require_once(base_path().'/vendor/autoload.php');
        require_once(__DIR__.'/lib/PhpReports/PhpReports.php');
    }

    public function index() {
        \PhpReports::listReports();
    }

    public function dashboards() {
        \PhpReports::listDashboards();
    }

    public function dashboardWithName($name){
        \PhpReports::displayDashboard($name);
    }

    public function edit(Request $request) {
        PhpReports::editReport($request->input('report'));
    }

    public function setEnv(Request $request) {
        $request->session()->put('environment', $request->input('environment'));
        return response()->json([
            "status" => "OK"
        ]);
    }

    public function email() {
        \PhpReports::emailReport();
    }

    public function reportListJson() {
        return response()
                ->json(\PhpReports::getReportListJSON());
    }

    public function report(Request $request) {
        \PhpReports::displayReport($request->input('report'),'html');
    }

    public function reportWithFormat(Request $request, $format) {
        \PhpReports::displayReport($request->input('report'),$format);
    }


}

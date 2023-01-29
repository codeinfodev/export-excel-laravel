<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Excel;

class ExportExcelController extends Controller
{
    public function ExportUsers(Request $request){
        return Excel::download(new UsersExport,'users.xlsx');
    }
}

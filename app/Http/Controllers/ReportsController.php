<?php

namespace App\Http\Controllers;

use App\Models\Room_reservations;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function view_reports()
    {   
        $reservations = Room_reservations::all();
        return view('admin.reports.view_reports', compact(['reservations']));
    }
}

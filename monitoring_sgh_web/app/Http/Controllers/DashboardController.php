<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $temperature = DB::table('detail_sensors')->orderBy('created_at', 'desc')->value('temp');

        return view('dashboard')->with('temperature', $temperature);

    }
}

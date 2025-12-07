<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function index()
    {
        return view('dashboard.logs');
    }

    /**
     * Display a listing of the resource.
     */
    public function show()
    {
        return response()->json(Logs::with('user')->latest()->get());
    }
}

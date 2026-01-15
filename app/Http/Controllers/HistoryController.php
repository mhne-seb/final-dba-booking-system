<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    // Display history
    public function index(Request $request)
    {
        $history = DB::select('CALL history_information()');
        $total = count($history);
        
        
        return view('pages.history', compact('history', 'total'));
    }
    
}
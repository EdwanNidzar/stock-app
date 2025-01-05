<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;

class HistoryController extends Controller
{
    public function tableName($tableName)
    {
        $history = History::with('user') // Eager load the user relationship
            ->where('table_name', $tableName)
            ->orderBy('created_at', 'desc') // Order by created_at in descending order
            ->get();

        return response()->json($history); // Send as JSON response
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $recentWorklogs = $request->user()->worklogs()
            ->with('files')
            ->orderBy('log_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        ds($recentWorklogs);

        return inertia('Dashboard', [
            'recentWorklogs' => $recentWorklogs,
        ]);
    }
}

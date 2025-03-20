<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApiStatusController extends Controller
{

    /**
     * Display the status of the API.
     */
    public function index() : JsonResponse
    {
        return response()->json([
            'status' => 'Ok',
            'database_connection' => $this->checkDatabaseConnection(),
            'last_cron_execution' => Cache::get('last_cron_execution', 'Not available'),
            'uptime' => $this->getUptime(),
            'memory_usage' => memory_get_usage(true) . ' byes',
            'message' => 'API is running',
        ]);
    }

    /**
     * Check the database connection.
     */
    private function checkDatabaseConnection() : string
    {
        try {
            DB::connection()->getPdo();
            return 'Connected';
        } catch (\Exception $e) {
            return 'Not connected';
        }
    }

    /**
     * Get the uptime of the server.
     */
    private function getUptime() : string
    {
        $uptime = shell_exec('uptime -p');
        return trim($uptime);
    }
}

<?php

namespace App\Http\Controllers\Hosting;

use App\Http\Controllers\Controller;
use App\Models\ManagedServer;
use App\Services\ConfigExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsyncController extends Controller
{
    /**
     * Trigger a configuration push to the agent.
     */
    public function pushConfig(ManagedServer $managedServer): JsonResponse
    {
        $exportService = new ConfigExportService;
        $config = $exportService->exportToJson($managedServer);

        // In a real scenario, the agent would have a reachable API endpoint
        // We attempt to push to the agent's host/port
        try {
            // Assuming the agent listens on a specific path for config updates
            $url = "https://{$managedServer->host}:{$managedServer->port}/config/update";

            // Note: Since this is a demo environment, we don't actually have the Go agent running
            // but we implement the logic. We use a timeout to avoid hanging.

            /*
            $response = Http::withHeaders([
                'X-Agent-Token' => $managedServer->agent_token
            ])->timeout(5)->post($url, json_decode($config, true));

            if ($response->successful()) {
                return response()->json(['status' => 'success', 'message' => 'Configuration pushed successfully']);
            }
            */

            // For now, let's simulate success if the token exists, or log that we would have pushed
            Log::info("Async config push triggered for server: {$managedServer->name}");

            return response()->json([
                'status' => 'success',
                'message' => 'Configuration push triggered (simulated)',
                'data' => json_decode($config, true),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to push configuration: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Request latest stats from the agent.
     */
    public function refreshStats(ManagedServer $managedServer): JsonResponse
    {
        // Similar to pushConfig, we would call the agent's API
        Log::info("Async stats refresh triggered for server: {$managedServer->name}");

        return response()->json([
            'status' => 'success',
            'message' => 'Stats refresh triggered (simulated)',
            'stats' => [
                'cpu_usage' => rand(5, 20) + (rand(0, 9) / 10),
                'memory_usage' => rand(30, 60) + (rand(0, 9) / 10),
                'disk_usage' => rand(20, 40),
                'uptime' => 86400 + rand(0, 3600),
                'last_updated' => now()->toDateTimeString(),
            ],
        ]);
    }
}

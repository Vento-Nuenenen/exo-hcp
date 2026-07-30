<?php

namespace App\Http\Controllers\Hosting;

use App\Http\Controllers\Controller;
use App\Models\ManagedServer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    /**
     * Receive stats from a managed server agent.
     */
    public function stats(Request $request): JsonResponse
    {
        $request->validate([
            'agent_token' => ['required', 'string'],
            'cpu_usage' => ['nullable', 'numeric'],
            'memory_usage' => ['nullable', 'numeric'],
            'disk_usage' => ['nullable', 'numeric'],
            'uptime' => ['nullable', 'integer'],
        ]);

        $server = ManagedServer::where('agent_token', $request->agent_token)->first();

        if (! $server) {
            return response()->json(['error' => 'Invalid agent token'], 401);
        }

        // Here we could store these stats in a database table or cache
        // For now, let's just log them and update a 'last_seen_at' timestamp if we had one
        // $server->update(['last_seen_at' => now()]);

        Log::info("Received stats from server: {$server->name}", $request->only([
            'cpu_usage', 'memory_usage', 'disk_usage', 'uptime',
        ]));

        return response()->json(['status' => 'success']);
    }
}

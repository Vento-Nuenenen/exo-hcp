<?php

namespace App\Console\Commands\Hosting;

use App\Models\ManagedServer;
use App\Services\ConfigExportService;
use Illuminate\Console\Command;

class ExportServerConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hosting:export-config {server_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export the configuration for a managed server as JSON';

    /**
     * Execute the console command.
     */
    public function handle(ConfigExportService $service): int
    {
        $serverId = $this->argument('server_id');
        $server = ManagedServer::query()->with(['domains.subdomains'])->find($serverId);

        if (! $server) {
            $this->error("Managed Server with ID {$serverId} not found.");

            return 1;
        }

        $json = $service->exportToJson($server);

        $this->output->writeln($json);

        return 0;
    }
}

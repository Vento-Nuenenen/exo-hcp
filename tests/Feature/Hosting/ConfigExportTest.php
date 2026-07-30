<?php

use App\Models\Domain;
use App\Models\ManagedServer;
use App\Models\Subdomain;
use App\Models\User;

test('it can export server configuration as JSON via web', function () {
    $user = User::factory()->create();
    $server = ManagedServer::factory()->create([
        'name' => 'Test Server',
        'agent_token' => 'secret-agent-token',
    ]);

    $domain = Domain::factory()->create([
        'managed_server_id' => $server->id,
        'name' => 'example.com',
        'web_server' => 'nginx',
        'runtime' => 'php',
    ]);

    $subdomain = Subdomain::factory()->create([
        'domain_id' => $domain->id,
        'managed_server_id' => $server->id,
        'name' => 'app',
        'runtime' => 'node',
    ]);

    $this->actingAs($user)
        ->get(route('hosting.servers.export', $server))
        ->assertOk()
        ->assertJson([
            'name' => 'Test Server',
            'agent_token' => 'secret-agent-token',
            'domains' => [
                [
                    'name' => 'example.com',
                    'web_server' => 'nginx',
                    'runtime' => 'php',
                    'subdomains' => [
                        [
                            'name' => 'app',
                            'full_domain' => 'app.example.com',
                            'runtime' => 'node',
                        ],
                    ],
                ],
            ],
        ]);
});

test('it can export server configuration via CLI', function () {
    $server = ManagedServer::factory()->create([
        'name' => 'CLI Server',
        'agent_token' => 'cli-token',
    ]);

    $domain = Domain::factory()->create([
        'managed_server_id' => $server->id,
        'name' => 'cli.test',
    ]);

    $this->artisan('hosting:export-config', ['server_id' => $server->id])
        ->assertSuccessful();
});

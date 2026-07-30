<?php

use App\Models\ManagedServer;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->server = ManagedServer::factory()->create([
        'name' => 'Test Server',
        'host' => '1.2.3.4',
        'port' => 8080,
        'agent_token' => 'test-token',
        'is_active' => true,
    ]);
});

test('it can trigger a config push', function () {
    actingAs($this->user)
        ->post(route('hosting.servers.push-config', $this->server))
        ->assertOk()
        ->assertJsonStructure([
            'status',
            'message',
            'data',
        ])
        ->assertJson([
            'status' => 'success',
        ]);
});

test('it can refresh server stats', function () {
    actingAs($this->user)
        ->post(route('hosting.servers.refresh-stats', $this->server))
        ->assertOk()
        ->assertJsonStructure([
            'status',
            'message',
            'stats' => [
                'cpu_usage',
                'memory_usage',
                'disk_usage',
                'uptime',
                'last_updated',
            ],
        ])
        ->assertJson([
            'status' => 'success',
        ]);
});

test('api documentation is accessible', function () {
    actingAs($this->user)
        ->get('/api/docs')
        ->assertOk();
});

test('api resources are listed in entrypoint', function () {
    actingAs($this->user)
        ->get('/api', ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonFragment(['@type' => 'Entrypoint'])
        ->assertJsonFragment(['managedServer' => '/api/managed_servers'])
        ->assertJsonFragment(['domain' => '/api/domains'])
        ->assertJsonFragment(['subdomain' => '/api/subdomains']);
});

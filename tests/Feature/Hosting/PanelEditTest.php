<?php

use App\Models\Domain;
use App\Models\ManagedServer;
use App\Models\Subdomain;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('it can view the domain edit page', function () {
    $user = User::factory()->create();
    $server = ManagedServer::factory()->create();
    $domain = Domain::factory()->create(['managed_server_id' => $server->id]);

    $this->actingAs($user)
        ->get(route('hosting.domains.edit', $domain))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('hosting/domains/Edit')
            ->has('domain')
            ->has('users')
            ->has('managedServers')
        );
});

test('it can update a domain', function () {
    $user = User::factory()->create();
    $server = ManagedServer::factory()->create();
    $domain = Domain::factory()->create([
        'managed_server_id' => $server->id,
        'name' => 'old-domain.com',
    ]);

    $this->actingAs($user)
        ->patch(route('hosting.domains.update', $domain), [
            'name' => 'new-domain.com',
            'user_id' => $user->id,
            'managed_server_id' => $server->id,
            'document_root' => '/var/www/new',
            'web_server' => 'nginx',
            'runtime' => 'php',
        ])
        ->assertRedirect(route('hosting.index'));

    expect($domain->fresh()->name)->toBe('new-domain.com');
});

test('it can view the subdomain edit page', function () {
    $user = User::factory()->create();
    $server = ManagedServer::factory()->create();
    $domain = Domain::factory()->create(['managed_server_id' => $server->id]);
    $subdomain = Subdomain::factory()->create([
        'domain_id' => $domain->id,
        'managed_server_id' => $server->id,
    ]);

    $this->actingAs($user)
        ->get(route('hosting.subdomains.edit', $subdomain))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('hosting/subdomains/Edit')
            ->has('subdomain')
            ->has('domains')
            ->has('managedServers')
        );
});

test('it can view the server edit page', function () {
    $user = User::factory()->create();
    $server = ManagedServer::factory()->create();

    $this->actingAs($user)
        ->get(route('hosting.servers.edit', $server))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('hosting/servers/Edit')
            ->has('managedServer')
        );
});

test('it can update a managed server without changing the token', function () {
    $user = User::factory()->create();
    $server = ManagedServer::factory()->create([
        'name' => 'Old Server',
        'agent_token' => 'original-token',
    ]);

    $this->actingAs($user)
        ->patch(route('hosting.servers.update', $server), [
            'name' => 'New Server',
            'host' => '1.2.3.4',
            'port' => 2222,
            'agent_token' => '',
            'is_active' => true,
        ])
        ->assertRedirect(route('hosting.index'));

    $server->refresh();
    expect($server->name)->toBe('New Server');
    expect($server->agent_token)->toBe('original-token');
});

test('it can receive stats from agent', function () {
    $server = ManagedServer::factory()->create([
        'agent_token' => 'valid-token',
    ]);

    $this->postJson(route('hosting.agent.stats.public'), [
        'agent_token' => 'valid-token',
        'cpu_usage' => 10.5,
        'memory_usage' => 20.0,
        'disk_usage' => 30.0,
        'uptime' => 12345,
    ])
        ->assertOk()
        ->assertJson(['status' => 'success']);
});

test('it rejects stats with invalid token', function () {
    $this->postJson(route('hosting.agent.stats.public'), [
        'agent_token' => 'invalid-token',
    ])
        ->assertStatus(401);
});

<?php

use App\Models\Domain;
use App\Models\ManagedServer;
use App\Models\User;

test('guests are redirected from hosting panel', function () {
    $this->get(route('hosting.index'))->assertRedirect(route('login'));
});

test('authenticated users can see hosting panel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('hosting.index'))
        ->assertOk();
});

test('hosting panel can create managed server domain and subdomain with runtime and git configuration', function () {
    $admin = User::factory()->create();
    $owner = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('hosting.servers.store'), [
            'name' => 'Node-01',
            'host' => '10.0.0.5',
            'port' => 22,
            'agent_token' => 'token-123',
            'is_active' => true,
        ])
        ->assertRedirect(route('hosting.index'));

    $server = ManagedServer::query()->firstOrFail();

    $this->actingAs($admin)
        ->post(route('hosting.domains.store'), [
            'user_id' => $owner->id,
            'managed_server_id' => $server->id,
            'name' => 'example.test',
            'document_root' => '/var/www/example',
            'web_server' => 'nginx',
            'runtime' => 'ruby',
            'runtime_version' => '3.3',
            'git_repository' => 'https://github.com/acme/site.git',
            'git_branch' => 'main',
        ])
        ->assertRedirect(route('hosting.index'));

    $domain = Domain::query()->firstOrFail();

    $this->actingAs($admin)
        ->post(route('hosting.subdomains.store'), [
            'domain_id' => $domain->id,
            'managed_server_id' => $server->id,
            'name' => 'app',
            'document_root' => '/var/www/example/app',
            'runtime' => 'node',
            'runtime_version' => '20',
            'git_repository' => 'https://github.com/acme/app.git',
            'git_branch' => 'develop',
        ])
        ->assertRedirect(route('hosting.index'));

    $this->assertDatabaseHas('managed_servers', [
        'id' => $server->id,
        'name' => 'Node-01',
        'host' => '10.0.0.5',
    ]);

    $this->assertDatabaseHas('domains', [
        'id' => $domain->id,
        'managed_server_id' => $server->id,
        'runtime' => 'ruby',
        'git_repository' => 'https://github.com/acme/site.git',
    ]);

    $this->assertDatabaseHas('subdomains', [
        'domain_id' => $domain->id,
        'managed_server_id' => $server->id,
        'name' => 'app',
        'runtime' => 'node',
        'git_branch' => 'develop',
    ]);
});

test('hosting panel validates allowed web server and runtime values', function () {
    $admin = User::factory()->create();
    $owner = User::factory()->create();
    $server = ManagedServer::factory()->create();

    $this->actingAs($admin)
        ->post(route('hosting.domains.store'), [
            'user_id' => $owner->id,
            'managed_server_id' => $server->id,
            'name' => 'bad.test',
            'document_root' => '/var/www/bad',
            'web_server' => 'apache',
            'runtime' => 'python',
        ])
        ->assertSessionHasErrors(['web_server', 'runtime']);
});

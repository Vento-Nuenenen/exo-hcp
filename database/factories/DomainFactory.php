<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\ManagedServer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Domain>
 */
class DomainFactory extends Factory
{
    protected $model = Domain::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'managed_server_id' => ManagedServer::factory(),
            'name' => fake()->domainName(),
            'document_root' => '/var/www/html',
            'web_server' => 'nginx',
            'runtime' => 'php',
            'runtime_version' => '8.3',
            'git_repository' => fake()->url(),
            'git_branch' => 'main',
        ];
    }
}

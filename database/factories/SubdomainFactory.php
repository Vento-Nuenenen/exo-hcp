<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\ManagedServer;
use App\Models\Subdomain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subdomain>
 */
class SubdomainFactory extends Factory
{
    protected $model = Subdomain::class;

    public function definition(): array
    {
        return [
            'domain_id' => Domain::factory(),
            'managed_server_id' => ManagedServer::factory(),
            'name' => fake()->domainWord(),
            'document_root' => '/var/www/app',
            'runtime' => 'node',
            'runtime_version' => '20',
            'git_repository' => fake()->url(),
            'git_branch' => 'main',
        ];
    }
}

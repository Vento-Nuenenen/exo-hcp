<?php

namespace Database\Factories;

use App\Models\ManagedServer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ManagedServer>
 */
class ManagedServerFactory extends Factory
{
    protected $model = ManagedServer::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'host' => fake()->ipv4(),
            'port' => 22,
            'agent_token' => fake()->sha1(),
            'is_active' => true,
        ];
    }
}

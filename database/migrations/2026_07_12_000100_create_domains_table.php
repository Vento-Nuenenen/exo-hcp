<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('managed_servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('host');
            $table->unsignedInteger('port')->default(22);
            $table->string('agent_token');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('managed_server_id')->constrained('managed_servers')->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('document_root');
            $table->string('web_server')->default('nginx');
            $table->string('runtime')->default('php');
            $table->string('runtime_version')->nullable();
            $table->string('git_repository')->nullable();
            $table->string('git_branch')->nullable();
            $table->timestamps();
        });

        Schema::create('subdomains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->foreignId('managed_server_id')->constrained('managed_servers')->cascadeOnDelete();
            $table->string('name');
            $table->string('document_root');
            $table->string('runtime')->default('php');
            $table->string('runtime_version')->nullable();
            $table->string('git_repository')->nullable();
            $table->string('git_branch')->nullable();
            $table->timestamps();

            $table->unique(['domain_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subdomains');
        Schema::dropIfExists('domains');
        Schema::dropIfExists('managed_servers');
    }
};

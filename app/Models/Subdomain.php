<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use App\Traits\HasHostingFeatures;
use Database\Factories\SubdomainFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'domain_id',
    'managed_server_id',
    'name',
    'document_root',
    'runtime',
    'runtime_version',
    'git_repository',
    'git_branch',
])]
#[ApiResource]
class Subdomain extends Model
{
    /** @use HasFactory<SubdomainFactory> */
    use HasFactory, HasHostingFeatures;

    /**
     * @return BelongsTo<Domain, $this>
     */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * @return BelongsTo<ManagedServer, $this>
     */
    public function managedServer(): BelongsTo
    {
        return $this->belongsTo(ManagedServer::class);
    }
}

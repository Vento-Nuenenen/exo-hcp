<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use App\Traits\HasHostingFeatures;
use Database\Factories\DomainFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $document_root
 * @property string $web_server
 * @property string $runtime
 * @property string|null $runtime_version
 * @property string|null $git_repository
 * @property string|null $git_branch
 */
#[Fillable([
    'user_id',
    'managed_server_id',
    'name',
    'document_root',
    'web_server',
    'runtime',
    'runtime_version',
    'git_repository',
    'git_branch',
])]
#[ApiResource]
class Domain extends Model
{
    /** @use HasFactory<DomainFactory> */
    use HasFactory, HasHostingFeatures;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<ManagedServer, $this>
     */
    public function managedServer(): BelongsTo
    {
        return $this->belongsTo(ManagedServer::class);
    }

    /**
     * @return HasMany<Subdomain, $this>
     */
    public function subdomains(): HasMany
    {
        return $this->hasMany(Subdomain::class);
    }
}

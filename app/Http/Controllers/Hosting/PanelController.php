<?php

namespace App\Http\Controllers\Hosting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hosting\StoreDomainRequest;
use App\Http\Requests\Hosting\StoreHostingUserRequest;
use App\Http\Requests\Hosting\StoreManagedServerRequest;
use App\Http\Requests\Hosting\StoreSubdomainRequest;
use App\Http\Requests\Hosting\UpdateDomainRequest;
use App\Http\Requests\Hosting\UpdateHostingUserRequest;
use App\Http\Requests\Hosting\UpdateManagedServerRequest;
use App\Http\Requests\Hosting\UpdateSubdomainRequest;
use App\Models\Domain;
use App\Models\ManagedServer;
use App\Models\Subdomain;
use App\Models\User;
use App\Services\ConfigExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class PanelController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('hosting/Index', [
            'users' => User::query()->select('id', 'name', 'email')->latest()->get(),
            'managedServers' => ManagedServer::query()->latest()->get(),
            'domains' => Domain::query()
                ->with(['user:id,name,email', 'managedServer:id,name,host'])
                ->latest()
                ->get(),
            'subdomains' => Subdomain::query()
                ->with(['domain:id,name', 'managedServer:id,name,host'])
                ->latest()
                ->get(),
        ]);
    }

    public function storeUser(StoreHostingUserRequest $request): RedirectResponse
    {
        User::query()->create([
            ...$request->safe()->except('password'),
            'password' => Hash::make($request->string('password')),
        ]);

        return to_route('hosting.index');
    }

    public function updateUser(UpdateHostingUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->safe()->except('password');

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->string('password'));
        }

        $user->update($validated);

        return to_route('hosting.index');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        $user->delete();

        return to_route('hosting.index');
    }

    public function storeDomain(StoreDomainRequest $request): RedirectResponse
    {
        Domain::query()->create($request->validated());

        return to_route('hosting.index');
    }

    public function storeManagedServer(StoreManagedServerRequest $request): RedirectResponse
    {
        ManagedServer::query()->create($request->validated());

        return to_route('hosting.index');
    }

    public function updateManagedServer(UpdateManagedServerRequest $request, ManagedServer $managedServer): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['agent_token'])) {
            unset($data['agent_token']);
        }

        $managedServer->update($data);

        return to_route('hosting.index');
    }

    public function destroyManagedServer(ManagedServer $managedServer): RedirectResponse
    {
        $managedServer->delete();

        return to_route('hosting.index');
    }

    public function exportManagedServerConfig(ManagedServer $managedServer, ConfigExportService $service): JsonResponse
    {
        $managedServer->load(['domains.subdomains']);
        $config = $service->exportToJson($managedServer);

        return response()->json(json_decode($config, true));
    }

    public function editDomain(Domain $domain)
    {
        return Inertia::render('hosting/domains/Edit', [
            'domain' => $domain->load(['user', 'managedServer']),
            'users' => User::all(),
            'managedServers' => ManagedServer::all(),
        ]);
    }

    public function editSubdomain(Subdomain $subdomain)
    {
        return Inertia::render('hosting/subdomains/Edit', [
            'subdomain' => $subdomain->load(['domain', 'managedServer']),
            'domains' => Domain::all(),
            'managedServers' => ManagedServer::all(),
        ]);
    }

    public function editManagedServer(ManagedServer $managedServer)
    {
        return Inertia::render('hosting/servers/Edit', [
            'managedServer' => $managedServer,
        ]);
    }

    public function updateDomain(UpdateDomainRequest $request, Domain $domain): RedirectResponse
    {
        $domain->update($request->validated());

        return to_route('hosting.index');
    }

    public function destroyDomain(Domain $domain): RedirectResponse
    {
        $domain->delete();

        return to_route('hosting.index');
    }

    public function storeSubdomain(StoreSubdomainRequest $request): RedirectResponse
    {
        Subdomain::query()->create($request->validated());

        return to_route('hosting.index');
    }

    public function updateSubdomain(UpdateSubdomainRequest $request, Subdomain $subdomain): RedirectResponse
    {
        $subdomain->update($request->validated());

        return to_route('hosting.index');
    }

    public function destroySubdomain(Subdomain $subdomain): RedirectResponse
    {
        $subdomain->delete();

        return to_route('hosting.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\ClientCreated;
use App\Events\ClientDeleted;
use App\Events\ClientUpdated;
use App\Events\CreatingClient;
use App\Events\DeletingClient;
use App\Events\UpdatingClient;
use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\EditClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Throwable;

class ClientController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
            'role:admin',
        ];
    }

    public function index(Request $request)
    {
        if ($request->user()->cannot('list', Client::class)) {
            abort(403);
        }

        $filters = $request->only(['first_name', 'last_name', 'email', 'phone']);

        $clients = Client::filter($filters)->sort($request);

        $clients = $clients->paginate();

        return ClientResource::collection($clients);
    }

    public function show(Client $client)
    {
        if (auth()->user()->cannot('view', $client)) {
            abort(403);
        }

        return new ClientResource($client);
    }

    /**
     * @throws Throwable
     */
    public function store(CreateClientRequest $request)
    {
        return DB::transaction(function () use ($request) {
            event(new CreatingClient());
            $client = Client::create($request->validated());
            event(new ClientCreated($client));

            return response(new ClientResource($client), Response::HTTP_CREATED);
        });
    }

    /**
     * @throws Throwable
     */
    public function update(EditClientRequest $request, Client $client)
    {
        return DB::transaction(function () use ($request, $client) {
            event(new UpdatingClient());
            $client->update($request->validated());
            event(new ClientUpdated($client));

            return response(new ClientResource($client), Response::HTTP_OK);
        });
    }

    /**
     * @throws Throwable
     */
    public function destroy(Client $client): Response
    {
        if (auth()->user()->cannot('delete', $client)) {
            abort(403);
        }

        return DB::transaction(function () use ($client) {
            event(new DeletingClient($client));
            $client->delete();
            event(new ClientDeleted($client));

            return response(status: Response::HTTP_NO_CONTENT);
        });
    }
}

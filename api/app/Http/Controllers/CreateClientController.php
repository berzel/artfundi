<?php

namespace App\Http\Controllers;

use App\Events\ClientCreated;
use App\Events\CreatingClient;
use App\Http\Requests\CreateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateClientController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            'auth:sanctum',
            'role:admin',
        ];
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

            return new ClientResource($client);
        });
    }


}

<?php

namespace App\Http\Controllers;

use App\Events\ClientUpdated;
use App\Events\UpdatingClient;
use App\Http\Requests\EditClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Throwable;

class EditClientController extends Controller implements HasMiddleware
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
    public function update(EditClientRequest $request, Client $client)
    {
        return DB::transaction(function () use ($request, $client) {
            event(new UpdatingClient());
            $client->update($request->validated());
            event(new ClientUpdated($client));

            return new ClientResource($client);
        });
    }
}

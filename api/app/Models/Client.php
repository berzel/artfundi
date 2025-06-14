<?php

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
    ];


    public function scopeFilter($query, $filters)
    {
        return $query
            ->when($filters['first_name'] ?? null, fn($q, $value) => $q->where('first_name', 'like', "%$value%"))
            ->when($filters['last_name'] ?? null, fn($q, $value) => $q->where('last_name', 'like', "%$value%"))
            ->when($filters['email'] ?? null, fn($q, $value) => $q->where('email', 'like', "%$value%"))
            ->when($filters['phone'] ?? null, fn($q, $value) => $q->where('phone', 'like', "%$value%"));
    }

    public function scopeSort($query, $request)
    {
        $allowedSorts = ['first_name', 'last_name', 'email', 'phone', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        $sort_by = in_array($request->input('order_by'), $allowedSorts)
            ? $request->input('order_by')
            : 'created_at';

        $sort_dir = in_array($request->input('order'), $allowedDirections)
            ? $request->input('order')
            : 'desc';

        return $query->orderBy($sort_by, $sort_dir);
    }
}

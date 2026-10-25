<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'photo',
    ];

    /**
     * Relasi 1:1 dengan User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

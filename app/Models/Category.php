<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relasi M:N dengan Gear.
     */
    public function gears(): BelongsToMany
    {
        return $this->belongsToMany(Gear::class, 'category_gear');
    }
}

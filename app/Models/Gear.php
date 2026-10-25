<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gear extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'unit_code',
        'name',
        'description',
        'photo',
        'status',
    ];

    /**
     * Relasi M:N dengan Category.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_gear');
    }

    /**
     * Relasi 1:N dengan Rental.
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Cek apakah perlengkapan tersedia untuk disewa.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'tersedia';
    }
}

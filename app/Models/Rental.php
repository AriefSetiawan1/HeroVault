<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'gear_id',
        'rented_at',
        'due_at',
        'returned_at',
        'status',
        'late_days',
        'fine',
        'return_condition',
        'processed_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rented_at' => 'date',
            'due_at' => 'date',
            'returned_at' => 'date',
        ];
    }

    /**
     * Relasi: penyewa (anggota).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: perlengkapan yang disewa.
     */
    public function gear(): BelongsTo
    {
        return $this->belongsTo(Gear::class);
    }

    /**
     * Relasi: admin yang memproses pengembalian.
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Cek apakah sewa sudah terlambat.
     */
    public function isLate(): bool
    {
        return $this->status === 'dipinjam' && now()->greaterThan($this->due_at);
    }
}

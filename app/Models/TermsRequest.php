<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Solicitud de un usuario para volver a ver los términos y condiciones
 * (su enlace de un solo uso ya fue gastado).
 */
class TermsRequest extends Model
{
    protected $fillable = ['user_id', 'status', 'attended_by', 'attended_at'];

    protected $casts = ['attended_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attended_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}

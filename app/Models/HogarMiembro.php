<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HogarMiembro extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'hogar_miembros';

    protected $fillable = [
        'hogar_id',
        'user_id',
        'parentesco_id',
        'rol',
        'apodo',
        'estado',
    ];

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parentesco(): BelongsTo
    {
        return $this->belongsTo(Parentesco::class, 'parentesco_id');
    }

    public function capacitaciones(): HasMany
    {
        return $this->hasMany(Capacitacion::class, 'hogar_miembro_id');
    }
}

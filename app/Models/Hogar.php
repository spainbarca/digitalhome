<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hogar extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'hogares';

    protected $fillable = [
        'nombre',
        'descripcion',
        'owner_id',
        'avatar_url',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function miembros(): HasMany
    {
        return $this->hasMany(HogarMiembro::class, 'hogar_id');
    }

    public function documentosServicio(): HasMany
    {
        return $this->hasMany(DocumentoServicio::class, 'hogar_id');
    }

    public function etiquetas(): HasMany
    {
        return $this->hasMany(Etiqueta::class, 'hogar_id');
    }

    public function recordatorios(): HasMany
    {
        return $this->hasMany(Recordatorio::class, 'hogar_id');
    }
}

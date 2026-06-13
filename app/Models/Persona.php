<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Persona extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'personas';

    protected $fillable = [
        'hogar_id',
        'tipo_documento_id',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'numero_documento',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'email',
        'avatar_url',
        'tipo_sangre',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'activo'           => 'boolean',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    public function getFotoUrlAttribute(): ?string
    {
        $foto = $this->avatar_url;
        if (empty($foto)) {
            return null;
        }
        return Str::startsWith($foto, ['http://', 'https://'])
            ? $foto
            : asset('storage/' . $foto);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'persona_id');
    }
}

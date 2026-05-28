<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerfilMiembro extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'perfiles_miembro';

    protected $fillable = [
        'user_id',
        'fecha_nacimiento',
        'genero',
        'telefono',
        'avatar_url',
        'numero_dni',
        'tipo_sangre',
        'alergias',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

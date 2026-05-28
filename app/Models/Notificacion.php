<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notificacion extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'titulo',
        'cuerpo',
        'notificable_id',
        'notificable_type',
        'leida',
        'leida_en',
    ];

    protected function casts(): array
    {
        return [
            'leida'    => 'boolean',
            'leida_en' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function notificable(): MorphTo
    {
        return $this->morphTo('notificable');
    }
}

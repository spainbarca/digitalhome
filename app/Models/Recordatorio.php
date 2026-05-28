<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recordatorio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'recordatorios';

    protected $fillable = [
        'user_id',
        'hogar_id',
        'titulo',
        'descripcion',
        'fecha_vencimiento',
        'fecha_aviso',
        'recordable_id',
        'recordable_type',
        'estado',
        'repetir',
    ];

    protected function casts(): array
    {
        return [
            'fecha_vencimiento' => 'date',
            'fecha_aviso'       => 'datetime',
            'repetir'           => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function recordable(): MorphTo
    {
        return $this->morphTo('recordable');
    }
}

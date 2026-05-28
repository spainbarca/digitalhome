<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LecturaConsumo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'lecturas_consumo';

    protected $fillable = [
        'documento_id',
        'lectura_anterior',
        'lectura_actual',
        'consumo',
    ];

    protected function casts(): array
    {
        return [
            'lectura_anterior' => 'decimal:4',
            'lectura_actual'   => 'decimal:4',
            'consumo'          => 'decimal:4',
        ];
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(DocumentoServicio::class, 'documento_id');
    }
}

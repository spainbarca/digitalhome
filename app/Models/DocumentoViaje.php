<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoViaje extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'documentos_viaje';

    protected $fillable = [
        'hogar_id',
        'viaje_id',
        'reserva_id',
        'tipo_documento_viaje_id',
        'nombre',
        'archivo_path',
        'fecha_emision',
        'fecha_vencimiento',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision'    => 'date',
            'fecha_vencimiento'=> 'date',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function viaje(): BelongsTo
    {
        return $this->belongsTo(Viaje::class, 'viaje_id');
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }

    public function tipoDocumentoViaje(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoViaje::class, 'tipo_documento_viaje_id');
    }

    public function getArchivoUrlAttribute(): ?string
    {
        return $this->archivo_path ? asset('storage/' . $this->archivo_path) : null;
    }
}

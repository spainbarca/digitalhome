<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoMascota extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'documentos_mascota';

    protected $fillable = [
        'mascota_id',
        'atencion_veterinaria_id',
        'evento_sanitario_id',
        'tipo_documento_mascota_id',
        'nombre',
        'archivo_path',
        'fecha',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    public function atencionVeterinaria(): BelongsTo
    {
        return $this->belongsTo(AtencionVeterinaria::class, 'atencion_veterinaria_id');
    }

    public function eventoSanitario(): BelongsTo
    {
        return $this->belongsTo(EventoSanitario::class, 'evento_sanitario_id');
    }

    public function tipoDocumentoMascota(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoMascota::class, 'tipo_documento_mascota_id');
    }

    // Devuelve el padre real (el primero no-null entre mascota / atención / evento).
    // La validación "exactamente un parent" va en el FormRequest, no aquí.
    public function parent(): Mascota|AtencionVeterinaria|EventoSanitario|null
    {
        return $this->mascota ?? $this->atencionVeterinaria ?? $this->eventoSanitario;
    }
}

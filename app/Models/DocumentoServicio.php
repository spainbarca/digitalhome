<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoServicio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'documentos_servicio';

    protected $fillable = [
        'cuenta_id',
        'subido_por',
        'tipo_documento_id',
        'estado_pago_id',
        'metodo_pago_id',
        'moneda_id',
        'hogar_id',
        'visibilidad_id',
        'periodo_inicio',
        'periodo_fin',
        'monto_total',
        'fecha_vencimiento',
        'fecha_pago',
        'archivo_url',
        'extension',
        'tamano_bytes',
        'documento_url',
        'documento_extension',
        'documento_tamano_bytes',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'periodo_inicio'         => 'date',
            'periodo_fin'            => 'date',
            'fecha_vencimiento'      => 'date',
            'fecha_pago'             => 'date',
            'monto_total'            => 'decimal:2',
            'tamano_bytes'           => 'integer',
            'documento_tamano_bytes' => 'integer',
        ];
    }

    public function cuenta(): BelongsTo
    {
        return $this->belongsTo(CuentaServicio::class, 'cuenta_id');
    }

    public function subidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subido_por');
    }

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoServicio::class, 'tipo_documento_id');
    }

    public function estadoPago(): BelongsTo
    {
        return $this->belongsTo(EstadoPago::class, 'estado_pago_id');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function visibilidad(): BelongsTo
    {
        return $this->belongsTo(TipoVisibilidad::class, 'visibilidad_id');
    }

    public function tipoArchivo(): BelongsTo
    {
        return $this->belongsTo(TipoArchivo::class, 'extension', 'extension');
    }

    public function lecturaConsumo(): HasOne
    {
        return $this->hasOne(LecturaConsumo::class, 'documento_id');
    }

    public function accesos(): MorphMany
    {
        return $this->morphMany(DocumentoAcceso::class, 'documento');
    }

    public function historialVersiones(): MorphMany
    {
        return $this->morphMany(HistorialVersion::class, 'versionable');
    }

    public function recordatorios(): MorphMany
    {
        return $this->morphMany(Recordatorio::class, 'recordable');
    }

    public function etiquetas(): MorphToMany
    {
        return $this->morphToMany(
            Etiqueta::class,
            'etiquetable',
            'etiquetables',
            'etiquetable_id',
            'etiqueta_id'
        );
    }
}

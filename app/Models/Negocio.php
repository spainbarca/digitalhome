<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Negocio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'negocios';

    protected $fillable = [
        'hogar_id',
        'empresa_id',
        'miembro_id',
        'regimen_tributario_id',
        'tipo_sociedad_id',
        'estado_negocio_id',
        'nombre',
        'ciiu',
        'fecha_constitucion',
        'fecha_inicio_actividades',
        'partida_registral',
        'oficina_registral',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_constitucion'       => 'date',
            'fecha_inicio_actividades' => 'date',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function miembro(): BelongsTo
    {
        return $this->belongsTo(HogarMiembro::class, 'miembro_id');
    }

    public function regimenTributario(): BelongsTo
    {
        return $this->belongsTo(RegimenTributario::class, 'regimen_tributario_id');
    }

    public function tipoSociedad(): BelongsTo
    {
        return $this->belongsTo(TipoSociedad::class, 'tipo_sociedad_id');
    }

    public function estadoNegocio(): BelongsTo
    {
        return $this->belongsTo(EstadoNegocio::class, 'estado_negocio_id');
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'negocio_id');
    }

    public function pagosNegocio(): HasMany
    {
        return $this->hasMany(PagoNegocio::class, 'negocio_id');
    }

    public function documentosNegocio(): HasMany
    {
        return $this->hasMany(DocumentoNegocio::class, 'negocio_id');
    }

    public function productosFinancieros(): HasMany
    {
        return $this->hasMany(ProductoFinanciero::class, 'negocio_id');
    }

    public function getSectorAttribute(): ?Sector
    {
        return $this->empresa?->sector;
    }
}

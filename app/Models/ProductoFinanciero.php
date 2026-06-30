<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoFinanciero extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'productos_financieros';

    protected $fillable = [
        'entidad_financiera_id',
        'tipo_producto_financiero_id',
        'estado_producto_id',
        'miembro_id',
        'producto_padre_id',
        'moneda_id',
        'alias',
        'numero_cuenta',
        'cci',
        'ultimos_digitos',
        'saldo_actual',
        'linea_credito',
        'tasa_tea',
        'tasa_tcea',
        'cuota',
        'plazo_meses',
        'dia_corte',
        'dia_pago',
        'fecha_apertura',
        'fecha_vencimiento',
        'es_mancomunada',
        'notas',
        'negocio_id',
        'mascota_id',
    ];

    protected function casts(): array
    {
        return [
            'saldo_actual'     => 'decimal:2',
            'linea_credito'    => 'decimal:2',
            'cuota'            => 'decimal:2',
            'tasa_tea'         => 'decimal:3',
            'tasa_tcea'        => 'decimal:3',
            'plazo_meses'      => 'integer',
            'dia_corte'        => 'integer',
            'dia_pago'         => 'integer',
            'fecha_apertura'   => 'date',
            'fecha_vencimiento'=> 'date',
            'es_mancomunada'   => 'boolean',
        ];
    }

    public function entidadFinanciera(): BelongsTo
    {
        return $this->belongsTo(EntidadFinanciera::class, 'entidad_financiera_id');
    }

    public function tipoProductoFinanciero(): BelongsTo
    {
        return $this->belongsTo(TipoProductoFinanciero::class, 'tipo_producto_financiero_id');
    }

    public function estadoProducto(): BelongsTo
    {
        return $this->belongsTo(EstadoProducto::class, 'estado_producto_id');
    }

    public function miembro(): BelongsTo
    {
        return $this->belongsTo(HogarMiembro::class, 'miembro_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function productoPadre(): BelongsTo
    {
        return $this->belongsTo(ProductoFinanciero::class, 'producto_padre_id');
    }

    public function productosHijos(): HasMany
    {
        return $this->hasMany(ProductoFinanciero::class, 'producto_padre_id');
    }

    public function transacciones(): HasMany
    {
        return $this->hasMany(Transaccion::class, 'producto_financiero_id');
    }

    public function transaccionesRecibidas(): HasMany
    {
        return $this->hasMany(Transaccion::class, 'producto_destino_id');
    }

    public function documentosFinancieros(): HasMany
    {
        return $this->hasMany(DocumentoFinanciero::class, 'producto_financiero_id');
    }

    public function beneficiarios(): HasMany
    {
        return $this->hasMany(Beneficiario::class, 'producto_financiero_id');
    }

    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class, 'negocio_id');
    }

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }
}

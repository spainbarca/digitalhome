<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagoEducativo extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'pagos_educativos';

    protected $fillable = [
        'hogar_id',
        'matricula_id',
        'moneda_id',
        'concepto',
        'mes_correspondiente',
        'monto',
        'fecha_vencimiento',
        'fecha_pago',
        'estado',
        'comprobante_path',
        'comprobante_nombre_original',
        'comprobante_mime',
        'comprobante_size',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'monto'            => 'decimal:2',
            'fecha_vencimiento' => 'date',
            'fecha_pago'        => 'date',
            'estado'            => 'string',
            'comprobante_size'  => 'integer',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class, 'matricula_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }
}

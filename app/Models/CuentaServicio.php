<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuentaServicio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'cuentas_servicio';

    protected $fillable = [
        'propiedad_id',
        'proveedor_id',
        'titular_user_id',
        'numero_cuenta',
        'estado',
        'fecha_inicio',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
        ];
    }

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(PropiedadInmueble::class, 'propiedad_id');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(ProveedorServicio::class, 'proveedor_id');
    }

    public function titular(): BelongsTo
    {
        return $this->belongsTo(User::class, 'titular_user_id');
    }

    public function documentosServicio(): HasMany
    {
        return $this->hasMany(DocumentoServicio::class, 'cuenta_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DocumentoAcceso extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'documento_acceso';

    protected $fillable = [
        'documento_id',
        'documento_type',
        'user_id',
        'permiso',
    ];

    public function documento(): MorphTo
    {
        return $this->morphTo('documento');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

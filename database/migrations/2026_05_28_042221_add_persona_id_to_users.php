<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Vínculo 1:1 con personas — nullable porque
            // un user puede existir antes de tener persona asignada
            $table->foreignUuid('persona_id')
                  ->nullable()
                  ->unique()          // garantiza 1:1 a nivel de BD
                  ->constrained('personas')
                  ->nullOnDelete()
                  ->after('id');

            // Proveedor OAuth (google, etc.)
            $table->string('provider', 30)->nullable()->after('remember_token');
            $table->string('provider_id')->nullable()->after('provider');
            $table->string('provider_token')->nullable()->after('provider_id');
            $table->text('avatar_url')->nullable()->after('provider_token'); // URL larga de Google

            $table->index(['provider', 'provider_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['persona_id']);
            $table->dropUnique(['persona_id']);
            $table->dropColumn(['persona_id', 'provider', 'provider_id', 'provider_token', 'avatar_url']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entidades_financieras', function (Blueprint $table) {
            $table->renameColumn('logo_url', 'logo_path');
            $table->string('banner_path')->nullable()->after('logo_path');
        });
    }

    public function down(): void
    {
        Schema::table('entidades_financieras', function (Blueprint $table) {
            $table->dropColumn('banner_path');
            $table->renameColumn('logo_path', 'logo_url');
        });
    }
};

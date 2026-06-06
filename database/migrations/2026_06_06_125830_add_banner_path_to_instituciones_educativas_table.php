<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('instituciones_educativas', function (Blueprint $table) {
            $table->string('banner_path', 500)->nullable()->after('imagen_path');
        });
    }

    public function down(): void
    {
        Schema::table('instituciones_educativas', function (Blueprint $table) {
            $table->dropColumn('banner_path');
        });
    }
};

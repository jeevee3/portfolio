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
    Schema::table('artworks', function (Blueprint $table) {
        $table->string('type')->default('portfolio')->after('image_path');
    });
}

public function down(): void
{
    Schema::table('artworks', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}
};
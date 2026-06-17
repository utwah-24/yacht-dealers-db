<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catamarans', function (Blueprint $table) {
            $table->json('destinations')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('catamarans', function (Blueprint $table) {
            $table->text('destinations')->nullable()->change();
        });
    }
};

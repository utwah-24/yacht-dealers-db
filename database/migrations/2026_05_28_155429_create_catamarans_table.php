<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catamarans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('services_provided');
            $table->text('description');
            $table->text('destinations');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catamarans');
    }
};

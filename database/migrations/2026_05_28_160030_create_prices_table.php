<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catamaran_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['Dar tours', 'Zanzibar']);
            $table->enum('charter', ['Half day', 'Full day', 'Live Onboard']);
            $table->unsignedSmallInteger('duration');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};

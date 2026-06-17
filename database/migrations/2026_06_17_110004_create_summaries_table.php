<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_id')->nullable()->constrained()->nullOnDelete();
            $table->string('catamaran_name');
            $table->string('departure');
            $table->string('destination');
            $table->text('charter_details');
            $table->string('food')->nullable();
            $table->string('drinks')->nullable();
            $table->boolean('dj_service')->default(false);
            $table->boolean('marine_ticket_non_tanzanian')->default(false);
            $table->boolean('marine_ticket_tanzanian')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summaries');
    }
};

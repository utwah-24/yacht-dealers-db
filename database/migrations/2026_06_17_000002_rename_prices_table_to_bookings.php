<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('prices', 'bookings');

        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('type', 'location_type');
            $table->renameColumn('charter', 'charter_type');
            $table->renameColumn('price', 'charter_price');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('location_type')->change();
            $table->string('charter_type')->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('location_type', ['Dar tours', 'Zanzibar'])->change();
            $table->enum('charter_type', ['Half day', 'Full day', 'Live Onboard'])->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('location_type', 'type');
            $table->renameColumn('charter_type', 'charter');
            $table->renameColumn('charter_price', 'price');
        });

        Schema::rename('bookings', 'prices');
    }
};

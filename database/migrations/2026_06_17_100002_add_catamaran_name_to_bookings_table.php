<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('catamaran_name')->nullable()->after('catamaran_id');
        });

        DB::statement('
            UPDATE bookings
            INNER JOIN catamarans ON bookings.catamaran_id = catamarans.id
            SET bookings.catamaran_name = catamarans.name
        ');
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('catamaran_name');
        });
    }
};

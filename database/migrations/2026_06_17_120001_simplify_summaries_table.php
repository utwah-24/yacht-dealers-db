<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->dropColumn([
                'catamaran_name',
                'departure',
                'destination',
                'charter_details',
                'food',
                'drinks',
                'dj_service',
                'marine_ticket_non_tanzanian',
                'marine_ticket_tanzanian',
            ]);
        });

        Schema::table('summaries', function (Blueprint $table) {
            $table->foreignId('catamaran_photo_id')
                ->nullable()
                ->after('guest_id')
                ->constrained('catamaran_photos')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->dropForeign(['catamaran_photo_id']);
            $table->dropColumn('catamaran_photo_id');
        });

        Schema::table('summaries', function (Blueprint $table) {
            $table->string('catamaran_name')->after('guest_id');
            $table->string('departure')->after('catamaran_name');
            $table->string('destination')->after('departure');
            $table->text('charter_details')->after('destination');
            $table->string('food')->nullable()->after('charter_details');
            $table->string('drinks')->nullable()->after('food');
            $table->boolean('dj_service')->default(false)->after('drinks');
            $table->boolean('marine_ticket_non_tanzanian')->default(false)->after('dj_service');
            $table->boolean('marine_ticket_tanzanian')->default(false)->after('marine_ticket_non_tanzanian');
        });
    }
};

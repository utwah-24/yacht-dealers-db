<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('personal_registrations', 'personal_requests');

        Schema::table('personal_requests', function (Blueprint $table) {
            $table->dropColumn(['others', 'allergies', 'special']);
        });
    }

    public function down(): void
    {
        Schema::table('personal_requests', function (Blueprint $table) {
            $table->text('others')->nullable()->after('status');
            $table->text('allergies')->nullable()->after('others');
            $table->text('special')->nullable()->after('allergies');
        });

        Schema::rename('personal_requests', 'personal_registrations');
    }
};

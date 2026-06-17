<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('extras', function (Blueprint $table) {
            $table->dropColumn(['food', 'food_price', 'food_description']);
        });

        Schema::table('extras', function (Blueprint $table) {
            $table->string('additional_services')->after('booking_id');
            $table->decimal('additional_cost', 10, 2)->default(0)->after('additional_services');
            $table->boolean('status')->default(false)->after('additional_cost');
            $table->text('description')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('extras', function (Blueprint $table) {
            $table->dropColumn(['additional_services', 'additional_cost', 'status', 'description']);
        });

        Schema::table('extras', function (Blueprint $table) {
            $table->boolean('food')->default(false)->after('booking_id');
            $table->decimal('food_price', 10, 2)->nullable()->after('food');
            $table->text('food_description')->nullable()->after('food_price');
        });
    }
};

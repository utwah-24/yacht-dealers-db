<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catamaran_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catamaran_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $catamarans = DB::table('catamarans')
            ->whereNotNull('photos')
            ->where('photos', '!=', '')
            ->get(['id', 'photos']);

        foreach ($catamarans as $catamaran) {
            DB::table('catamaran_photos')->insert([
                'catamaran_id' => $catamaran->id,
                'path' => $catamaran->photos,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('catamarans', function (Blueprint $table) {
            $table->dropColumn('photos');
        });
    }

    public function down(): void
    {
        Schema::table('catamarans', function (Blueprint $table) {
            $table->string('photos')->nullable()->after('description');
        });

        $photos = DB::table('catamaran_photos')
            ->orderBy('sort_order')
            ->get(['catamaran_id', 'path']);

        foreach ($photos as $photo) {
            DB::table('catamarans')
                ->where('id', $photo->catamaran_id)
                ->update(['photos' => $photo->path]);
        }

        Schema::dropIfExists('catamaran_photos');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_preorder')->default(false)->index()->after('is_active');
            $table->timestamp('published_at')->nullable()->index()->after('is_preorder');
        });

        DB::table('products')
            ->where('is_active', true)
            ->whereNull('published_at')
            ->update(['published_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_preorder', 'published_at']);
        });
    }
};

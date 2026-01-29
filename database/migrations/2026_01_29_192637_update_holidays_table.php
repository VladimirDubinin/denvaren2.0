<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->tinyInteger('repeat')->default(false)->nullable()->comment('Повторять уведомление каждый год')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('telegraph_chats', function (Blueprint $table) {
            $table->tinyInteger('repeat')->nullable()->comment('Повторять уведомление каждый год')->change();
        });
    }
};

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
        Schema::table('chats', function (Blueprint $table) {
            $table->boolean('waiting_add_answer')->default(false)->comment('Идёт процесс добавления события');
            $table->boolean('waiting_delete_answer')->default(false)->comment('Идёт процесс удаления события');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropColumn(['waiting_add_answer', 'waiting_delete_answer']);
        });
    }
};

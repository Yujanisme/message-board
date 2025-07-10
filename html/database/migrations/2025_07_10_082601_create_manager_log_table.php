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
        Schema::create('manager_log', function (Blueprint $table) {
            $table->id();
            $table->string('account')->comment('管理員帳號');
            $table->string('action_type')->comment('操作類型');
            $table->integer('action')->comment('操作內容');
            $table->timestamp('created_at')->comment('建立時間');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_log');
    }
};

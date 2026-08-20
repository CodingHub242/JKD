<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->timestamp('agent_joined_at')->nullable();
            $table->timestamp('visitor_typing_at')->nullable();
            $table->timestamp('agent_typing_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn(['agent_joined_at', 'visitor_typing_at', 'agent_typing_at']);
        });
    }
};

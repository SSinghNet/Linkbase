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
        Schema::create('link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();  // 45 chars covers IPv6
            $table->text('user_agent')->nullable();
            $table->string('country', 2)->nullable();      // ISO country code e.g. "US"
            $table->timestamp('clicked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_clicks');
    }
};

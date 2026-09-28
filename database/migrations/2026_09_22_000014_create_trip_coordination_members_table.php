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
        Schema::create('trip_coordination_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suggestion_id')->constrained('trip_coordination_suggestions')->cascadeOnDelete();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->unique(['suggestion_id', 'trip_id'], 'uq_coordination_member');
            $table->index('user_id', 'idx_coordination_member_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_coordination_members');
    }
};

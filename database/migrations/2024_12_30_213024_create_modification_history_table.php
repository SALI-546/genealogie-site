<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModificationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modification_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('modification_proposals')->onDelete('cascade');
            $table->enum('action', ['created', 'approved', 'rejected', 'executed', 'invalidated']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modification_history');
    }
}

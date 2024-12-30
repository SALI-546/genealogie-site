<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalVotesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proposal_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('modification_proposals')->onDelete('cascade');
            $table->foreignId('voter_id')->constrained('users')->onDelete('cascade');
            $table->enum('vote', ['approve', 'reject']);
            $table->timestamps();

            $table->unique(['proposal_id', 'voter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_votes');
    }
}

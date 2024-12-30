<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'voter_id',
        'vote',
    ];

    public function proposal()
    {
        return $this->belongsTo(ModificationProposal::class, 'proposal_id');
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }
}

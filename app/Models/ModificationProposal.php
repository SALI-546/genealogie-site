<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModificationProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposer_id',
        'person_id',
        'type',
        'data',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposer_id');
    }

    public function votes()
    {
        return $this->hasMany(ProposalVote::class, 'proposal_id');
    }

    public function history()
    {
        return $this->hasMany(ModificationHistory::class, 'proposal_id');
    }
}

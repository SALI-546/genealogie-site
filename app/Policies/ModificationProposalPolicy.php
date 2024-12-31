<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ModificationProposal;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModificationProposalPolicy
{
    use HandlesAuthorization;

    /**
     * Déterminer si l'utilisateur peut proposer une modification.
     */
    public function propose(User $user)
    {
        return true; // Tous les utilisateurs authentifiés peuvent proposer
    }

    /**
     * Déterminer si l'utilisateur peut voter sur une proposition.
     */
    public function vote(User $user, ModificationProposal $proposal)
    {
        // Empêcher le proposer de voter sur sa propre proposition
        return $user->id !== $proposal->proposer_id;
    }

    /**
     * Déterminer si l'utilisateur peut exécuter une proposition.
     */
    public function execute(User $user, ModificationProposal $proposal)
    {
        // Définir des règles spécifiques si nécessaire
        return true;
    }
}

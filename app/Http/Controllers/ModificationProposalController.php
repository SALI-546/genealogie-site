<?php

namespace App\Http\Controllers;

use App\Models\ModificationProposal;
use App\Models\ProposalVote;
use App\Models\ModificationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Person;
use App\Models\User;
use App\Notifications\ModificationProposed;


class ModificationProposalController extends Controller
{
    /**
     * Proposer une modification.
     */
    public function proposeModification(Request $request, $person_id)
    {
        $this->authorize('propose', ModificationProposal::class); // Limiter à 5 propositions par jour
        $proposalsToday = ModificationProposal::where('proposer_id', Auth::id())
            ->whereDate('created_at', today())
            ->count();
    
        if ($proposalsToday >= 5) {
            return back()->with('error', 'Vous avez atteint la limite de propositions pour aujourd\'hui.');
        }



        $request->validate([
            'type' => 'required|in:update_person,add_relationship',
            'data' => 'required|json',
        ]);

        $proposal = ModificationProposal::create([
            'proposer_id' => Auth::id(),
            'person_id' => $person_id,
            'type' => $request->type,
            'data' => json_decode($request->data, true),
            'status' => 'pending',
        ]);

        // Historique
        ModificationHistory::create([
            'proposal_id' => $proposal->id,
            'action' => 'created',
            'user_id' => Auth::id(),
            'comments' => 'Proposition de modification créée.',
        ]);

        // Notifier les membres de la communauté (exemple : tous les utilisateurs)
    $users = User::all();
    foreach ($users as $user) {
        $user->notify(new ModificationProposed($proposal));
    }

        return back()->with('success', 'Proposition de modification soumise avec succès.');
    }

    /**
     * Voter sur une proposition.
     */
    public function vote(Request $request, $proposal_id)
    {
        $proposal = ModificationProposal::findOrFail($proposal_id);
        $this->authorize('vote', $proposal);

        $request->validate([
            'vote' => 'required|in:approve,reject',
        ]);

        $proposal = ModificationProposal::findOrFail($proposal_id);

        // Empêcher de voter plusieurs fois
        if ($proposal->votes()->where('voter_id', Auth::id())->exists()) {
            return back()->with('error', 'Vous avez déjà voté sur cette proposition.');
        }

        ProposalVote::create([
            'proposal_id' => $proposal->id,
            'voter_id' => Auth::id(),
            'vote' => $request->vote,
        ]);

        // Vérifier si la proposition atteint le seuil d'approbation ou de rejet
        $approveCount = $proposal->votes()->where('vote', 'approve')->count();
        $rejectCount = $proposal->votes()->where('vote', 'reject')->count();

        if ($approveCount >= 3 && $proposal->status === 'pending') {
            $proposal->update(['status' => 'approved']);

            // Historique
            ModificationHistory::create([
                'proposal_id' => $proposal->id,
                'action' => 'approved',
                'user_id' => Auth::id(),
                'comments' => 'Proposition approuvée.',
            ]);

            // Exécuter la modification
            $this->executeModification($proposal);
        }

        if ($rejectCount >= 3 && $proposal->status === 'pending') {
            $proposal->update(['status' => 'rejected']);

            // Historique
            ModificationHistory::create([
                'proposal_id' => $proposal->id,
                'action' => 'rejected',
                'user_id' => Auth::id(),
                'comments' => 'Proposition rejetée.',
            ]);
        }

        return back()->with('success', 'Votre vote a été enregistré.');
    }

    /**
     * Exécuter une proposition approuvée.
     */
    private function executeModification($proposal)
    {
        DB::transaction(function () use ($proposal) {
            if ($proposal->type === 'update_person') {
                $person = $proposal->person;
                foreach ($proposal->data as $key => $value) {
                    $person->$key = $value;
                }
                $person->save();
            } elseif ($proposal->type === 'add_relationship') {
                DB::table('relationships')->insert([
                    'created_by' => $proposal->proposer_id,
                    'parent_id' => $proposal->data['parent_id'],
                    'child_id' => $proposal->data['child_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Mettre à jour le statut
            $proposal->update(['status' => 'executed']);

            // Historique
            ModificationHistory::create([
                'proposal_id' => $proposal->id,
                'action' => 'executed',
                'user_id' => Auth::id(),
                'comments' => 'Proposition exécutée.',
            ]);
        });
    }

    /**
     * Afficher le formulaire de proposition de modification.
     */
    public function showProposeForm()
    {
        $people = Person::all();
        return view('modifications.propose', compact('people'));
    }

    /**
     * Afficher les propositions en attente pour voter.
     */
    public function showVoteForm()
    {
        // Récupérer les propositions en statut 'pending'
        $proposals = ModificationProposal::where('status', 'pending')->with('proposer')->get();

        return view('modifications.vote', compact('proposals'));
    }
}